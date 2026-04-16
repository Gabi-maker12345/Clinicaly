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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Auth;
use App\Livewire\ChatIA;
use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\DashboardController;

Route::get('/', [DiscoveryController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () { 
        // Verifica o papel do usuário. 
        if (Auth::user()->role === 'doctor') {
            return app(DiagnosticController::class)->medicoIndex();
        }
        return app(DashboardController::class)->pacienteIndex();
    })->name('dashboard');

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
    Route::post('/messages/start/{user}', [MessageController::class, 'start'])->name('messages.start');
    Route::get('/messages/search', [MessageController::class, 'search'])->name('messages.search');
    Route::resource('messages', MessageController::class)->only(['index', 'show', 'store'])->parameters([
        'messages' => 'conversation'
    ]);

    // --- ÁREA MÉDICA E DIAGNÓSTICO ---
    Route::get('/diagnosticar', [DiscoveryController::class, 'diagnostico'])->name('discovery.index');
    Route::post('/diagnosticar/processar', [DiscoveryController::class, 'process'])->name('processar.diagnostico');
    
    // Validação de diagnóstico
    Route::get('/validar-diagnostico/{diagnostico}', [DiagnosticController::class, 'validar'])->name('diagnostico.validar');
    Route::post('/diagnostico/{diagnostico}/confirmar', [DiagnosticController::class, 'confirmar'])->name('diagnostico.confirmar');
    Route::delete('/diagnostico/{diagnostico}/rejeitar', [DiagnosticController::class, 'rejeitar'])->name('diagnostico.rejeitar');
    Route::get('/diagnosticos/fila', [DiagnosticController::class, 'fila'])->name('diagnostico.fila');
    Route::get('/pacientes/{id}/historico', [DiagnosticController::class, 'history'])->name('patients.history');

    // Prescrições
    Route::get('/diagnostico/{diagnostico}/prescrever', [PrescriptionController::class, 'create'])->name('prescriptions.create');
    Route::post('/diagnostico/{diagnostico}/prescrever', [PrescriptionController::class, 'store'])->name('prescriptions.store');

    // API de suporte
    Route::get('/api/medicamentos/buscar', [PrescriptionController::class, 'searchMedications'])->name('api.medications.search');
    Route::get('/user/profile', [DiagnosticController::class, 'profile'])->name('profile.show');
});

Route::get('/sintomas/corpo/{part}', [SymptomController::class, 'getByBodyPart']);
Route::get('/api/sintomas/buscar', [SymptomController::class, 'searchSymptoms'])->name('api.symptoms.search');
