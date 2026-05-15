<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Definindo quem é médico
        Gate::define('access-doctor-panel', function (User $user) {
            return $user->isDoctor();
        });

        // Definindo quem é paciente
        Gate::define('access-patient-panel', function (User $user) {
            return $user->isPatient();
        });

        Gate::define('access-clinic-panel', function (User $user) {
            return $user->isClinic();
        });
    }
}
