<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'surname',
        'telephone',
        'password', 
        'role',
        'must_change_password',
        'profil',
        'email',
        'latitude',
        'longitude',
        'is_available',  // disponibilité du livreur
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    // Un propriétaire possède plusieurs biens
    public function biens()
    {
        return $this->hasMany(Bien::class, 'proprietaire_id');
    }
    

    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }

    //Un client peut avoir plusieurs attributions
    public function attributions()
    {
        return $this->hasMany(Attribution::class, 'client_id');
    }

    //Un client peut avoir plusieurs attributions
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'client_id');
    }

    public function boutique()
    {
        return $this->hasOne(Boutique::class, 'proprietaire_id');
    }


    public function isLivreur()
    {
        return $this->role === 'livreur';
    }

    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

}
