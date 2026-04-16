<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Apenas as categorias utilizadas pelas 20 doenças geradas.
        // Cada category_disease associa a doença à sua categoria no DiseasePivotSeeder.
        $categories = [
            ['id' => 1, 'name' => 'Doenças Infecciosas e Parasitárias'],
            ['id' => 2, 'name' => 'Doenças do Aparelho Respiratório'],
            ['id' => 3, 'name' => 'Doenças Cardiovasculares'],
            ['id' => 4, 'name' => 'Doenças Neurológicas'],
            ['id' => 5, 'name' => 'Doenças Endócrinas e Metabólicas'],
            ['id' => 6, 'name' => 'Doenças do Aparelho Digestivo'],
            ['id' => 7, 'name' => 'Doenças Osteomusculares'],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'id'         => $cat['id'],
                'name'       => $cat['name'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
