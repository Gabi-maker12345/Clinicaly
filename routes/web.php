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
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\ClinicStockController;
use App\Http\Controllers\DoctorAvailabilityController;
use App\Http\Controllers\MultiAccountController;
use App\Http\Controllers\ClinicalRequestController;

Route::get('/', [DiscoveryController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()?->isClinic() ? 'clinic.index' : 'profile.show');
    })->name('dashboard');

    Route::get('/agenda', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/agenda', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::patch('/agenda/{appointment}/aceitar', [AppointmentController::class, 'accept'])->name('appointments.accept');
    Route::patch('/agenda/{appointment}/recusar', [AppointmentController::class, 'reject'])->name('appointments.reject');
    Route::get('/agenda/{appointment}/chat', [AppointmentController::class, 'chat'])->name('appointments.chat');
    Route::patch('/medico/disponibilidade', [DoctorAvailabilityController::class, 'update'])->name('doctor.availability.update');
    Route::post('/consultas/pedido-clinico', [ClinicalRequestController::class, 'store'])->name('clinical-requests.store');
    Route::get('/consultas/pedido-clinico/{message}/diagnosticar', [ClinicalRequestController::class, 'diagnose'])->name('clinical-requests.diagnose');
    Route::get('/multi-contas', [MultiAccountController::class, 'index'])->name('multi-accounts.index');
    Route::post('/multi-contas/conectar', [MultiAccountController::class, 'connect'])->name('multi-accounts.connect');
    Route::get('/multi-contas/{role}/abrir', [MultiAccountController::class, 'open'])->name('multi-accounts.open');
    Route::post('/multi-contas/{role}/alternar', [MultiAccountController::class, 'switch'])->name('multi-accounts.switch');
    Route::delete('/multi-contas/{role}', [MultiAccountController::class, 'disconnect'])->name('multi-accounts.disconnect');

    Route::get('/clinica', [ClinicController::class, 'index'])->name('clinic.index');
    Route::patch('/clinica/horario', [ClinicController::class, 'updateActivityHours'])->name('clinic.hours.update');
    Route::post('/clinica/funcionarios', [ClinicController::class, 'storeEmployee'])->name('clinic.employees.store');
    Route::patch('/clinica/funcionarios/{employee}', [ClinicController::class, 'updateEmployee'])->name('clinic.employees.update');
    Route::delete('/clinica/funcionarios/{employee}', [ClinicController::class, 'destroyEmployee'])->name('clinic.employees.destroy');
    Route::get('/clinica/funcionarios/{employee}/atividades', [ClinicController::class, 'employeeActivities'])->name('clinic.employees.activities');
    Route::patch('/clinica/agendamentos/{appointment}/aceitar', [ClinicController::class, 'acceptAppointment'])->name('clinic.appointments.accept');
    Route::post('/clinica/estoque', [ClinicStockController::class, 'store'])->name('clinic.stock.store');
    Route::patch('/clinica/estoque/{item}', [ClinicStockController::class, 'update'])->name('clinic.stock.update');
    Route::delete('/clinica/estoque/{item}', [ClinicStockController::class, 'destroy'])->name('clinic.stock.destroy');
    Route::post('/clinica/estoque/movimento', [ClinicStockController::class, 'movement'])->name('clinic.stock.movement');
    Route::get('/clinica/estoque/movimentos', [ClinicStockController::class, 'movements'])->name('clinic.stock.movements');
    Route::post('/prescricoes/{prescription}/checkout-estoque', [ClinicStockController::class, 'checkout'])->name('clinic.stock.checkout');
    Route::get('/analise/{area}', function (string $area) {
        return view('pages.placeholder', [
            'title' => match ($area) {
                'triagem' => 'Triagem IA',
                'relatorios' => 'Relatórios',
                'insights' => 'Insights',
                default => 'Análise',
            },
            'icon' => match ($area) {
                'triagem' => 'fa-microscope',
                'relatorios' => 'fa-chart-pie',
                'insights' => 'fa-lightbulb',
                default => 'fa-chart-line',
            },
        ]);
    })->name('placeholder.analise');
    Route::view('/configuracoes', 'profile.settings')->name('settings.index');

    // Acompanhamento (Monitoramento)
    Route::post('/prescriptions/{prescription}/start', [MonitoringController::class, 'startPrescription'])->name('prescriptions.start');
    Route::patch('/monitoring-logs/{log}/complete', [MonitoringController::class, 'completeLog'])->name('monitoring.logs.complete');
    Route::get('/monitoring-logs/{log}/complete-mail', [MonitoringController::class, 'completeLogFromMail'])->middleware('signed')->name('monitoring.logs.complete-mail');
    
    // Notificações (O Sino)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

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
    Route::patch('/messages/conversations/{conversation}', [MessageController::class, 'updateConversation'])->name('messages.conversations.update');
    Route::delete('/messages/conversations/{conversation}', [MessageController::class, 'destroyConversation'])->name('messages.conversations.destroy');
    Route::patch('/messages/{message}', [MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::resource('messages', MessageController::class)->only(['index', 'show', 'store'])->parameters([
        'messages' => 'conversation'
    ]);

    // --- ÁREA MÉDICA E DIAGNÓSTICO ---
    Route::get('/diagnosticar', [DiscoveryController::class, 'diagnostico'])->name('discovery.index');
    Route::post('/diagnosticar/processar', [DiscoveryController::class, 'process'])->name('processar.diagnostico');
    
    // Validação de diagnóstico
    Route::get('/validar-diagnostico/{diagnostico}', [DiagnosticController::class, 'validar'])->name('diagnostico.validar');
    Route::get('/diagnosticos/{diagnostico}/resultado', [DiagnosticController::class, 'resultado'])->name('diagnostico.resultado');
    Route::post('/diagnostico/{diagnostico}/confirmar', [DiagnosticController::class, 'confirmar'])->name('diagnostico.confirmar');
    Route::delete('/diagnostico/{diagnostico}/rejeitar', [DiagnosticController::class, 'rejeitar'])->name('diagnostico.rejeitar');
    Route::get('/diagnosticos/fila', [DiagnosticController::class, 'fila'])->name('diagnostico.fila');
    Route::get('/pacientes/{id}/historico', [DiagnosticController::class, 'history'])->name('patients.history');

    // Prescrições
    Route::get('/prescricoes', [PrescriptionController::class, 'indexMedico'])->name('prescriptions.medico');
    Route::get('/diagnostico/{diagnostico}/prescrever', [PrescriptionController::class, 'create'])->name('prescriptions.create');
    Route::post('/diagnostico/{diagnostico}/prescrever', [PrescriptionController::class, 'store'])->name('prescriptions.store');

    // API de suporte
    Route::get('/api/medicamentos/buscar', [PrescriptionController::class, 'searchMedications'])->name('api.medications.search');
    Route::get('/farmacias/buscar', [PharmacyController::class, 'search'])->name('farmacias.buscar');
    Route::get('/user/profile', [DiagnosticController::class, 'profile'])->name('profile.show');
    Route::post('/user/profile/photo', [ProfilePhotoController::class, 'update'])->name('profile.photo.update');
});

Route::get('/sintomas/corpo/{part}', [SymptomController::class, 'getByBodyPart']);
Route::get('/api/sintomas/buscar', [SymptomController::class, 'searchSymptoms'])->name('api.symptoms.search');
