<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DoctorAvailabilityController extends Controller
{
    public function update()
    {
        $doctor = Auth::user();

        if (! $doctor?->isDoctor()) {
            abort(403);
        }

        $doctor->update([
            'is_available' => ! $doctor->is_available,
        ]);

        return back()->with('success', $doctor->is_available ? 'Você está livre para novas consultas.' : 'Você está ocupado no momento.');
    }
}
