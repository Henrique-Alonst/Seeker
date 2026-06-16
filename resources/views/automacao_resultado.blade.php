<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Candidato - Automação de Vagas</title>
    <style>
        body { font-family: sans-serif; background: #f4f6f9; margin: 40px; color: #333; }
        .container { max-width: 900px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1, h2 { color: #2c3e50; }
        .perfil { background: #eef2f7; padding: 15px; border-radius: 6px; margin-bottom: 25px; }
        .badge { background: #3498db; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-right: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .status-sucesso { color: #27ae60; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h1>Robô de Candidaturas Automatizadas</h1>
    <p>Protótipo de Validação de Conceito (MVP) para o Projeto Aplicado.</p>

    <div class="perfil">
        <h3>Perfil Monitorado:</h3>
        <p><strong>Candidato:</strong> {{ $perfilCandidato['nome'] }}</p>
        <p><strong>Foco:</strong> {{ $perfilCandidato['cargo_pretendido'] }}</p>
        <p><strong>Skills Cadastradas:</strong>
            @foreach($perfilCandidato['skills'] as $skill)
                <span class="badge">{{ $skill }}</span>
            @endforeach
        </p>
    </div>

    <h2>Relatório de Execução em Segundo Plano</h2>
    <p>O sistema varreu as vagas disponíveis e aplicou o filtro de compatibilidade:</p>

    <table>
        <thead>
            <tr>
                <th>Vaga</th>
                <th>Empresa</th>
                <th>Match de Skills</th>
                <th>Ação do Sistema</th>
                <th>Horário</th>
            </tr>
        </thead>
        <tbody>
            @forelse($relatorioAplicacoes as $aplicacao)
                <tr>
                    <td><strong>{{ $aplicacao['titulo'] }}</strong></td>
                    <td>{{ $aplicacao['empresa'] }}</td>
                    <td>
                        @foreach($aplicacao['skills_compativeis'] as $skill)
                            <span class="badge" style="background-color: #2ecc71;">{{ $skill }}</span>
                        @endforeach
                    </td>
                    <td class="status-sucesso">{{ $aplicacao['status'] }}</td>
                    <td>{{ $aplicacao['data_envio'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nenhuma vaga compatível encontrada no ciclo atual.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
