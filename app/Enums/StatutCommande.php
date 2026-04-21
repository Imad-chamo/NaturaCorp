<?php

namespace App\Enums;

enum StatutCommande: string
{
    case EN_COURS = 'en_cours';
    case VALIDEE = 'validee';
    case LIVREE = 'livree';

    public function label(): string
    {
        return match($this) {
            self::EN_COURS => 'En cours',
            self::VALIDEE => 'Validée',
            self::LIVREE => 'Livrée',
        };
    }

}
