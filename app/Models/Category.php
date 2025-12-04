<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Produit;

class Category extends Model
{
    protected $fillable = ['nom', 'slug', 'description'];

    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }
}
