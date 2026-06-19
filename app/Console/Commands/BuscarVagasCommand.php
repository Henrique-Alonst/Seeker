<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class BuscarVagasCommand extends Command
{
    protected $signature = 'app:buscar-vagas';
    protected $description = 'Consome a API da Adzuna, filtra por stack, exibe links no terminal e envia por e-mail.';

    public function handle()
    {
        $this->info("🤖 Iniciando o Robô de Candidaturas Automatizadas (Back-end)...");

        // 1. Perfil do Candidato
        $perfilCandidato = [
            'nome' => 'Carlos Henrique Alonso Tobias',
            'email' => 'seu-email@gmail.com', // <-- Coloque seu e-mail aqui para receber a lista!
            'skills' => ['laravel', 'php', 'javascript' ]
        ];

        $termoBusca = implode(' ', $perfilCandidato['skills']);
        $appId = env('ADZUNA_APP_ID');
        $appKey = env('ADZUNA_APP_KEY');
        $vagasApi = [];

        if ($appId && $appKey) {
            $this->info("🔍 Conectando à API da Adzuna e buscando por: '{$termoBusca}'...");

            try {
                $response = Http::timeout(8)->get("https://api.adzuna.com/v1/api/jobs/br/search/1", [
                    'app_id' => $appId,
                    'app_key' => $appKey,
                    'what' => $termoBusca,
                    'results_per_page' => 5
                ]);

                if ($response->successful()) {
                    $vagasApi = $response->json()['results'] ?? [];
                }
            } catch (\Exception $e) {
                $this->warn("⚠️ Erro de conexão com a API. Usando dados locais...");
            }
        }

        if (empty($vagasApi)) {
            $this->warn("⚠️ Nenhuma vaga retornada da API. Usando contingência local...");
            $vagasApi = [
                [
                    'title' => 'Desenvolvedor PHP/Laravel',
                    'description' => 'Trabalhar com rotinas de back-end em PHP e framework Laravel.',
                    'company' => ['display_name' => 'Contingência PHP Corp'],
                    'redirect_url' => 'https://adzuna.com.br/exemplo-vaga-php'
                ],
                [
                    'title' => 'Desenvolvedor Front-end',
                    'description' => 'Atuar com JavaScript, HTML e CSS.',
                    'company' => ['display_name' => 'Contingência Web S/A'],
                    'redirect_url' => 'https://adzuna.com.br/exemplo-vaga-front'
                ]
            ];
        }

        // 3. Algoritmo de Matching e Preparação do Relatório
        $headers = ['Vaga', 'Empresa', 'Decisão', 'Link da Vaga'];
        $linhasTabela = [];
        $vagasAprovadas = []; // Array para guardar o que vai pro e-mail

        foreach ($vagasApi as $vaga) {
            $tituloReal = $vaga['title'];
            $empresaReal = $vaga['company']['display_name'] ?? 'Não informada';
            $descricaoReal = $vaga['description'] ?? '';
            $linkReal = $vaga['redirect_url'] ?? 'Link não disponível';

            $textoBusca = mb_strtolower(strip_tags($tituloReal . ' ' . $descricaoReal), 'UTF-8');

            $skillsEncontradas = [];
            foreach ($perfilCandidato['skills'] as $skill) {
                if (str_contains($textoBusca, strtolower($skill))) {
                    $skillsEncontradas[] = strtoupper($skill);
                }
            }

            if (count($skillsEncontradas) > 0) {
                $decisao = 'Aprovada ✅';
                $vagasAprovadas[] = [
                    'titulo' => $tituloReal,
                    'empresa' => $empresaReal,
                    'link' => $linkReal
                ];
            } else {
                $decisao = 'Ignorada ❌';
            }

            // Encurta o link na tabela do terminal para não quebrar o layout da tela
            $linkCurto = substr($linkReal, 0, 45) . '...';

            $linhasTabela[] = [
                $tituloReal,
                $empresaReal,
                $decisao,
                $linkReal // No terminal moderno você consegue clicar direto no link completo
            ];
        }

        $this->newLine();
        $this->info("📊 RELATÓRIO DE PROCESSAMENTO DO ALGORITMO:");
        $this->table($headers, $linhasTabela);

        // 4. Disparo do E-mail Automatizado
        if (count($vagasAprovadas) > 0) {
            $this->info("📧 Enviando lista de vagas aprovadas para " . $perfilCandidato['email'] . "...");

            try {
                Mail::raw($this->formatarMensagemEmail($perfilCandidato['nome'], $vagasAprovadas), function ($message) use ($perfilCandidato) {
                    $message->to($perfilCandidato['email'])
                            ->subject('🤖 Seeker - Suas Vagas Compatíveis do Dia!');
                });
                $this->info("✅ E-mail enviado com sucesso!");
            } catch (\Exception $e) {
                $this->error("❌ Falha ao enviar e-mail. Verifique as configurações do seu .env.");
            }
        }

        return Command::SUCCESS;
    }

    // Auxiliar para montar o texto do e-mail de forma limpa
    private function formatarMensagemEmail($nome, $vagas)
    {
        $mensagem = "Olá, {$nome}!\n\n";
        $mensagem .= "O robô Seeker encontrou as seguintes vagas compatíveis com o seu perfil:\n\n";
        $mensagem .= "--------------------------------------------------\n";

        foreach ($vagas as $vaga) {
            $mensagem .= "📌 Vaga: {$vaga['titulo']}\n";
            $mensagem .= "🏢 Empresa: {$vaga['empresa']}\n";
            $mensagem .= "🔗 Link para Candidatura: {$vaga['link']}\n";
            $mensagem .= "--------------------------------------------------\n";
        }

        $mensagem .= "\nBoa sorte no processo seletivo!\nAtenciosamente,\nRobô Seeker 🤖";
        return $mensagem;
    }
}
