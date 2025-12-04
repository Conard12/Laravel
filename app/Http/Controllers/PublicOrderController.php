<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class PublicOrderController extends Controller
{
    /**
     * Affiche le formulaire de commande (données du panier)
     */
    public function checkout()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $produitId => $quantity) {
            $produit = Produit::find($produitId);

            if ($produit) {
                $cartItems[] = [
                    'produit' => $produit,
                    'quantite' => $quantity,
                    'sous_total' => $produit->prix * $quantity
                ];
                $total += $produit->prix * $quantity;
            }
        }

        if (empty($cartItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Votre panier est vide'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $cartItems,
                'total' => $total,
                'count' => count($cartItems)
            ],
            'message' => 'Données de commande récupérées'
        ]);
    }

    /**
     * Traite la commande et la sauvegarde
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_client' => 'required|string|max:255',
            'email_client' => 'required|email|max:255',
            'telephone_client' => 'nullable|string|max:20',
            'adresse_livraison' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Votre panier est vide'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $details = [];
            $totalCommande = 0;

            foreach ($cart as $produitId => $quantity) {
                $produit = Produit::find($produitId);

                if ($produit) {
                    $details[] = [
                        'produit_id' => $produit->id,
                        'nom_produit' => $produit->nom,
                        'quantite' => $quantity,
                        'prix_unitaire' => $produit->prix,
                        'sous_total' => $produit->prix * $quantity
                    ];
                    $totalCommande += $produit->prix * $quantity;
                }
            }

            $commande = Commande::create([
                'user_id' => null, // Commande publique, pas d'utilisateur connecté
                'nom_client' => $request->nom_client,
                'email_client' => $request->email_client,
                'telephone_client' => $request->telephone_client,
                'adresse_livraison' => $request->adresse_livraison,
                'details_commande' => $details,
                'total_prix' => $totalCommande,
                'statut' => 'en_attente',
                'notes' => $request->notes,
            ]);

            // Vider le panier
            Session::forget('cart');

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'commandes' => [$commande],
                    'total_commandes' => 1
                ],
                'message' => 'Commande créée avec succès ! Vous recevrez un email de confirmation.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la commande. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Affiche l'historique des commandes d'un client (par email)
     */
    public function history(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $commandes = Commande::where('email_client', $request->email)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $commandes,
            'message' => 'Historique des commandes récupéré'
        ]);
    }

    /**
     * Retourne l'historique des commandes du participant connecté (API JSON)
     */
    public function mesCommandesApi(Request $request)
    {
        $user = $request->user();
        $commandes = \App\Models\Commande::where('email_client', $user->email)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $commandes
        ]);
    }

    /**
     * Affiche les détails d'une commande
     */
    public function show($id)
    {
        $commande = Commande::findOrFail($id);

        // Décoder les détails de la commande
        $details = is_string($commande->details_commande) 
            ? json_decode($commande->details_commande, true) 
            : $commande->details_commande;

        return response()->json([
            'success' => true,
            'data' => [
                'commande' => $commande,
                'details' => $details
            ],
            'message' => 'Détails de la commande récupérés'
        ]);
    }
}
