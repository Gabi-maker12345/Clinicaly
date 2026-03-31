<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\SymptomController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SearchHistoryController;
use App\Http\Controllers\DescriptionController;
use App\Http\Livewire\ChatIA; // Certifique-se que o namespace está correto

Route::get('/', [DiscoveryController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil e Dashboard
    Route::get('/perfil', [ProfileController::class, 'dashboard'])->name('profile.dashboard');
    
    // Acompanhamento (Monitoramento)
    Route::post('/monitoring/start', [MonitoringController::class, 'store'])->name('monitoring.store');
    Route::patch('/monitoring/{monitoring}/check', [MonitoringController::class, 'checkIn'])->name('monitoring.check');
    
    // Notificações (O Sino)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // --- ÁREA DE ESTUDO E CHAT IA ---
    // A rota principal que carrega o componente Livewire
    Route::get('/estudo', [ChatController::class, 'index'])->name('chat.index');

    // Rotas de recursos médicos (Acesso restrito a usuários logados)
    Route::resource('diseases', DiseaseController::class)->only(['index', 'show']);
    Route::resource('symptoms', SymptomController::class)->only(['index', 'show']);
    Route::resource('medications', MedicationController::class)->only(['index', 'show']);
    Route::resource('categories', CategoryController::class)->only(['index', 'show']);
});

Route::get('/diagnosticar', [DiscoveryController::class, 'diagnostico'])->name('discovery.index');
Route::post('/diagnosticar/processar', [DiscoveryController::class, 'process'])->name('processar.diagnostico');
Route::get('/sintomas/corpo/{part}', [SymptomController::class, 'getByBodyPart']);