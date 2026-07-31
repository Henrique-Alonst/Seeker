<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seeker — Arquivo de Vagas</title>

    <style>
      @import url('https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Mono:wght@400;700&display=swap');

      :root{
        --bg:#FFFFFF;
        --ink:#0A0A0A;
        --red:#E8402C;
        --gray:#EDEDED;
      }
      *{box-sizing:border-box;}
      body{
        margin:0;
        background:var(--bg);
        color:var(--ink);
        font-family:'Space Mono',monospace;
        padding:36px 24px 100px;
      }
      .wrap{max-width:1040px;margin:0 auto;}

      header{
        display:flex;justify-content:space-between;align-items:flex-end;
        border-bottom:4px solid var(--ink);
        padding-bottom:16px;
        margin-bottom:28px;
        flex-wrap:wrap;gap:10px;
      }
      h1{
        font-family:'Archivo Black',sans-serif;
        font-size:32px;
        margin:0;
        letter-spacing:-.01em;
        text-transform:uppercase;
      }
      header .tag{
        background:var(--ink);color:#fff;
        font-size:11px;padding:5px 10px;
        text-transform:uppercase;letter-spacing:.08em;
      }

      .intake{
        border:2px solid var(--ink);
        padding:0;
        margin-bottom:36px;
      }
      .intake .head{
        background:var(--ink);color:#fff;
        padding:10px 16px;
        font-size:12px;text-transform:uppercase;letter-spacing:.08em;
        display:flex;justify-content:space-between;
      }
      .intake form{padding:20px;}
      .row{display:flex;gap:0;flex-wrap:wrap;}
      .field{
        flex:1 1 200px;
        border-right:1px solid var(--ink);
        padding:0 14px 14px 0;
        margin-right:14px;
      }
      .field:last-child{border-right:none;}
      .field.grow{flex:2 1 260px;}
      label{
        display:block;font-size:10.5px;text-transform:uppercase;letter-spacing:.06em;
        color:#555;margin-bottom:6px;margin-top:14px;
      }
      input,textarea,select{
        width:100%;
        border:none;border-bottom:2px solid var(--ink);
        background:transparent;
        padding:6px 2px;
        font-family:'Space Mono',monospace;
        font-size:13.5px;color:var(--ink);
        border-radius:0;
      }
      input:focus,textarea:focus,select:focus{outline:none;border-bottom-color:var(--red);}
      textarea{resize:vertical;min-height:40px;}

      .submit-btn{
        margin-top:18px;
        background:var(--red);color:#fff;
        border:none;padding:12px 22px;
        font-family:'Space Mono',monospace;font-weight:700;
        font-size:13px;text-transform:uppercase;letter-spacing:.06em;
        cursor:pointer;border-radius:0;
      }
      .submit-btn:hover{background:#C43220;}

      .archive-label{
        font-size:12px;text-transform:uppercase;letter-spacing:.1em;
        color:#777;margin-bottom:14px;
      }

      .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;}
      .case{
        border:2px solid var(--ink);
        position:relative;
        padding:16px 16px 14px;
        background:#fff;
      }
      .case .num{
        position:absolute;top:-13px;left:14px;
        background:#fff;padding:0 6px;
        font-size:11px;color:#777;
      }
      .case h3{
        font-family:'Archivo Black',sans-serif;
        font-size:15px;margin:6px 0 3px;
        text-transform:uppercase;
        line-height:1.25;
      }
      .case .company{font-size:12px;color:#555;margin-bottom:10px;}
      .case .notes{font-size:12px;line-height:1.5;margin-bottom:12px;color:#222;}
      .case .footer{
        display:flex;justify-content:space-between;align-items:center;
        border-top:1px solid var(--ink);padding-top:10px;font-size:11px;
      }
      .stamp{
        font-family:'Space Mono',monospace;font-weight:700;
        font-size:10.5px;text-transform:uppercase;letter-spacing:.05em;
        border:2px solid;
        padding:3px 8px;
        transform:rotate(-3deg);
        display:inline-block;
      }
      .st-aplicado{color:#1F5FBF;border-color:#1F5FBF;}
      .st-entrevista{color:#A66A00;border-color:#A66A00;}
      .st-teste{color:#5B3FBF;border-color:#5B3FBF;}
      .st-oferta{color:#1E8A5A;border-color:#1E8A5A;}
      .st-recusado{color:var(--red);border-color:var(--red);}
      .case a{color:var(--ink);text-decoration:underline;font-size:11px;}
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
