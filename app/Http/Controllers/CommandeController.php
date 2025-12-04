<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    public function __construct()
    {
        // Temporairement, on va déplacer la vérification dans chaque méthode
        // pour éviter les problèmes de session
    }

    /**
     * Afficher la liste des commandes
     */
    public function index(Request $request)
    {
        if (!auth()->check()) {
            abort(401, 'Vous devez être connecté.');
        }

        $query = Commande::with(['user']);

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $commandes = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('commandes.index', compact('commandes'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $this->authorize('create', Commande::class);
        $produits = Produit::all();
        
        return view('commandes.create', compact('produits'));
    }

    /**
     * Enregistrer une nouvelle commande
     */
    public function store(Request $request)
    {
        $request->validate([
            'produits' => 'required|array|min:1',
            'quantites' => 'required|array|min:1',
            // autres validations si besoin
        ]);

        try {
            DB::beginTransaction();

            $commande = new Commande();
            $commande->user_id = auth()->id();
            $commande->statut = 'en_attente';
            $commande->notes = $request->notes;

            // Stocker les détails sous forme de tableau associatif ou JSON
            $details = [];
            $total = 0;
            foreach ($request->produits as $i => $produit_id) {
                $quantite = $request->quantites[$i];
                $details[] = [
                    'produit_id' => $produit_id,
                    'quantite' => $quantite
                ];
                $produit = \App\Models\Produit::find($produit_id);
                if ($produit) {
                    $total += $produit->prix * $quantite;
                }
            }
            $commande->details_commande = ['produits' => $request->produits]; // Simplified for now, should match expected structure
             // Re-structuring details to match what seems to be expected (array of items or map)
             // The original code had $details[] = ... but then used $commande->details_commande['produits'] in show()
             // Let's look at show(): foreach ($this->details_commande['produits'] as $produitId => $quantite)
             // So it expects a map of productId => quantity.
            
            $produitsMap = [];
            foreach ($request->produits as $i => $produit_id) {
                $produitsMap[$produit_id] = $request->quantites[$i];
            }
            $commande->details_commande = ['produits' => $produitsMap];
            
            $commande->total_prix = $total;
            $commande->save();

            DB::commit();

            return redirect()->route('commandes.index')->with('success', 'Commande créée avec succès !');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la création de la commande: ' . $e->getMessage());
        }
    }

    /**
     * Afficher une commande
     */
    public function show(Commande $commande)
    {
        $this->authorize('view', $commande);
        // Décoder les détails de la commande
        $details = is_string($commande->details_commande)
            ? json_decode($commande->details_commande, true)
            : $commande->details_commande;

        // Récupérer les produits avec leurs quantités
        $produits = collect();
        $total = 0;
        
        if (is_array($details) && isset($details['produits'])) {
             foreach ($details['produits'] as $produitId => $quantite) {
                $produit = \App\Models\Produit::find($produitId);
                if ($produit) {
                    $sousTotal = $produit->prix * $quantite;
                    $produit->quantite_commande = $quantite;
                    $produit->sous_total = $sousTotal;
                    $total += $sousTotal;
                    $produits->push($produit);
                }
            }
        }

        return view('commandes.show', compact('commande', 'produits', 'total'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Commande $commande)
    {
        $this->authorize('update', $commande);
        $produits = Produit::all();
        $commande->load(['user']);
        
        return view('commandes.edit', compact('commande', 'produits'));
    }

    /**
     * Mettre à jour une commande
     */
    public function update(Request $request, Commande $commande)
    {
        $this->authorize('update', $commande);
        $request->validate([
            'details_commande' => 'required|array',
            'details_commande.produits' => 'required|array',
            'statut' => 'required|in:en_attente,confirmee,en_preparation,livree,annulee',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $commande->details_commande = $request->details_commande;
            $commande->statut = $request->statut;
            $commande->notes = $request->notes;
            $commande->save();

            // Recalculer le total
            $total = $commande->calculerTotal();
            $commande->total_prix = $total;
            $commande->save();

            DB::commit();

            return redirect()->route('commandes.index')
                ->with('success', 'Commande mise à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la mise à jour de la commande.');
        }
    }

    /**
     * Supprimer une commande
     */
    public function destroy(Commande $commande)
    {
        $this->authorize('delete', $commande);
        try {
            $commande->delete();
            return redirect()->route('commandes.index')
                ->with('success', 'Commande supprimée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de la commande.');
        }
    }

    /**
     * Changer le statut d'une commande
     */
    public function updateStatut(Request $request, Commande $commande)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,confirmee,en_preparation,livree,annulee',
        ]);

        $commande->statut = $request->statut;
        $commande->save();

        return back()->with('success', 'Statut de la commande mis à jour !');
    }
} 