<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seeker — Arquivo de Vagas</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Mono:wght@400;700&display=swap');

        /* ===== Cores  ===== */
        :root {
            --branco: #FFFFFF;
            --preto: #0A0A0A;
            --vermelho: #E8402C;
            --vermelho-escuro: #C43220;
            --cinza-claro: #EDEDED;
            --cinza-medio: #555555;
            --cinza-escuro: #777777;
            --cinza-fundo: #FAFAFA;

            /* cores dos "carimbos" de status */
            --azul: #1F5FBF;
            --laranja: #A66A00;
            --roxo: #5B3FBF;
            --verde: #1E8A5A;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--branco);
            color: var(--preto);
            font-family: 'Space Mono', monospace;
            padding: 36px 24px 100px;
        }

        .wrap {
            max-width: 1040px;
            margin: 0 auto;
        }

        /* ===== Cabeçalho ===== */
        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-bottom: 4px solid var(--preto);
            padding-bottom: 16px;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 10px;
        }

        h1 {
            font-family: 'Archivo Black', sans-serif;
            font-size: 32px;
            margin: 0;
            letter-spacing: -.01em;
            text-transform: uppercase;
        }

        header .tag {
            background: var(--preto);
            color: var(--branco);
            font-size: 11px;
            padding: 5px 10px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }


        /* ===== Ficha de admissão (formulário de cadastro) ===== */
        .intake {
            border: 2px solid var(--preto);
            margin-bottom: 36px;
        }

        .intake .head {
            background: var(--preto);
            color: var(--branco);
            padding: 10px 16px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .08em;
            display: flex;
            justify-content: space-between;
        }

        .intake form {
            padding: 20px;
        }

        .row {
            display: flex;
            gap: 0;
            flex-wrap: wrap;
        }

        .field {
            flex: 1 1 200px;
            border-right: 1px solid var(--preto);
            padding: 0 14px 14px 0;
            margin-right: 14px;
        }

        .field:last-child {
            border-right: none;
        }

        .field.grow {
            flex: 2 1 260px;
        }

        label {
            display: block;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--cinza-medio);
            margin-bottom: 6px;
            margin-top: 14px;
        }

        input,
        textarea,
        select {
            width: 100%;
            border: none;
            border-bottom: 2px solid var(--preto);
            background: transparent;
            padding: 6px 2px;
            font-family: 'Space Mono', monospace;
            font-size: 13.5px;
            color: var(--preto);
            border-radius: 0;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-bottom-color: var(--vermelho);
        }

        textarea {
            resize: vertical;
            min-height: 40px;
        }

        .submit-btn {
            margin-top: 18px;
            background: var(--vermelho);
            color: var(--branco);
            border: none;
            padding: 12px 22px;
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .06em;
            cursor: pointer;
            border-radius: 0;
        }

        .submit-btn:hover {
            background: var(--vermelho-escuro);
        }

        .archive-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--cinza-escuro);
            margin-bottom: 14px;
        }

        /* ===== Grid de vagas cadastradas ===== */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
        }

        .case {
            border: 2px solid var(--preto);
            position: relative;
            padding: 16px 16px 14px;
            background: var(--branco);
        }

        .case .num {
            position: absolute;
            top: -13px;
            left: 14px;
            background: var(--branco);
            padding: 0 6px;
            font-size: 11px;
            color: var(--cinza-escuro);
        }

        /* botões de editar / excluir no canto do card */
        .case .actions {
            position: absolute;
            top: -13px;
            right: 14px;
            display: flex;
            gap: 6px;
        }

        .case .actions a,
        .case .actions button {
            background: var(--branco);
            border: 2px solid var(--preto);
            font-family: 'Space Mono', monospace;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .05em;
            padding: 2px 7px;
            cursor: pointer;
            color: var(--preto);
            text-decoration: none;
        }

        .case .actions a:hover {
            background: var(--preto);
            color: var(--branco);
        }

        .case .actions button.del:hover {
            background: var(--vermelho);
            border-color: var(--vermelho);
            color: var(--branco);
        }

        .case form.inline-del {
            display: inline;
            margin: 0;
        }

        .case h3 {
            font-family: 'Archivo Black', sans-serif;
            font-size: 15px;
            margin: 6px 0 3px;
            text-transform: uppercase;
            line-height: 1.25;
        }

        .case .company {
            font-size: 12px;
            color: var(--cinza-medio);
            margin-bottom: 10px;
        }

        .case .notes {
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 8px;
            color: #222222;
        }

        .case .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--preto);
            padding-top: 10px;
            font-size: 11px;
        }

        .stamp {
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: .05em;
            border: 2px solid;
            padding: 3px 8px;
            transform: rotate(-3deg);
            display: inline-block;
        }

        .st-aplicado {
            color: var(--azul);
            border-color: var(--azul);
        }

        .st-entrevista {
            color: var(--laranja);
            border-color: var(--laranja);
        }

        .st-teste {
            color: var(--roxo);
            border-color: var(--roxo);
        }

        .st-oferta {
            color: var(--verde);
            border-color: var(--verde);
        }

        .st-recusado {
            color: var(--vermelho);
            border-color: var(--vermelho);
        }

        .case a {
            color: var(--preto);
            text-decoration: underline;
            font-size: 11px;
        }

        /* ===== Estilos para o Modal de Edição ===== */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(10, 10, 10, 0.7);
            /* usa o tom do var(--preto) com opacidade */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }

        .modal-content {
            background: var(--branco);
            max-width: 900px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 8px 8px 0px var(--preto);
        }

        .close-btn {
            background: transparent;
            border: none;
            color: var(--branco);
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
            font-family: 'Space Mono', monospace;
        }

        .close-btn:hover {
            color: var(--vermelho);
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .btn-cancel {
            margin-top: 18px;
            background: var(--cinza-claro);
            color: var(--preto);
            border: 2px solid var(--preto);
            padding: 12px 22px;
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .06em;
            cursor: pointer;
            border-radius: 0;
        }

        .btn-cancel:hover {
            background: var(--cinza-medio);
            color: var(--branco);
        }

        /* Ajuste no botão "editar" no card para manter hover nativo do seu estilo */
        .case .actions button.btn-edit:hover {
            background: var(--preto);
            color: var(--branco);
        }

        .filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .filter-btn {
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
            color: #555;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .filter-btn:hover {
            background-color: #f0f0f0;
            border-color: #ccc;
        }

        .filter-btn.active {
            background-color: #111;
            color: #fff;
            border-color: #111;
        }
    </style>
</head>

<body>
    @yield('content')
</body>

</html>
