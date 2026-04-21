<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pharmacie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'siret',
        'email',
        'telephone',
        'adresse',
        'code_postal',
        'ville',
        'statut',
        'derniere_prise_contact',
        'commercial_id',
        'zone_id',
        'latitude',
        'longitude',
    ];


    public function commercial()
    {
        return $this->belongsTo(User::class, 'commercial_id');
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function documents()
    {
        return $this->hasMany(DocumentJoint::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    protected static function booted(): void
    {
        static::saving(function ($pharmacie) {
            if ($pharmacie->code_postal) {
                $departement = substr($pharmacie->code_postal, 0, 2);

                // Cas particuliers (ex: Corse, DOM-TOM)
                if (str_starts_with($pharmacie->code_postal, '20')) {
                    $departement = (intval($pharmacie->code_postal) < 20200) ? '2A' : '2B';
                }

                $zone = \App\Models\Zone::where('code_departement', $departement)->first();
                $pharmacie->zone_id = $zone?->id;
            }
        });
    }


}
