<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SymptomSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // severity: 'low' | 'medium' | 'high'
        $symptoms = [
            // ── Gerais / Sistémicos ──────────────────────────────────────────
            ['id' =>  1, 'name' => 'Febre',                          'severity' => 'medium'],
            ['id' =>  2, 'name' => 'Fadiga intensa',                 'severity' => 'medium'],
            ['id' =>  3, 'name' => 'Perda de peso involuntária',     'severity' => 'high'],
            ['id' =>  4, 'name' => 'Suores noturnos',                'severity' => 'medium'],
            ['id' =>  5, 'name' => 'Calafrios',                      'severity' => 'low'],
            ['id' =>  6, 'name' => 'Mal-estar geral',                'severity' => 'low'],
            ['id' =>  7, 'name' => 'Perda de apetite',               'severity' => 'low'],
            ['id' =>  8, 'name' => 'Dor de cabeça',                  'severity' => 'medium'],

            // ── Respiratórios ────────────────────────────────────────────────
            ['id' =>  9, 'name' => 'Tosse seca',                     'severity' => 'low'],
            ['id' => 10, 'name' => 'Tosse com expectoração',         'severity' => 'medium'],
            ['id' => 11, 'name' => 'Dificuldade respiratória',       'severity' => 'high'],
            ['id' => 12, 'name' => 'Sibilância',                     'severity' => 'medium'],
            ['id' => 13, 'name' => 'Dor torácica',                   'severity' => 'high'],
            ['id' => 14, 'name' => 'Hemoptise',                      'severity' => 'high'],
            ['id' => 15, 'name' => 'Dispneia ao esforço',            'severity' => 'medium'],

            // ── Cardiovasculares ─────────────────────────────────────────────
            ['id' => 16, 'name' => 'Palpitações',                    'severity' => 'medium'],
            ['id' => 17, 'name' => 'Edema nos membros inferiores',   'severity' => 'medium'],
            ['id' => 18, 'name' => 'Dor precordial irradiante',      'severity' => 'high'],
            ['id' => 19, 'name' => 'Tontura',                        'severity' => 'medium'],
            ['id' => 20, 'name' => 'Síncope',                        'severity' => 'high'],

            // ── Neurológicos ─────────────────────────────────────────────────
            ['id' => 21, 'name' => 'Tremor em repouso',              'severity' => 'medium'],
            ['id' => 22, 'name' => 'Rigidez muscular',               'severity' => 'medium'],
            ['id' => 23, 'name' => 'Confusão mental',                'severity' => 'high'],
            ['id' => 24, 'name' => 'Défice de memória',              'severity' => 'high'],
            ['id' => 25, 'name' => 'Crises convulsivas',             'severity' => 'high'],
            ['id' => 26, 'name' => 'Fraqueza muscular unilateral',   'severity' => 'high'],
            ['id' => 27, 'name' => 'Afasia',                         'severity' => 'high'],
            ['id' => 28, 'name' => 'Rigidez de nuca',                'severity' => 'high'],
            ['id' => 29, 'name' => 'Fotofobia',                      'severity' => 'medium'],
            ['id' => 30, 'name' => 'Bradicinesia',                   'severity' => 'medium'],

            // ── Gastrointestinais ────────────────────────────────────────────
            ['id' => 31, 'name' => 'Náuseas',                        'severity' => 'low'],
            ['id' => 32, 'name' => 'Vómitos',                        'severity' => 'medium'],
            ['id' => 33, 'name' => 'Diarreia',                       'severity' => 'medium'],
            ['id' => 34, 'name' => 'Dor abdominal',                  'severity' => 'medium'],
            ['id' => 35, 'name' => 'Icterícia',                      'severity' => 'high'],
            ['id' => 36, 'name' => 'Hepatomegalia',                  'severity' => 'high'],
            ['id' => 37, 'name' => 'Melena',                         'severity' => 'high'],
            ['id' => 38, 'name' => 'Distensão abdominal',            'severity' => 'medium'],

            // ── Metabólicos / Endócrinos ─────────────────────────────────────
            ['id' => 39, 'name' => 'Poliúria',                       'severity' => 'medium'],
            ['id' => 40, 'name' => 'Polidipsia',                     'severity' => 'medium'],
            ['id' => 41, 'name' => 'Polifagia',                      'severity' => 'low'],
            ['id' => 42, 'name' => 'Visão turva',                    'severity' => 'medium'],
            ['id' => 43, 'name' => 'Ganho de peso',                  'severity' => 'low'],
            ['id' => 44, 'name' => 'Intolerância ao frio',           'severity' => 'low'],
            ['id' => 45, 'name' => 'Obstipação',                     'severity' => 'low'],
            ['id' => 46, 'name' => 'Hipoglicemia',                   'severity' => 'high'],

            // ── Osteomusculares ──────────────────────────────────────────────
            ['id' => 47, 'name' => 'Dor articular',                  'severity' => 'medium'],
            ['id' => 48, 'name' => 'Edema articular',                'severity' => 'medium'],
            ['id' => 49, 'name' => 'Rigidez matinal',                'severity' => 'medium'],
            ['id' => 50, 'name' => 'Dor óssea difusa',               'severity' => 'high'],
            ['id' => 51, 'name' => 'Limitação de movimentos',        'severity' => 'medium'],
            ['id' => 52, 'name' => 'Crepitação articular',           'severity' => 'low'],
            ['id' => 53, 'name' => 'Deformidade articular',          'severity' => 'high'],

            // ── Infecciosos específicos ──────────────────────────────────────
            ['id' => 54, 'name' => 'Exantema maculopapular',         'severity' => 'medium'],
            ['id' => 55, 'name' => 'Adenomegalia',                   'severity' => 'medium'],
            ['id' => 56, 'name' => 'Esplenomegalia',                 'severity' => 'high'],
            ['id' => 57, 'name' => 'Dor muscular intensa (mialgia)', 'severity' => 'medium'],
            ['id' => 58, 'name' => 'Prurido',                        'severity' => 'low'],
            ['id' => 59, 'name' => 'Epistaxe',                       'severity' => 'medium'],
            ['id' => 60, 'name' => 'Dor retroocular',                'severity' => 'medium'],
        ];

        foreach ($symptoms as $symptom) {
            DB::table('symptoms')->insert([
                'id'         => $symptom['id'],
                'name'       => $symptom['name'],
                'severity'   => $symptom['severity'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
