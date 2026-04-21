<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    protected $fillable = ['nom', 'code_departement', 'user_id'];

    public function commerciaux()
    {
        return $this->belongsToMany(User::class, 'user_zone');
    }

    public function pharmacies()
    {
        return $this->hasMany(Pharmacie::class);
    }

}
