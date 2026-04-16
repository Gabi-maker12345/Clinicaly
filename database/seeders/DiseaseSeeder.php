<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiseaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Campos: id, name, icd_code, category_id, min_age, max_age, target_gender, severity
        // category_id é a FK direta na tabela diseases.
        // A tabela pivot category_disease também é preenchida no DiseasePivotSeeder.
        // target_gender: 'male' | 'female' | 'both'
        // severity: 1 (leve) a 5 (crítico)

        $diseases = [
            // ── Cat. 1 – Doenças Infecciosas e Parasitárias ──────────────────
            [
                'id'            => 1,
                'name'          => 'Tuberculose Pulmonar',
                'icd_code'      => 'A15.0',
                'category_id'   => 1,
                'min_age'       => 0,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 4,
            ],
            [
                'id'            => 2,
                'name'          => 'Malária por Plasmodium falciparum',
                'icd_code'      => 'B50',
                'category_id'   => 1,
                'min_age'       => 0,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 4,
            ],
            [
                'id'            => 3,
                'name'          => 'Dengue Hemorrágica',
                'icd_code'      => 'A91',
                'category_id'   => 1,
                'min_age'       => 0,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 5,
            ],
            [
                'id'            => 4,
                'name'          => 'HIV / SIDA',
                'icd_code'      => 'B20',
                'category_id'   => 1,
                'min_age'       => 0,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 5,
            ],
            [
                'id'            => 5,
                'name'          => 'Hepatite B Crónica',
                'icd_code'      => 'B18.1',
                'category_id'   => 1,
                'min_age'       => 0,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 4,
            ],
            [
                'id'            => 6,
                'name'          => 'Meningite Bacteriana',
                'icd_code'      => 'G00',
                'category_id'   => 1,
                'min_age'       => 0,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 5,
            ],

            // ── Cat. 2 – Doenças do Aparelho Respiratório ────────────────────
            [
                'id'            => 7,
                'name'          => 'Asma Brônquica',
                'icd_code'      => 'J45',
                'category_id'   => 2,
                'min_age'       => 0,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 3,
            ],
            [
                'id'            => 8,
                'name'          => 'Pneumonia Bacteriana',
                'icd_code'      => 'J15',
                'category_id'   => 2,
                'min_age'       => 0,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 4,
            ],
            [
                'id'            => 9,
                'name'          => 'Doença Pulmonar Obstrutiva Crónica (DPOC)',
                'icd_code'      => 'J44',
                'category_id'   => 2,
                'min_age'       => 40,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 4,
            ],

            // ── Cat. 3 – Doenças Cardiovasculares ────────────────────────────
            [
                'id'            => 10,
                'name'          => 'Hipertensão Arterial Sistémica',
                'icd_code'      => 'I10',
                'category_id'   => 3,
                'min_age'       => 18,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 3,
            ],
            [
                'id'            => 11,
                'name'          => 'Infarto Agudo do Miocárdio',
                'icd_code'      => 'I21',
                'category_id'   => 3,
                'min_age'       => 30,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 5,
            ],
            [
                'id'            => 12,
                'name'          => 'Acidente Vascular Cerebral Isquémico',
                'icd_code'      => 'I63',
                'category_id'   => 3,
                'min_age'       => 30,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 5,
            ],
            [
                'id'            => 13,
                'name'          => 'Insuficiência Cardíaca Congestiva',
                'icd_code'      => 'I50',
                'category_id'   => 3,
                'min_age'       => 40,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 4,
            ],

            // ── Cat. 4 – Doenças Neurológicas ─────────────────────────────────
            [
                'id'            => 14,
                'name'          => 'Doença de Parkinson',
                'icd_code'      => 'G20',
                'category_id'   => 4,
                'min_age'       => 50,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 4,
            ],
            [
                'id'            => 15,
                'name'          => 'Doença de Alzheimer',
                'icd_code'      => 'G30',
                'category_id'   => 4,
                'min_age'       => 60,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 5,
            ],
            [
                'id'            => 16,
                'name'          => 'Epilepsia',
                'icd_code'      => 'G40',
                'category_id'   => 4,
                'min_age'       => 0,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 4,
            ],

            // ── Cat. 5 – Doenças Endócrinas e Metabólicas ────────────────────
            [
                'id'            => 17,
                'name'          => 'Diabetes Mellitus Tipo 1',
                'icd_code'      => 'E10',
                'category_id'   => 5,
                'min_age'       => 0,
                'max_age'       => 40,
                'target_gender' => 'both',
                'severity'      => 4,
            ],
            [
                'id'            => 18,
                'name'          => 'Diabetes Mellitus Tipo 2',
                'icd_code'      => 'E11',
                'category_id'   => 5,
                'min_age'       => 30,
                'max_age'       => 120,
                'target_gender' => 'both',
                'severity'      => 3,
            ],
            [
                'id'            => 19,
                'name'          => 'Hipotiroidismo',
                'icd_code'      => 'E03',
                'category_id'   => 5,
                'min_age'       => 0,
                'max_age'       => 120,
                'target_gender' => 'female',
                'severity'      => 2,
            ],

            // ── Cat. 7 – Doenças Osteomusculares ─────────────────────────────
            [
                'id'            => 20,
                'name'          => 'Artrite Reumatoide',
                'icd_code'      => 'M05',
                'category_id'   => 7,
                'min_age'       => 20,
                'max_age'       => 120,
                'target_gender' => 'female',
                'severity'      => 3,
            ],
        ];

        foreach ($diseases as $disease) {
            DB::table('diseases')->insert([
                'id'            => $disease['id'],
                'name'          => $disease['name'],
                'icd_code'      => $disease['icd_code'],
                'category_id'   => $disease['category_id'],
                'min_age'       => $disease['min_age'],
                'max_age'       => $disease['max_age'],
                'target_gender' => $disease['target_gender'],
                'severity'      => $disease['severity'],
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }
}
