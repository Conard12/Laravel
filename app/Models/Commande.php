<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commande extends Model
{
    protected $fillable = [
        'user_id',
        'details_commande',
        'statut',
        'total_prix',
        'date_commande',
        'notes',
        // Champs pour les commandes publiques
        'nom_client',
        'email_client',
        'telephone_client',
        'telephone_client',
        'adresse_livraison',
        'stripe_session_id',
        'payment_status'
    ];

    protected $casts = [
        'details_commande' => 'array',
        'date_commande' => 'datetime',
        'total_prix' => 'decimal:2'
    ];



    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir les produits de la commande
     */
    public function getProduits()
    {
        if (is_array($this->details_commande) && isset($this->details_commande['produits'])) {
            return Produit::whereIn('id', array_keys($this->details_commande['produits']))->get();
        }
        return collect();
    }

    /**
     * Calculer le total de la commande
     */
    public function calculerTotal()
    {
        $total = 0;
        if (is_array($this->details_commande) && isset($this->details_commande['produits'])) {
            foreach ($this->details_commande['produits'] as $produitId => $quantite) {
                $produit = Produit::find($produitId);
                if ($produit) {
                    $total += $produit->prix * $quantite;
                }
            }
        }
        return $total;
    }
}
