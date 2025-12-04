<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nom_entreprise', // Ajouté
        'email',
        'password',
        'role', // Ajouté
        'statut',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



    /**
     * Relation avec les commandes
     */
    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    // Méthodes de vérification de rôle
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEntrepreneur()
    {
        return $this->role === 'entrepreneur' && $this->statut === 'approuve';
    }

    public function isEnAttente()
    {
        return $this->role === 'entrepreneur' && $this->statut === 'en_attente';
    }

    public function isParticipant()
    {
        return $this->role === 'participant';
    }

    public function can($ability, $arguments = [])
    {
        if ($ability === 'admin') {
            return $this->role === 'admin';
        }
        
        return false;
    }
}
