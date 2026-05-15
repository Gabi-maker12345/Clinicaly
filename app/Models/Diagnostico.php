<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Symptom;

class Diagnostico extends Model
{
    protected $table = 'diagnosticos'; 

    protected $fillable = [
        'id_medico',
        'id_paciente',
        'clinic_id',
        'id_doenca',
        'id_sintomas',
        'dados_biometricos',
        'status',
        'doencas_sugeridas',
        'doenca_final',
        'parecer_medico',
        'links_referencia',
        'alertas_criticos',
    ];

    protected $casts = [
        'id_sintomas' => 'array',
        'dados_biometricos' => 'array',
        'links_referencia' => 'array',
        'doencas_sugeridas' => 'array',
        'parecer_medico' => 'array',
        'alertas_criticos' => 'array',
    ];

    protected $appends = [
        'confirmed_disease_name',
    ];

    public function medico() {
        return $this->belongsTo(User::class, 'id_medico');
    }

    public function paciente() {
        return $this->belongsTo(User::class, 'id_paciente');
    }

    public function clinic() {
        return $this->belongsTo(User::class, 'clinic_id');
    }

    public function doenca()
    {
        return $this->belongsTo(Disease::class, 'id_doenca');
    }

    public function getConfirmedDiseaseNameAttribute(): string
    {
        return $this->doenca?->name
            ?? $this->doenca_final
            ?? ($this->parecer_medico['doenca'] ?? null)
            ?? ($this->doencas_sugeridas[0]['nome'] ?? 'Diagnóstico clínico');
    }

    public function sintomas()
    {
        // Carrega sintomas pelos IDs armazenados em id_sintomas (array JSON)
        $ids = (array) $this->id_sintomas;
        return Symptom::whereIn('id', $ids)->get();
    }
}
