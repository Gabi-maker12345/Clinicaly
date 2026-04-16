<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,    // 1. Categorias (sem dependências)
            SymptomSeeder::class,     // 2. Sintomas   (sem dependências)
            MedicationSeeder::class,  // 3. Medicamentos (sem dependências)
            DiseaseSeeder::class,     // 4. Doenças (depende de categories)
            DescriptionSeeder::class, // 5. Descrições morphable (depende de diseases, symptoms, medications)
            DiseasePivotSeeder::class,// 6. Pivôs disease_symptom, disease_medication, category_disease
            DiagnosticoSeeder::class,  // 7. Diagnósticos (depende de users, sintomas, doenças)
        ]);
    }
}
