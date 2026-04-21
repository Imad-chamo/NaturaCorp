<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentJoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacie_id',
        'commande_id',
        'nom_fichier',
        'chemin',
        'type',
    ];

    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}
