<?php

namespace App\Services;

use App\Models\JournalActivite;
use Illuminate\Support\Facades\Request;

class JournalService
{
    public static function log(string $action, string $description): void
    {
        JournalActivite::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip' => Request::ip(),
        ]);
    }
}
