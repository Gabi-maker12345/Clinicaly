<?php

namespace Database\Seeders;

use App\Models\Diagnostico;
use App\Models\User;
use Illuminate\Database\Seeder;

class DiagnosticoSeeder extends Seeder
{
    public function run(): void
    {
        // Certifique-se de que existem usuários para evitar erros de FK
        $medico = User::where('role', 'medico')->first() ?? User::factory()->create();
        $paciente = User::where('role', 'paciente')->first() ?? User::factory()->create();

        Diagnostico::create([
            'id_medico' => $medico->id,
            'id_paciente' => $paciente->id,
            
            // Simula a estrutura que o seu Blade percorre no @foreach
            'doencas_sugeridas' => [
                [
                    'nome' => 'Malária Cerebral',
                    'probabilidade' => 87,
                ],
                [
                    'nome' => 'Meningite Bacteriana',
                    'probabilidade' => 65,
                ],
                [
                    'nome' => 'Encefalite Viral',
                    'probabilidade' => 30,
                ]
            ],

            // IDs de sintomas fictícios (conforme seu migration)
            'id_sintomas' => [1, 5, 12, 18],

            // Simula os links do Gemini que aparecem na parte inferior da view
            'links_referencia' => [
                [
                    'fonte' => 'OMS',
                    'url' => 'https://www.who.int',
                    'resumo' => 'Protocolo de tratamento imediato para casos de malária grave em regiões endêmicas.'
                ],
                [
                    'fonte' => 'PubMed',
                    'url' => 'https://pubmed.ncbi.nlm.nih.gov',
                    'resumo' => 'Estudo clínico sobre a diferenciação de sintomas entre meningite e malária cerebral.'
                ]
            ],

            // Dados biométricos (tratados como array para o Blade)
            'dados_biometricos' => [
                'idade' => 28,
                'genero' => 'm',
                'peso' => 74,
                'pressao' => '12/8'
            ],

            'status' => 'pendente',
            'created_at' => now()->subMinutes(15), // Para testar o "há 15 min"
        ]);

        // Exemplo de caso leve (para testar o filtro "Médios/Baixos")
        Diagnostico::create([
            'id_medico' => $medico->id,
            'id_paciente' => $paciente->id,
            'doencas_sugeridas' => [
                ['nome' => 'Rinite Alérgica', 'probabilidade' => 45],
                ['nome' => 'Sinusite Aguda', 'probabilidade' => 20]
            ],
            'id_sintomas' => [2, 3],
            'links_referencia' => [],
            'dados_biometricos' => ['idade' => 19, 'genero' => 'f', 'peso' => 60],
            'status' => 'pendente',
            'created_at' => now()->subHours(2),
        ]);
    }
}