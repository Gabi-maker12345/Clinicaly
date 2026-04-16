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
        'id_doenca',
        'id_sintomas',
        'dados_biometricos',
        'status',
        'doencas_sugeridas',
        'links_referencia',
        'alertas_criticos',
    ];

    protected $casts = [
        'id_sintomas' => 'array',
        'dados_biometricos' => 'array',
        'links_referencia' => 'array',
        'doencas_sugeridas' => 'array',
        'alertas_criticos' => 'array',
    ];

    public function medico() {
        return $this->belongsTo(User::class, 'id_medico');
    }

    public function paciente() {
        return $this->belongsTo(User::class, 'id_paciente');
    }

    public function doenca()
    {
        return $this->belongsTo(Disease::class, 'id_doenca');
    }

    public function sintomas()
    {
        // Carrega sintomas pelos IDs armazenados em id_sintomas (array JSON)
        $ids = (array) $this->id_sintomas;
        return Symptom::whereIn('id', $ids)->get();
    }
}