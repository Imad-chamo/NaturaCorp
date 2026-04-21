<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationInterne extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'contenu',
        'est_lu',
        'user_id',
    ];

    protected $casts = [
        'est_lu' => 'boolean',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

