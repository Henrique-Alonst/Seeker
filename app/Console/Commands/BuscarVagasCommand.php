<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class BuscarVagasCommand extends Command
{
    /**
     * Assinatura do comando CLI com opções configuráveis.
     */
    protected $signature = 'app:buscar-vagas
                            {--email= : O e-mail do destinatário}
                            {--nome= : O nome do candidato}
                            {--skills= : Lista de termos/skills separados por vírgula}';

    /**
     * Descrição do comando.
     */
    protected $description = 'Consome a API da Adzuna, filtra vagas por termos e envia o relatório por e-mail.';

    public function handle()
    {
        // 1. Definição das variáveis de entrada e parâmetros padrão
        $email = $this->option('email') ?? 'seu-email@gmail.com';
        $nome = $this->option('nome') ?? 'Carlos Henrique Alonso Tobias';

        $skillsInput = $this->option('skills');
        $skills = $skillsInput ? explode(',', $skillsInput) : ['desenvolvedor', 'junior'];

        $this->info("Iniciando busca de vagas...");

        $perfilCandidato = [
            'nome' => $nome,
            'email' => $email,
            'skills' => array_map('trim', $skills)
        ];

        // 2. Preparação dos parâmetros para a API da Adzuna
        $termoBusca = implode(' ', $perfilCandidato['skills']);
        $appId = env('ADZUNA_APP_ID');
        $appKey = env('ADZUNA_APP_KEY');
        $vagasApi = [];

        // 3. Chamada HTTP para a API externa
        if ($appId && $appKey) {
            $this->info("Conectando à API Adzuna (Termo: '{$termoBusca}')...");

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
                $this->warn("Erro de conexão com a API externa. Ativando contingência local.");
            }
        }

        // 4. Fallback: uso de dados locais caso a API falhe ou não retorne dados
        if (empty($vagasApi)) {
            $this->warn("Nenhuma vaga retornada da API. Utilizando dados de contingência.");
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

        // 5. Filtragem de dados com base nas skills do candidato
        $headers = ['Vaga', 'Empresa', 'Decisão', 'Link'];
        $linhasTabela = [];
        $vagasAprovadas = [];

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
                $decisao = 'Aprovada';
                $vagasAprovadas[] = [
                    'titulo' => $tituloReal,
                    'empresa' => $empresaReal,
                    'link' => $linkReal
                ];
            } else {
                $decisao = 'Ignorada';
            }

            $linhasTabela[] = [
                $tituloReal,
                $empresaReal,
                $decisao,
                $linkReal
            ];
        }

        // 6. Exibição do resultado no terminal
        $this->newLine();
        $this->info("Relatório de Processamento:");
        $this->table($headers, $linhasTabela);

        // 7. Envio de e-mail com o resumo das vagas filtradas
        if (count($vagasAprovadas) > 0) {
            $this->info("Enviando e-mail com vagas aprovadas para: " . $perfilCandidato['email']);

            try {
                Mail::raw($this->formatarMensagemEmail($perfilCandidato['nome'], $vagasAprovadas), function ($message) use ($perfilCandidato) {
                    $message->to($perfilCandidato['email'])
                        ->subject('Seeker - Relatório de Vagas Compatíveis');
                });
                $this->info("E-mail enviado com sucesso.");
            } catch (\Exception $e) {
                $this->error("Falha ao enviar e-mail. Verifique as credenciais no arquivo .env.");
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Formata o texto simples que será enviado no corpo do e-mail.
     */
    private function formatarMensagemEmail($nome, $vagas)
    {
        $mensagem = "Olá, {$nome}.\n\n";
        $mensagem .= "As seguintes vagas foram encontradas com base nas suas preferências:\n\n";
        $mensagem .= "--------------------------------------------------\n";

        foreach ($vagas as $vaga) {
            $mensagem .= "Vaga: {$vaga['titulo']}\n";
            $mensagem .= "Empresa: {$vaga['empresa']}\n";
            $mensagem .= "Link: {$vaga['link']}\n";
            $mensagem .= "--------------------------------------------------\n";
        }

        $mensagem .= "\nAtenciosamente,\nSistema Seeker";
        return $mensagem;
    }
}
