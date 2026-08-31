<?php

namespace App\Console\Commands;

use App\Services\AdzunaService;
use App\Services\FiltroVagaService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class BuscarVagasCommand extends Command
{
    protected $signature = 'app:buscar-vagas {--skills= : Lista de termos/skills separados por vírgula}';

    protected $description = 'Consome a API da Adzuna, filtra vagas por termos e envia o relatório por e-mail.';

    public function handle(AdzunaService $adzunaService, FiltroVagaService $filterService): int
    {
        $email  = config('app.candidate.email');
        $nome   = config('app.candidate.name');
        $cidade = config('app.candidate.location', '');
        $raio   = (int) config('app.candidate.km', 50);

        $skillsInput = $this->option('skills');
        $skills = array_map('trim', $skillsInput ? explode(',', $skillsInput) : ['desenvolvedor', 'junior']);

        $this->info("Iniciando busca para: {$nome} ({$email}) em {$cidade} (+{$raio}km) e Vagas Remotas...");

        // 1. Busca na API com filtro de localização
        $vagasApi = $adzunaService->buscarVagas($skills, $cidade, $raio);

        if (empty($vagasApi)) {
            $this->warn("Nenhuma vaga foi encontrada na API ou ocorreu uma falha na conexão.");
            return Command::SUCCESS;
        }

        // 2. Filtragem
        $resultado = $filterService->processarEFiltrar($vagasApi, $skills, $cidade);

        // 3. Exibição na CLI
        $this->newLine();
        $this->info("Relatório de Processamento:");
        $this->table(['Vaga', 'Empresa', 'Decisão', 'Link'], $resultado['tabela']);

        // 4. Envio de E-mail
        if (!empty($resultado['aprovadas'])) {
            $this->enviarEmail($nome, $email, $resultado['aprovadas']);
        } else {
            $this->info("Nenhuma vaga aprovada nos critérios de filtragem.");
        }

        return Command::SUCCESS;
    }

    private function enviarEmail(string $nome, string $email, array $vagas): void
    {
        $this->info("Enviando e-mail para: {$email}");

        try {
            Mail::raw($this->formatarMensagemEmail($nome, $vagas), function ($message) use ($email, $nome) {
                $message->to($email, $nome)
                        ->subject('Seeker - Relatório de Vagas Compatíveis');
            });

            $this->info("E-mail enviado com sucesso.");
        } catch (\Throwable $e) {
            $this->error("Erro SMTP: " . $e->getMessage());
        }
    }

    private function formatarMensagemEmail(string $nome, array $vagas): string
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

        return $mensagem . "\nAtenciosamente,\nSistema Seeker";
    }
}
