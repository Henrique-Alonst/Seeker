<?php

namespace App\Services;

class FiltroVagaService
{
    public function processarEFiltrar(array $vagasApi, array $skills): array
    {
        $linhasTabela = [];
        $vagasAprovadas = [];

        foreach ($vagasApi as $vaga) {
            $titulo = $vaga['title'] ?? 'Sem título';
            $empresa = $vaga['company']['display_name'] ?? 'Não informada';
            $descricao = $vaga['description'] ?? '';
            $link = $vaga['redirect_url'] ?? 'Link não disponível';

            $textoBusca = mb_strtolower(strip_tags($titulo . ' ' . $descricao), 'UTF-8');
            $skillsEncontradas = [];

            foreach ($skills as $skill) {
                if (str_contains($textoBusca, mb_strtolower($skill, 'UTF-8'))) {
                    $skillsEncontradas[] = strtoupper($skill);
                }
            }

            if (count($skillsEncontradas) > 0) {
                $decisao = 'Aprovada';
                $vagasAprovadas[] = [
                    'titulo' => $titulo,
                    'empresa' => $empresa,
                    'link' => $link
                ];
            } else {
                $decisao = 'Ignorada';
            }

            $linhasTabela[] = [$titulo, $empresa, $decisao, $link];
        }

        return [
            'tabela' => $linhasTabela,
            'aprovadas' => $vagasAprovadas,
        ];
    }
}
