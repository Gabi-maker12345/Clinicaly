<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monitoring;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Disease;
use App\Models\Medication;

class MonitoringController extends Controller
{
    public function store(Request $request) {
    $monitoring = Monitoring::create([
        'user_id' => auth()->id(),
        'disease_id' => $request->disease_id,
        'medication_id' => $request->medication_id,
        'start_date' => now(),
        'finish_date' => $request->finish_date,
        'interval_hours' => $request->interval_hours,
        'next_notification_at' => now()->addHours($request->interval_hours),
        'status' => 'active'
    ]);
    return redirect()->route('profile.dashboard')->with('success', 'Acompanhamento iniciado!');
    }

    public function checkIn(Monitoring $monitoring) {
        $monitoring->update([
            'next_notification_at' => now()->addHours($monitoring->interval_hours)
        ]);
        return back();
    }
}
