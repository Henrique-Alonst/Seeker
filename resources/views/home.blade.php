@extends('layouts.app')

@section('content')
<div class="wrap">
  <header>
    <h1>Arquivo Seeker</h1>
     <span class="tag" id="count-tag">{{ $vagas->count() }} {{ Str::plural('processo', $vagas->count()) }}</span>
  </header>

  <div class="intake">
    <div class="head"><span>Ficha de admissão</span><span>Nº automático</span></div>

    <form action="{{ route('vagas.store') }}" method="POST">
      @csrf

      <div class="row">
        <div class="field grow">
          <label>01 · Cargo</label>
          <input type="text" name="cargo" required placeholder="DESENVOLVEDOR LARAVEL PHP" value="{{ old('cargo') }}">
        </div>
        <div class="field grow">
          <label>02 · Empresa</label>
          <input type="text" name="empresa" required placeholder="BUSCAFREELAS" value="{{ old('empresa') }}">
        </div>
        <div class="field">
          <label>03 · Data</label>
          <input type="date" name="data" value="{{ old('data', now()->format('Y-m-d')) }}">
        </div>
      </div>

      <div class="row">
        <div class="field">
          <label>04 · Status</label>
          <select name="status">
            <option value="aplicado" {{ old('status') == 'aplicado' ? 'selected' : '' }}>Aplicado</option>
            <option value="entrevista" {{ old('status') == 'entrevista' ? 'selected' : '' }}>Entrevista</option>
            <option value="teste" {{ old('status') == 'teste' ? 'selected' : '' }}>Teste técnico</option>
            <option value="oferta" {{ old('status') == 'oferta' ? 'selected' : '' }}>Oferta</option>
            <option value="recusado" {{ old('status') == 'recusado' ? 'selected' : '' }}>Recusado</option>
          </select>
        </div>
        <div class="field grow">
          <label>05 · Link da vaga</label>
          <input type="url" name="link" placeholder="https://..." value="{{ old('link') }}">
        </div>
        <div class="field grow">
          <label>06 · Notas</label>
          <textarea name="notas" placeholder="Detalhes do processo...">{{ old('notas') }}</textarea>
        </div>
        <div class="field grow">
          <label>07 · Salário</label>
          <input type="text" name="salario" placeholder="4.000" value="{{ old('salario') }}">
        </div>
      </div>

      <button type="submit" class="submit-btn">Abrir processo →</button>
    </form>
  </div>

  <div class="archive-label">— arquivo de processos —</div>

  <div class="grid">
    @forelse($vagas as $index => $vaga)
      <div class="case">
        <div class="actions">
            <a href="{{route('vagas.editar', $vaga)}}">editar</a>
            <form action="{{route('vagas.excluir', $vaga)}}" method="POST" class="inline-del"
            onsubmit="return confirm('Excluir esse processo?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="del">Excluir</button>

            </form>
        </div>

        <span class="num">PROC. Nº {{ str_pad($vagas->count() - $index, 3, '0', STR_PAD_LEFT) }}</span>
        <h3>{{ $vaga->cargo }}</h3>
        <div class="company">{{ $vaga->empresa }} — {{ $vaga->data->format('d.m.Y') }} - {{ $vaga->salario }} R$</div>

        @if($vaga->notas)
          <div class="notes">{{ $vaga->notas }}</div>
        @endif

        <div class="footer">
          <span class="stamp st-{{ $vaga->status }}">
            @switch($vaga->status)
              @case('aplicado') Aplicado @break
              @case('entrevista') Entrevista @break
              @case('teste') Teste técnico @break
              @case('oferta') Oferta @break
              @case('recusado') Recusado @break
              @default {{ ucfirst($vaga->status) }}
            @endswitch
          </span>

          @if($vaga->link)
            <a href="{{ $vaga->link }}" target="_blank">ver vaga →</a>
          @else
            <span style="color:#999;">sem link</span>
          @endif
        </div>
      </div>
    @empty
      <p style="color: #777;">Nenhum processo cadastrado até o momento.</p>
    @endforelse
  </div>
</div>
@endsection
