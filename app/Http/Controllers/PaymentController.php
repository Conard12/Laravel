<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function checkout(Commande $commande)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];
        foreach ($commande->getProduits() as $produit) {
            // On récupère la quantité depuis les détails de la commande
            $quantite = $commande->details_commande['produits'][$produit->id] ?? 1;
            
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $produit->nom,
                    ],
                    'unit_amount' => $produit->prix * 100, // En centimes
                ],
                'quantity' => $quantite,
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
            'metadata' => [
                'commande_id' => $commande->id,
            ],
        ]);

        $commande->update(['stripe_session_id' => $session->id]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionId = $request->get('session_id');

        try {
            $session = Session::retrieve($sessionId);
            $commandeId = $session->metadata->commande_id;
            $commande = Commande::find($commandeId);

            if ($commande && $commande->payment_status !== 'paid') {
                $commande->update(['payment_status' => 'paid']);
                // Vider le panier ici si nécessaire
                session()->forget('cart');
            }

            return view('payment.success', compact('commande'));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Erreur lors du paiement.');
        }
    }

    public function cancel()
    {
        return view('payment.cancel');
    }
}
