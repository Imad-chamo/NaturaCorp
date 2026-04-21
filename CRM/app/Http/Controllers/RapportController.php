<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('rapports.index', [
            'rapports' => Rapport::with('utilisateur')->latest()->get()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rapport $rapport)
    {
        return response()->download(storage_path('app/' . $rapport->chemin_fichier));
    }


    public function destroy(Rapport $rapport)
    {
        $rapport->delete();
        return redirect()->route('rapports.index');
    }
}
