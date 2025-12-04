<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produit extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'prix',
        'image',
        'category_id',
        'user_id'
    ];

    protected $casts = [
        'prix' => 'decimal:2'
    ];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
