<?php

namespace App\Services;

class FiltroVagaService
{
    public function processarEFiltrar(array $vagasApi, array $skills, string $cidadeLocal = ''): array
    {
        $linhasTabela = [];
        $vagasAprovadas = [];
        $cidadeMin = mb_strtolower($cidadeLocal, 'UTF-8');

        foreach ($vagasApi as $vaga) {
            $titulo    = $vaga['title'] ?? 'Sem título';
            $empresa   = $vaga['company']['display_name'] ?? 'Não informada';
            $descricao = $vaga['description'] ?? '';
            $link      = $vaga['redirect_url'] ?? 'Link não disponível';
            $localVaga = $vaga['location']['display_name'] ?? '';

            $textoBusca = mb_strtolower(strip_tags($titulo . ' ' . $descricao . ' ' . $localVaga), 'UTF-8');

            // 1. Verifica Skills
            $skillsEncontradas = [];
            foreach ($skills as $skill) {
                if (str_contains($textoBusca, mb_strtolower($skill, 'UTF-8'))) {
                    $skillsEncontradas[] = strtoupper($skill);
                }
            }

            // 2. Verifica Localização vs Remoto
            $ehRemoto = str_contains($textoBusca, 'remoto')
                     || str_contains($textoBusca, 'home office')
                     || str_contains($textoBusca, 'teletrabalho');

            $ehDaRegiao = !empty($cidadeMin) && str_contains($textoBusca, $cidadeMin);

            if (count($skillsEncontradas) > 0 && ($ehRemoto || $ehDaRegiao || empty($cidadeLocal))) {
                $decisao = 'Aprovada';
                $vagasAprovadas[] = [
                    'titulo'  => $titulo,
                    'empresa' => $empresa,
                    'link'    => $link,
                ];
            } else {
                $decisao = 'Ignorada';
            }

            $linhasTabela[] = [$titulo, $empresa, $decisao, $link];
        }

        return [
            'tabela'    => $linhasTabela,
            'aprovadas' => $vagasAprovadas,
        ];
    }
}
