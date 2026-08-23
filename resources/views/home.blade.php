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

  <!-- Filtros por Status -->
<div class="filters" id="arquivo-secao">
  <a href="{{ route('vagas.index') }}#arquivo-secao"
     class="filter-btn {{ !request('status') ? 'active' : '' }}">
    Todos ({{ $vagasCountTotal ?? $vagas->count() }})
  </a>
  <a href="{{ route('vagas.index', ['status' => 'aplicado']) }}#arquivo-secao"
     class="filter-btn {{ request('status') == 'aplicado' ? 'active' : '' }}">
    Aplicado
  </a>
  <a href="{{ route('vagas.index', ['status' => 'entrevista']) }}#arquivo-secao"
     class="filter-btn {{ request('status') == 'entrevista' ? 'active' : '' }}">
    Entrevista
  </a>
  <a href="{{ route('vagas.index', ['status' => 'teste']) }}#arquivo-secao"
     class="filter-btn {{ request('status') == 'teste' ? 'active' : '' }}">
    Teste Técnico
  </a>
  <a href="{{ route('vagas.index', ['status' => 'oferta']) }}#arquivo-secao"
     class="filter-btn {{ request('status') == 'oferta' ? 'active' : '' }}">
    Oferta
  </a>
  <a href="{{ route('vagas.index', ['status' => 'recusado']) }}#arquivo-secao"
     class="filter-btn {{ request('status') == 'recusado' ? 'active' : '' }}">
    Recusado
  </a>
</div>

<div class="archive-label">— arquivo de processos —</div>

  <div class="grid">
    @forelse($vagas as $index => $vaga)
      <div class="case">
        <div class="actions">
            <!-- Botão de edição configurado para abrir o modal -->
            <button type="button"
                    class="btn-edit"
                    onclick="openEditModal({{ json_encode($vaga) }}, '{{ route('vagas.update', $vaga) }}')">
                editar
            </button>

            <form action="{{ route('vagas.excluir', $vaga) }}" method="POST" class="inline-del"
                  onsubmit="return confirm('Excluir esse processo?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="del">Excluir</button>
            </form>
        </div>

        <span class="num">PROC. Nº {{ str_pad($vagas->count() - $index, 3, '0', STR_PAD_LEFT) }}</span>
        <h3>{{ $vaga->cargo }}</h3>
        <div class="company">
          {{ $vaga->empresa }} —
          {{ $vaga->data ? \Carbon\Carbon::parse($vaga->data)->format('d.m.Y') : '' }}
          @if($vaga->salario) - {{ $vaga->salario }} R$ @endif
        </div>

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

<!-- ===== MODAL DE EDIÇÃO ===== -->
<div id="editModal" class="modal-backdrop" style="display: none;">
  <div class="modal-content intake">
    <div class="head">
      <span>Editar Ficha de Admissão</span>
      <button type="button" onclick="closeEditModal()" class="close-btn">&times;</button>
    </div>

    <form id="editForm" action="{}" method="POST">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="field grow">
          <label>01 · Cargo</label>
          <input type="text" id="edit-cargo" name="cargo" required>
        </div>
        <div class="field grow">
          <label>02 · Empresa</label>
          <input type="text" id="edit-empresa" name="empresa" required>
        </div>
        <div class="field">
          <label>03 · Data</label>
          <input type="date" id="edit-data" name="data">
        </div>
      </div>

      <div class="row">
        <div class="field">
          <label>04 · Status</label>
          <select id="edit-status" name="status">
            <option value="aplicado">Aplicado</option>
            <option value="entrevista">Entrevista</option>
            <option value="teste">Teste técnico</option>
            <option value="oferta">Oferta</option>
            <option value="recusado">Recusado</option>
          </select>
        </div>
        <div class="field grow">
          <label>05 · Link da vaga</label>
          <input type="url" id="edit-link" name="link">
        </div>
        <div class="field grow">
          <label>06 · Notas</label>
          <textarea id="edit-notas" name="notas"></textarea>
        </div>
        <div class="field grow">
          <label>07 · Salário</label>
          <input type="text" id="edit-salario" name="salario">
        </div>
      </div>

      <div class="modal-actions">
        <button type="submit" class="submit-btn">Salvar alterações →</button>
        <button type="button" onclick="closeEditModal()" class="btn-cancel">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== SCRIPT DO MODAL ===== -->
<script>
  function openEditModal(vaga, actionUrl) {
    document.getElementById('editForm').action = actionUrl;

    document.getElementById('edit-cargo').value = vaga.cargo || '';
    document.getElementById('edit-empresa').value = vaga.empresa || '';

    if (vaga.data) {
      // Ajusta data no formato YYYY-MM-DD para o input HTML5
      let formattedDate = vaga.data.toString().substring(0, 10);
      document.getElementById('edit-data').value = formattedDate;
    } else {
      document.getElementById('edit-data').value = '';
    }

    document.getElementById('edit-status').value = vaga.status || 'aplicado';
    document.getElementById('edit-link').value = vaga.link || '';
    document.getElementById('edit-notas').value = vaga.notas || '';
    document.getElementById('edit-salario').value = vaga.salario || '';

    document.getElementById('editModal').style.display = 'flex';
  }

  function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
  }

  // Fecha o modal ao clicar na área escura (backdrop)
  window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
      closeEditModal();
    }
  }
</script>
@endsection
