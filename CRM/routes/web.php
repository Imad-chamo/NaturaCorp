<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProduitController,
    ProfileController,
    DashboardController,
    PharmacieController,
    CommandeController,
    DocumentJointController,
    NotificationInterneController,
    RapportController,
    CarteController,
    JournalActiviteController,
    UserController
};

Route::get('/', fn() => redirect('dashboard'));

// Public API — no auth required, used by the Vitrine
Route::prefix('api')->group(function () {
    Route::options('{any}', fn() => response()->json([], 204)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Accept')
    )->where('any', '.*');

    Route::get('produits', [\App\Http\Controllers\ProduitController::class, 'publicIndex'])->name('api.produits');
    Route::post('contact',  [\App\Http\Controllers\ContactPublicController::class, 'store'])->name('api.contact');
});

// auth obligatoire pour tout ce qui suit
Route::middleware(['auth'])->group(function () {

    // tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data/commandes', [DashboardController::class, 'chartCommandes'])->name('dashboard.data.commandes');

    // produits (admin uniquement)
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('produits', ProduitController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    // profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // utilisateurs
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/logs', [JournalActiviteController::class, 'index'])->name('admin.logs');
    });

    // pharmacies commandes documents
    Route::middleware(['role:admin|commercial'])->group(function () {
        Route::resource('pharmacies', PharmacieController::class);
        Route::resource('commandes', CommandeController::class);
        Route::resource('documents', DocumentJointController::class)->names('documents');
    });

    // carte
    Route::get('/carte', [CarteController::class, 'index'])->name('carte.index');

    // rapports
    Route::middleware(['role:admin'])->group(function () {
        Route::get('rapports', [RapportController::class, 'index'])->name('rapports.index');
        Route::get('rapports/{rapport}', [RapportController::class, 'show'])->name('rapports.show');
        Route::delete('rapports/{rapport}', [RapportController::class, 'destroy'])->name('rapports.destroy');
    });

    // notifications
    Route::prefix('notifications')->middleware(['auth'])->group(function () {
        Route::get('/fetch', [NotificationInterneController::class, 'fetch'])->name('notifications.fetch');
        Route::post('/read-all', [NotificationInterneController::class, 'markAllAsRead'])->name('notifications.readAll');
        Route::post('/{notification}/read', [NotificationInterneController::class, 'markAsRead'])->name('notifications.read');
        Route::delete('/{notification}', [NotificationInterneController::class, 'destroy'])->name('notifications.destroy');
    });

    // debug (suppression pharmacie)
    Route::get('/debug-delete/{pharmacie}', function (\App\Models\Pharmacie $pharmacie) {
        dd($pharmacie);
    });

    Route::get('/confidentialite', function () {
        return view('politiques.rgpd');
    })->name('confidentialite');

});

require __DIR__.'/auth.php';
