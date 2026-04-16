<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiseasePivotSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ════════════════════════════════════════════════════════════════════
        // 1. disease_symptom  (disease_id, symptom_id, weight 1–10)
        //    weight = importância do sintoma para o diagnóstico desta doença
        //    Cada doença tem MÍNIMO 5 sintomas.
        // ════════════════════════════════════════════════════════════════════
        $diseaseSymptoms = [

            // ── 1 Tuberculose Pulmonar ────────────────────────────────────────
            ['disease_id' =>  1, 'symptom_id' =>  9, 'weight' => 8],  // tosse seca
            ['disease_id' =>  1, 'symptom_id' => 10, 'weight' => 9],  // tosse com expectoração
            ['disease_id' =>  1, 'symptom_id' =>  1, 'weight' => 7],  // febre
            ['disease_id' =>  1, 'symptom_id' =>  4, 'weight' => 8],  // suores noturnos
            ['disease_id' =>  1, 'symptom_id' =>  3, 'weight' => 8],  // perda de peso
            ['disease_id' =>  1, 'symptom_id' => 14, 'weight' => 7],  // hemoptise
            ['disease_id' =>  1, 'symptom_id' =>  2, 'weight' => 6],  // fadiga

            // ── 2 Malária por P. falciparum ───────────────────────────────────
            ['disease_id' =>  2, 'symptom_id' =>  1, 'weight' => 10], // febre
            ['disease_id' =>  2, 'symptom_id' =>  5, 'weight' =>  9], // calafrios
            ['disease_id' =>  2, 'symptom_id' => 57, 'weight' =>  8], // mialgia
            ['disease_id' =>  2, 'symptom_id' =>  8, 'weight' =>  7], // cefaleia
            ['disease_id' =>  2, 'symptom_id' => 31, 'weight' =>  6], // náuseas
            ['disease_id' =>  2, 'symptom_id' => 56, 'weight' =>  7], // esplenomegalia
            ['disease_id' =>  2, 'symptom_id' => 23, 'weight' =>  8], // confusão (malária cerebral)

            // ── 3 Dengue Hemorrágica ──────────────────────────────────────────
            ['disease_id' =>  3, 'symptom_id' =>  1, 'weight' => 10], // febre
            ['disease_id' =>  3, 'symptom_id' =>  8, 'weight' =>  8], // cefaleia
            ['disease_id' =>  3, 'symptom_id' => 60, 'weight' =>  9], // dor retroocular
            ['disease_id' =>  3, 'symptom_id' => 57, 'weight' =>  8], // mialgia
            ['disease_id' =>  3, 'symptom_id' => 54, 'weight' =>  7], // exantema
            ['disease_id' =>  3, 'symptom_id' => 59, 'weight' =>  9], // epistaxe
            ['disease_id' =>  3, 'symptom_id' => 32, 'weight' =>  6], // vómitos

            // ── 4 HIV/SIDA ────────────────────────────────────────────────────
            ['disease_id' =>  4, 'symptom_id' =>  1, 'weight' =>  7], // febre
            ['disease_id' =>  4, 'symptom_id' =>  3, 'weight' =>  9], // perda de peso
            ['disease_id' =>  4, 'symptom_id' =>  2, 'weight' =>  8], // fadiga
            ['disease_id' =>  4, 'symptom_id' => 55, 'weight' =>  8], // adenomegalia
            ['disease_id' =>  4, 'symptom_id' =>  4, 'weight' =>  7], // suores noturnos
            ['disease_id' =>  4, 'symptom_id' =>  9, 'weight' =>  6], // tosse seca
            ['disease_id' =>  4, 'symptom_id' => 33, 'weight' =>  5], // diarreia

            // ── 5 Hepatite B Crónica ──────────────────────────────────────────
            ['disease_id' =>  5, 'symptom_id' => 35, 'weight' =>  9], // icterícia
            ['disease_id' =>  5, 'symptom_id' => 36, 'weight' =>  8], // hepatomegalia
            ['disease_id' =>  5, 'symptom_id' =>  2, 'weight' =>  7], // fadiga
            ['disease_id' =>  5, 'symptom_id' =>  7, 'weight' =>  6], // perda de apetite
            ['disease_id' =>  5, 'symptom_id' => 34, 'weight' =>  6], // dor abdominal
            ['disease_id' =>  5, 'symptom_id' => 31, 'weight' =>  5], // náuseas
            ['disease_id' =>  5, 'symptom_id' => 38, 'weight' =>  7], // distensão abdominal

            // ── 6 Meningite Bacteriana ────────────────────────────────────────
            ['disease_id' =>  6, 'symptom_id' =>  1, 'weight' => 10], // febre
            ['disease_id' =>  6, 'symptom_id' =>  8, 'weight' => 10], // cefaleia
            ['disease_id' =>  6, 'symptom_id' => 28, 'weight' => 10], // rigidez de nuca
            ['disease_id' =>  6, 'symptom_id' => 29, 'weight' =>  9], // fotofobia
            ['disease_id' =>  6, 'symptom_id' => 23, 'weight' =>  9], // confusão mental
            ['disease_id' =>  6, 'symptom_id' => 25, 'weight' =>  8], // crises convulsivas
            ['disease_id' =>  6, 'symptom_id' => 32, 'weight' =>  7], // vómitos

            // ── 7 Asma Brônquica ──────────────────────────────────────────────
            ['disease_id' =>  7, 'symptom_id' => 12, 'weight' => 10], // sibilância
            ['disease_id' =>  7, 'symptom_id' => 11, 'weight' =>  9], // dispneia
            ['disease_id' =>  7, 'symptom_id' =>  9, 'weight' =>  8], // tosse seca
            ['disease_id' =>  7, 'symptom_id' => 13, 'weight' =>  7], // dor torácica
            ['disease_id' =>  7, 'symptom_id' => 15, 'weight' =>  8], // dispneia ao esforço
            ['disease_id' =>  7, 'symptom_id' =>  6, 'weight' =>  4], // mal-estar

            // ── 8 Pneumonia Bacteriana ────────────────────────────────────────
            ['disease_id' =>  8, 'symptom_id' =>  1, 'weight' =>  9], // febre
            ['disease_id' =>  8, 'symptom_id' => 10, 'weight' =>  9], // tosse com expectoração
            ['disease_id' =>  8, 'symptom_id' => 13, 'weight' =>  8], // dor torácica
            ['disease_id' =>  8, 'symptom_id' => 11, 'weight' =>  8], // dispneia
            ['disease_id' =>  8, 'symptom_id' =>  5, 'weight' =>  7], // calafrios
            ['disease_id' =>  8, 'symptom_id' =>  2, 'weight' =>  6], // fadiga
            ['disease_id' =>  8, 'symptom_id' =>  6, 'weight' =>  5], // mal-estar

            // ── 9 DPOC ────────────────────────────────────────────────────────
            ['disease_id' =>  9, 'symptom_id' => 15, 'weight' => 10], // dispneia ao esforço
            ['disease_id' =>  9, 'symptom_id' => 10, 'weight' =>  9], // tosse com expectoração
            ['disease_id' =>  9, 'symptom_id' => 11, 'weight' =>  9], // dispneia
            ['disease_id' =>  9, 'symptom_id' =>  2, 'weight' =>  7], // fadiga
            ['disease_id' =>  9, 'symptom_id' => 12, 'weight' =>  7], // sibilância
            ['disease_id' =>  9, 'symptom_id' => 13, 'weight' =>  6], // dor torácica

            // ── 10 Hipertensão Arterial ───────────────────────────────────────
            ['disease_id' => 10, 'symptom_id' =>  8, 'weight' =>  6], // cefaleia
            ['disease_id' => 10, 'symptom_id' => 19, 'weight' =>  6], // tontura
            ['disease_id' => 10, 'symptom_id' => 16, 'weight' =>  5], // palpitações
            ['disease_id' => 10, 'symptom_id' => 42, 'weight' =>  5], // visão turva
            ['disease_id' => 10, 'symptom_id' =>  6, 'weight' =>  4], // mal-estar geral
            ['disease_id' => 10, 'symptom_id' => 59, 'weight' =>  4], // epistaxe

            // ── 11 IAM ────────────────────────────────────────────────────────
            ['disease_id' => 11, 'symptom_id' => 18, 'weight' => 10], // dor precordial irradiante
            ['disease_id' => 11, 'symptom_id' => 11, 'weight' =>  9], // dispneia
            ['disease_id' => 11, 'symptom_id' => 57, 'weight' =>  7], // diaforese/mialgia
            ['disease_id' => 11, 'symptom_id' => 31, 'weight' =>  6], // náuseas
            ['disease_id' => 11, 'symptom_id' => 20, 'weight' =>  8], // síncope
            ['disease_id' => 11, 'symptom_id' => 16, 'weight' =>  7], // palpitações

            // ── 12 AVC Isquémico ──────────────────────────────────────────────
            ['disease_id' => 12, 'symptom_id' => 26, 'weight' => 10], // fraqueza unilateral
            ['disease_id' => 12, 'symptom_id' => 27, 'weight' =>  9], // afasia
            ['disease_id' => 12, 'symptom_id' => 23, 'weight' =>  8], // confusão
            ['disease_id' => 12, 'symptom_id' =>  8, 'weight' =>  7], // cefaleia
            ['disease_id' => 12, 'symptom_id' => 19, 'weight' =>  7], // tontura
            ['disease_id' => 12, 'symptom_id' => 20, 'weight' =>  8], // síncope
            ['disease_id' => 12, 'symptom_id' => 42, 'weight' =>  7], // visão turva

            // ── 13 ICC ────────────────────────────────────────────────────────
            ['disease_id' => 13, 'symptom_id' => 15, 'weight' => 10], // dispneia ao esforço
            ['disease_id' => 13, 'symptom_id' => 17, 'weight' =>  9], // edema membros inf.
            ['disease_id' => 13, 'symptom_id' =>  2, 'weight' =>  8], // fadiga
            ['disease_id' => 13, 'symptom_id' => 11, 'weight' =>  9], // dispneia
            ['disease_id' => 13, 'symptom_id' => 16, 'weight' =>  7], // palpitações
            ['disease_id' => 13, 'symptom_id' => 19, 'weight' =>  6], // tontura

            // ── 14 Parkinson ──────────────────────────────────────────────────
            ['disease_id' => 14, 'symptom_id' => 21, 'weight' => 10], // tremor em repouso
            ['disease_id' => 14, 'symptom_id' => 22, 'weight' =>  9], // rigidez muscular
            ['disease_id' => 14, 'symptom_id' => 30, 'weight' => 10], // bradicinesia
            ['disease_id' => 14, 'symptom_id' => 51, 'weight' =>  7], // limitação de movimentos
            ['disease_id' => 14, 'symptom_id' => 19, 'weight' =>  6], // tontura
            ['disease_id' => 14, 'symptom_id' =>  6, 'weight' =>  5], // mal-estar

            // ── 15 Alzheimer ──────────────────────────────────────────────────
            ['disease_id' => 15, 'symptom_id' => 24, 'weight' => 10], // défice de memória
            ['disease_id' => 15, 'symptom_id' => 23, 'weight' =>  9], // confusão mental
            ['disease_id' => 15, 'symptom_id' => 27, 'weight' =>  8], // afasia
            ['disease_id' => 15, 'symptom_id' =>  2, 'weight' =>  6], // fadiga
            ['disease_id' => 15, 'symptom_id' =>  6, 'weight' =>  5], // mal-estar
            ['disease_id' => 15, 'symptom_id' => 51, 'weight' =>  5], // limitação (AVDs)

            // ── 16 Epilepsia ──────────────────────────────────────────────────
            ['disease_id' => 16, 'symptom_id' => 25, 'weight' => 10], // crises convulsivas
            ['disease_id' => 16, 'symptom_id' => 23, 'weight' =>  7], // confusão pós-ictal
            ['disease_id' => 16, 'symptom_id' =>  2, 'weight' =>  5], // fadiga
            ['disease_id' => 16, 'symptom_id' =>  8, 'weight' =>  6], // cefaleia pós-ictal
            ['disease_id' => 16, 'symptom_id' => 20, 'weight' =>  8], // síncope/queda

            // ── 17 Diabetes Mellitus Tipo 1 ───────────────────────────────────
            ['disease_id' => 17, 'symptom_id' => 39, 'weight' => 10], // poliúria
            ['disease_id' => 17, 'symptom_id' => 40, 'weight' => 10], // polidipsia
            ['disease_id' => 17, 'symptom_id' => 41, 'weight' =>  8], // polifagia
            ['disease_id' => 17, 'symptom_id' =>  3, 'weight' =>  9], // perda de peso
            ['disease_id' => 17, 'symptom_id' => 42, 'weight' =>  7], // visão turva
            ['disease_id' => 17, 'symptom_id' => 46, 'weight' =>  9], // hipoglicemia
            ['disease_id' => 17, 'symptom_id' =>  2, 'weight' =>  7], // fadiga

            // ── 18 Diabetes Mellitus Tipo 2 ───────────────────────────────────
            ['disease_id' => 18, 'symptom_id' => 39, 'weight' =>  8], // poliúria
            ['disease_id' => 18, 'symptom_id' => 40, 'weight' =>  8], // polidipsia
            ['disease_id' => 18, 'symptom_id' =>  2, 'weight' =>  7], // fadiga
            ['disease_id' => 18, 'symptom_id' => 42, 'weight' =>  6], // visão turva
            ['disease_id' => 18, 'symptom_id' => 43, 'weight' =>  6], // ganho de peso
            ['disease_id' => 18, 'symptom_id' => 46, 'weight' =>  7], // hipoglicemia
            ['disease_id' => 18, 'symptom_id' =>  6, 'weight' =>  4], // mal-estar

            // ── 19 Hipotiroidismo ─────────────────────────────────────────────
            ['disease_id' => 19, 'symptom_id' =>  2, 'weight' =>  9], // fadiga
            ['disease_id' => 19, 'symptom_id' => 43, 'weight' =>  8], // ganho de peso
            ['disease_id' => 19, 'symptom_id' => 44, 'weight' =>  9], // intolerância ao frio
            ['disease_id' => 19, 'symptom_id' => 45, 'weight' =>  7], // obstipação
            ['disease_id' => 19, 'symptom_id' =>  8, 'weight' =>  5], // cefaleia
            ['disease_id' => 19, 'symptom_id' => 22, 'weight' =>  6], // rigidez muscular
            ['disease_id' => 19, 'symptom_id' =>  6, 'weight' =>  5], // mal-estar geral

            // ── 20 Artrite Reumatoide ─────────────────────────────────────────
            ['disease_id' => 20, 'symptom_id' => 47, 'weight' => 10], // dor articular
            ['disease_id' => 20, 'symptom_id' => 48, 'weight' =>  9], // edema articular
            ['disease_id' => 20, 'symptom_id' => 49, 'weight' => 10], // rigidez matinal
            ['disease_id' => 20, 'symptom_id' => 53, 'weight' =>  8], // deformidade articular
            ['disease_id' => 20, 'symptom_id' =>  2, 'weight' =>  7], // fadiga
            ['disease_id' => 20, 'symptom_id' =>  1, 'weight' =>  6], // febre baixa
            ['disease_id' => 20, 'symptom_id' => 51, 'weight' =>  8], // limitação de movimentos
        ];

        foreach ($diseaseSymptoms as $row) {
            DB::table('disease_symptom')->insert([
                'disease_id' => $row['disease_id'],
                'symptom_id' => $row['symptom_id'],
                'weight'     => $row['weight'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ════════════════════════════════════════════════════════════════════
        // 2. disease_medication  (disease_id, medication_id)
        //    Cada doença tem MÍNIMO 3 medicamentos.
        // ════════════════════════════════════════════════════════════════════
        $diseaseMedications = [
            // 1 – Tuberculose (esquema RIPE)
            [1, 9], [1, 10], [1, 11], [1, 12],

            // 2 – Malária por P. falciparum
            [2, 17], [2, 18], [2, 19],

            // 3 – Dengue Hemorrágica (suporte; sem antiviral específico)
            [3, 21], [3, 24], [3, 32],

            // 4 – HIV/SIDA (esquema TAR de base)
            [4, 4], [4, 5], [4, 6],

            // 5 – Hepatite B Crónica
            [5, 4], [5, 5], [5, 2],

            // 6 – Meningite Bacteriana
            [6, 13], [6, 42], [6, 15],

            // 7 – Asma Brônquica
            [7, 58], [7, 59], [7, 60],

            // 8 – Pneumonia Bacteriana
            [8, 7], [8, 8], [8, 13],

            // 9 – DPOC
            [9, 61], [9, 60], [9, 62],

            // 10 – Hipertensão Arterial
            [10, 26], [10, 27], [10, 28], [10, 29],

            // 11 – IAM
            [11, 30], [11, 33], [11, 34], [11, 29],

            // 12 – AVC Isquémico
            [12, 34], [12, 30], [12, 29], [12, 26],

            // 13 – ICC
            [13, 26], [13, 31], [13, 32], [13, 48],

            // 14 – Parkinson
            [14, 35], [14, 36], [14, 21],

            // 15 – Alzheimer
            [15, 37], [15, 38], [15, 21],

            // 16 – Epilepsia
            [16, 39], [16, 40], [16, 41],

            // 17 – Diabetes Mellitus Tipo 1
            [17, 43], [17, 44], [17, 21],

            // 18 – Diabetes Mellitus Tipo 2
            [18, 45], [18, 46], [18, 48], [18, 29],

            // 19 – Hipotiroidismo
            [19, 47], [19, 21], [19, 57],

            // 20 – Artrite Reumatoide
            [20, 23], [20, 53], [20, 24], [20, 20],
        ];

        foreach ($diseaseMedications as [$diseaseId, $medicationId]) {
            DB::table('disease_medication')->insert([
                'disease_id'    => $diseaseId,
                'medication_id' => $medicationId,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        // ════════════════════════════════════════════════════════════════════
        // 3. category_disease  (category_id, disease_id)
        //    Reflecte o belongsToMany entre Category e Disease.
        //    Espelha o category_id da tabela diseases mas permite
        //    múltiplas categorias por doença se necessário.
        // ════════════════════════════════════════════════════════════════════
        $categoryDiseases = [
            // Cat. 1 – Infecciosas
            [1, 1], [1, 2], [1, 3], [1, 4], [1, 5], [1, 6],
            // Cat. 2 – Respiratório
            [2, 7], [2, 8], [2, 9],
            // Cat. 3 – Cardiovascular
            [3, 10], [3, 11], [3, 12], [3, 13],
            // Cat. 4 – Neurológico
            [4, 14], [4, 15], [4, 16],
            // Cat. 5 – Endócrino/Metabólico
            [5, 17], [5, 18], [5, 19],
            // Cat. 7 – Osteomuscular
            [7, 20],
        ];

        foreach ($categoryDiseases as [$categoryId, $diseaseId]) {
            DB::table('category_disease')->insert([
                'category_id' => $categoryId,
                'disease_id'  => $diseaseId,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
