<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicationSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $medications = [
            // ── Antivirais / Infecciosas ─────────────────────────────────────
            ['id' =>  1, 'name' => 'Oseltamivir (Tamiflu)',           'active_principle' => 'Oseltamivir fosfato'],
            ['id' =>  2, 'name' => 'Ribavirina',                      'active_principle' => 'Ribavirina'],
            ['id' =>  3, 'name' => 'Aciclovir',                       'active_principle' => 'Aciclovir'],
            ['id' =>  4, 'name' => 'Tenofovir',                       'active_principle' => 'Tenofovir disoproxil fumarato'],
            ['id' =>  5, 'name' => 'Lamivudina',                      'active_principle' => 'Lamivudina'],
            ['id' =>  6, 'name' => 'Dolutegravir',                    'active_principle' => 'Dolutegravir sódico'],

            // ── Antibióticos ─────────────────────────────────────────────────
            ['id' =>  7, 'name' => 'Amoxicilina + Ácido Clavulânico', 'active_principle' => 'Amoxicilina / Clavulanato de potássio'],
            ['id' =>  8, 'name' => 'Azitromicina',                    'active_principle' => 'Azitromicina di-hidratada'],
            ['id' =>  9, 'name' => 'Isoniazida',                      'active_principle' => 'Isoniazida'],
            ['id' => 10, 'name' => 'Rifampicina',                     'active_principle' => 'Rifampicina'],
            ['id' => 11, 'name' => 'Pirazinamida',                    'active_principle' => 'Pirazinamida'],
            ['id' => 12, 'name' => 'Etambutol',                       'active_principle' => 'Cloridrato de etambutol'],
            ['id' => 13, 'name' => 'Ceftriaxona',                     'active_principle' => 'Ceftriaxona sódica'],
            ['id' => 14, 'name' => 'Doxiciclina',                     'active_principle' => 'Cloridrato de doxiciclina'],
            ['id' => 15, 'name' => 'Benzilpenicilina (Penicilina G)', 'active_principle' => 'Benzilpenicilina potássica'],
            ['id' => 16, 'name' => 'Ciprofloxacino',                  'active_principle' => 'Ciprofloxacino cloridrato'],

            // ── Antiparasitários ─────────────────────────────────────────────
            ['id' => 17, 'name' => 'Artemetér + Lumefantrina',        'active_principle' => 'Artemetér / Lumefantrina'],
            ['id' => 18, 'name' => 'Cloroquina',                      'active_principle' => 'Difosfato de cloroquina'],
            ['id' => 19, 'name' => 'Primaquina',                      'active_principle' => 'Difosfato de primaquina'],

            // ── Anti-inflamatórios / Analgésicos ────────────────────────────
            ['id' => 20, 'name' => 'Ibuprofeno',                      'active_principle' => 'Ibuprofeno'],
            ['id' => 21, 'name' => 'Paracetamol',                     'active_principle' => 'Paracetamol (Acetaminofeno)'],
            ['id' => 22, 'name' => 'Naproxeno',                       'active_principle' => 'Naproxeno sódico'],
            ['id' => 23, 'name' => 'Metotrexato',                     'active_principle' => 'Metotrexato'],
            ['id' => 24, 'name' => 'Prednisona',                      'active_principle' => 'Prednisona'],
            ['id' => 25, 'name' => 'Hidrocortisona',                  'active_principle' => 'Succinato sódico de hidrocortisona'],

            // ── Cardiovasculares ─────────────────────────────────────────────
            ['id' => 26, 'name' => 'Enalapril',                       'active_principle' => 'Maleato de enalapril'],
            ['id' => 27, 'name' => 'Amlodipina',                      'active_principle' => 'Besilato de amlodipina'],
            ['id' => 28, 'name' => 'Losartan',                        'active_principle' => 'Losartan potássico'],
            ['id' => 29, 'name' => 'Atorvastatina',                   'active_principle' => 'Atorvastatina cálcica'],
            ['id' => 30, 'name' => 'AAS (Ácido Acetilsalicílico)',    'active_principle' => 'Ácido acetilsalicílico'],
            ['id' => 31, 'name' => 'Carvedilol',                      'active_principle' => 'Carvedilol'],
            ['id' => 32, 'name' => 'Furosemida',                      'active_principle' => 'Furosemida'],
            ['id' => 33, 'name' => 'Clopidogrel',                     'active_principle' => 'Bissulfato de clopidogrel'],
            ['id' => 34, 'name' => 'Alteplase (tPA)',                  'active_principle' => 'Alteplase (ativador do plasminogênio tecidual)'],

            // ── Neurológicos ─────────────────────────────────────────────────
            ['id' => 35, 'name' => 'Levodopa + Carbidopa',            'active_principle' => 'Levodopa / Carbidopa'],
            ['id' => 36, 'name' => 'Rasagilina',                      'active_principle' => 'Mesilato de rasagilina'],
            ['id' => 37, 'name' => 'Donepezila',                      'active_principle' => 'Cloridrato de donepezila'],
            ['id' => 38, 'name' => 'Memantina',                       'active_principle' => 'Cloridrato de memantina'],
            ['id' => 39, 'name' => 'Valproato de Sódio',              'active_principle' => 'Ácido valproico / Valproato de sódio'],
            ['id' => 40, 'name' => 'Carbamazepina',                   'active_principle' => 'Carbamazepina'],
            ['id' => 41, 'name' => 'Lamotrigina',                     'active_principle' => 'Lamotrigina'],
            ['id' => 42, 'name' => 'Dexametasona',                    'active_principle' => 'Fosfato dissódico de dexametasona'],

            // ── Endócrinos / Metabólicos ─────────────────────────────────────
            ['id' => 43, 'name' => 'Insulina NPH',                    'active_principle' => 'Insulina isófana humana'],
            ['id' => 44, 'name' => 'Insulina Regular',                'active_principle' => 'Insulina humana regular'],
            ['id' => 45, 'name' => 'Metformina',                      'active_principle' => 'Cloridrato de metformina'],
            ['id' => 46, 'name' => 'Glibenclamida',                   'active_principle' => 'Glibenclamida'],
            ['id' => 47, 'name' => 'Levotiroxina Sódica',             'active_principle' => 'Levotiroxina sódica'],
            ['id' => 48, 'name' => 'Empagliflozina',                  'active_principle' => 'Empagliflozina'],

            // ── Digestivos ───────────────────────────────────────────────────
            ['id' => 49, 'name' => 'Omeprazol',                       'active_principle' => 'Omeprazol'],
            ['id' => 50, 'name' => 'Metronidazol',                    'active_principle' => 'Metronidazol'],
            ['id' => 51, 'name' => 'Mesalazina',                      'active_principle' => 'Ácido 5-aminossalicílico'],
            ['id' => 52, 'name' => 'Azatioprina',                     'active_principle' => 'Azatioprina'],

            // ── Osteomusculares ──────────────────────────────────────────────
            ['id' => 53, 'name' => 'Hidroxicloroquina',               'active_principle' => 'Sulfato de hidroxicloroquina'],
            ['id' => 54, 'name' => 'Alopurinol',                      'active_principle' => 'Alopurinol'],
            ['id' => 55, 'name' => 'Colchicina',                      'active_principle' => 'Colchicina'],
            ['id' => 56, 'name' => 'Risedronato de Sódio',            'active_principle' => 'Risedronato monossódico'],
            ['id' => 57, 'name' => 'Carbonato de Cálcio + Vitamina D','active_principle' => 'Carbonato de cálcio / Colecalciferol'],

            // ── Respiratórios ────────────────────────────────────────────────
            ['id' => 58, 'name' => 'Salbutamol (Ventolin)',           'active_principle' => 'Sulfato de salbutamol'],
            ['id' => 59, 'name' => 'Budesonida',                      'active_principle' => 'Budesonida'],
            ['id' => 60, 'name' => 'Formoterol + Budesonida',         'active_principle' => 'Fumarato de formoterol / Budesonida'],
            ['id' => 61, 'name' => 'Tiotropio',                       'active_principle' => 'Brometo de tiotrópio'],
            ['id' => 62, 'name' => 'N-Acetilcisteína',                'active_principle' => 'N-Acetilcisteína'],
        ];

        foreach ($medications as $med) {
            DB::table('medications')->insert([
                'id'              => $med['id'],
                'name'            => $med['name'],
                'active_principle'=> $med['active_principle'],
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }
    }
}
