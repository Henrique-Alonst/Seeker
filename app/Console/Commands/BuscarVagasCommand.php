<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class BuscarVagasCommand extends Command
{
    protected $signature = 'app:buscar-vagas';
    protected $description = 'Consome a API de vagas dinamicamente baseada na stack do candidato.';

    public function handle()
    {
        $this->info("🤖 Iniciando o Robô de Candidaturas Automatizadas (Back-end)...");

        // 1. Perfil do Candidato (Mude as skills aqui para testar o comportamento!)
        $perfilCandidato = [
            'nome' => 'Henrique Alonso',
            'skills' => ['html',] // Teste mudar para ['javascript', 'css'] ou ['python']
        ];

        // Transforma o array de skills em uma string para a busca (ex: "php laravel")
        $termoBusca = implode(' ', $perfilCandidato['skills']);

        $appId = env('ADZUNA_APP_ID');
        $appKey = env('ADZUNA_APP_KEY');
        $vagasApi = [];

        if ($appId && $appKey) {
            // Agora o log mostra dinamicamente o que estamos caçando na API
            $this->info("🔍 Conectando à API da Adzuna e buscando por: '{$termoBusca}'...");

            try {
                $response = Http::timeout(8)->get("https://api.adzuna.com/v1/api/jobs/br/search/1", [
                    'app_id' => $appId,
                    'app_key' => $appKey,
                    'what' => $termoBusca, // <-- AQUI ESTÁ A MÁGICA: A busca agora é dinâmica!
                    'results_per_page' => 5
                ]);

                if ($response->successful()) {
                    $vagasApi = $response->json()['results'] ?? [];
                }
            } catch (\Exception $e) {
                $this->warn("⚠️ Erro de conexão com a API. Usando dados locais...");
            }
        }

        // 2. Garantia de Dados Local (Caso a API não traga nada)
        if (empty($vagasApi)) {
            $this->warn("⚠️ Nenhuma vaga retornada da API para a stack informada. Usando contingência local...");
            $vagasApi = [
                [
                    'title' => 'Desenvolvedor PHP/Laravel',
                    'description' => 'Trabalhar com rotinas de back-end em PHP e framework Laravel.',
                    'company' => ['display_name' => 'Contingência PHP Corp']
                ],
                [
                    'title' => 'Desenvolvedor Front-end',
                    'description' => 'Atuar com JavaScript, HTML e CSS.',
                    'company' => ['display_name' => 'Contingência Web S/A']
                ]
            ];
        }

        // 3. Algoritmo de Matching
        $headers = ['Vaga', 'Empresa', 'Skills Detectadas', 'Decisão do Robô'];
        $linhasTabela = [];

        foreach ($vagasApi as $vaga) {
            $tituloReal = $vaga['title'];
            $empresaReal = $vaga['company']['display_name'] ?? 'Não informada';
            $descricaoReal = $vaga['description'] ?? '';

            $textoBusca = mb_strtolower(strip_tags($tituloReal . ' ' . $descricaoReal), 'UTF-8');

            $skillsEncontradas = [];
            foreach ($perfilCandidato['skills'] as $skill) {
                if (str_contains($textoBusca, strtolower($skill))) {
                    $skillsEncontradas[] = strtoupper($skill);
                }
            }

            if (count($skillsEncontradas) > 0) {
                $linhasTabela[] = [
                    $tituloReal,
                    $empresaReal,
                    implode(', ', $skillsEncontradas),
                    'Candidatura Automatizada ✅'
                ];
            } else {
                $linhasTabela[] = [
                    $tituloReal,
                    $empresaReal,
                    'NENHUMA',
                    'Ignorada (Sem perfil) ❌'
                ];
            }
        }

        $this->newLine();
        $this->info("📊 RELATÓRIO DE PROCESSAMENTO DO ALGORITMO:");
        $this->table($headers, $linhasTabela);

        return Command::SUCCESS;
    }
}
