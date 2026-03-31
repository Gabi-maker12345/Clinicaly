<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckMedicationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-medication-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        
        // Busca acompanhamentos ativos onde a hora da próxima dose já passou
        $pending = Monitoring::where('status', 'active')
            ->where('next_notification_at', '<=', $now)
            ->get();

        foreach ($pending as $item) {
            // Envia a notificação para o banco (o que faz o ícone vermelho aparecer)
            $item->user->notify(new \App\Notifications\MedicationReminder($item));

            // Calcula a próxima dose baseada no intervalo (ex: +8 horas)
            $item->update([
                'next_notification_at' => $now->addHours($item->interval_hours)
            ]);
        }
    }
}
