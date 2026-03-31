<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disease;
use App\Models\Symptom;
use App\Models\Category;
use App\Models\Medication;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $jsonPath = database_path('data/diseases.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error("Arquivo JSON não encontrado em: $jsonPath");
            return;
        }

        $data = json_decode(File::get($jsonPath), true);

        foreach ($data as $item) {
            // 2. SEEDER DE CATEGORIA: Cria ou recupera a categoria
            $category = Category::firstOrCreate(['name' => $item['category']]);

            // 3. SEEDER DE DOENÇA: Cria a doença ligada à categoria
            $disease = Disease::create([
                'name' => $item['name'],
                'category_id' => $category->id,
            ]);

            // 4. SEEDER DE SINTOMAS: Itera sobre os nomes científicos do JSON
            $symptomIds = [];
            foreach ($item['symptoms'] as $symptomName) {
                $symptom = Symptom::firstOrCreate(['name' => $symptomName]);
                $symptomIds[] = $symptom->id;
            }
            // Faz a relação Muitos-para-Muitos (Tabela Pivô)
            $disease->symptoms()->attach($symptomIds, ['weight' => 10]);

            // 5. SEEDER DE REMÉDIOS: Itera sobre os medicamentos
            $medicationIds = [];
            foreach ($item['medications'] as $medName) {
                $medication = Medication::firstOrCreate(['name' => $medName]);
                $medicationIds[] = $medication->id;
            }
            // Faz a relação Muitos-para-Muitos (Tabela Pivô)
            $disease->medications()->attach($medicationIds);

            // 6. SEEDER DE DESCRIÇÕES: Polimórfico
            $disease->descriptions()->create([
                'type' => 'professional',
                'content' => $item['description_pro']
            ]);
            $disease->descriptions()->create([
                'type' => 'plain',
                'content' => $item['description_plain']
            ]);
            
        }

        $this->command->info('Banco de dados Clinicaly populado com sucesso!');
    }
}