<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\StatutCommande;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacie_id',
        'user_id',
        'date_commande',
        'statut',
        'quantite',
        'tarif_unitaire',
        'observations',
        'produit_id',
    ];

    protected $casts = [
        'statut' => StatutCommande::class,
        'date_commande' => 'date',
    ];

    protected $appends = ['statut_label'];


    public function pharmacie(): BelongsTo
    {
        return $this->belongsTo(Pharmacie::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatutLabelAttribute(): string
    {
        return $this->statut?->label() ?? '';
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function document()
    {
        return $this->hasOne(DocumentJoint::class);
    }


}
