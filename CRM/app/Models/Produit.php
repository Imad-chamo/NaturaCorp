<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{

    use hasFactory;
    protected $fillable = ['nom', 'description', 'categorie', 'tarif_unitaire', 'stock', 'stock_alerte', 'is_actif'];

    protected $casts    = ['is_actif' => 'boolean'];
    protected $appends  = ['image_url'];

    private const IMAGES = [
        'Vitamine D3 + K2'      => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600&q=80&auto=format&fit=crop',
        'Curcuma & Poivre Noir'  => 'https://images.unsplash.com/photo-1615485500704-8e990f9900f7?w=600&q=80&auto=format&fit=crop',
        'Magnésium Marin'        => 'https://images.unsplash.com/photo-1550572017-edd951b55104?w=600&q=80&auto=format&fit=crop',
        'Probiotiques Pro 50'    => 'https://images.unsplash.com/photo-1611077543185-f13ccf247b0b?w=600&q=80&auto=format&fit=crop',
        'Vitamine C Liposomale'  => 'https://images.unsplash.com/photo-1612776572997-76cc42e058c3?w=600&q=80&auto=format&fit=crop',
        'Zinc & Sélénium'        => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&q=80&auto=format&fit=crop',
        'Complexe B Actif'       => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=600&q=80&auto=format&fit=crop',
    ];

    public function getImageUrlAttribute(): string
    {
        return self::IMAGES[$this->nom]
            ?? 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=600&q=80&auto=format&fit=crop';
    }

    public function isStockFaible(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->stock_alerte;
    }

    public function isRupture(): bool
    {
        return $this->stock === 0;
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function decrementStock(int $quantite): bool
    {
        if ($this->stock < $quantite) {
            return false;
        }

        $this->stock -= $quantite;
        $this->save();

        return true;
    }
}
