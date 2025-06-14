@php
    $divida = $divida ?? null;
@endphp

<form method="POST" action="{{ $route }}">
    @csrf
    @if ($method === 'PUT')
        @method('PUT')
    @endif

    <div class="row mb-3">
        <div class="col">
            <label for="divida" class="form-label">Despesa</label>
            <input type="text" name="divida" id="divida" class="form-control"
                   value="{{ old('divida', $divida->divida ?? '') }}" required>
        </div>

        <div class="col">
            <label for="valor" class="form-label">Valor</label>
            <input type="text" name="valor" id="valor" class="form-control" placeholder="R$ 0,00"
                   value="{{ old('valor', isset($divida) ? number_format($divida->valor, 2, ',', '.') : '') }}" required>
        </div>

        <div class="col">
            <label for="dia_mes" class="form-label">Vencimento</label>
            <input type="text" name="dia_mes" id="data" class="form-control" placeholder="dd/mm/aaaa"
                   value="{{ old('dia_mes', isset($divida) ? str_pad($divida->vencimento, 2, '0', STR_PAD_LEFT) . '/' . str_pad($divida->mes, 2, '0', STR_PAD_LEFT) . '/' . str_pad($divida->ano, 4, '0', STR_PAD_LEFT) : '') }}" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="pessoal" {{ old('tipo', $divida->tipo ?? '') === 'pessoal' ? 'selected' : '' }}>Pessoal</option>
                <option value="casa" {{ old('tipo', $divida->tipo ?? '') === 'casa' ? 'selected' : '' }}>Casa</option>
            </select>
        </div>

        <div class="col">
            <label for="parcelas" class="form-label">Parcelas</label>
            <input type="number" name="parcelas" id="parcelas" class="form-control" min="0"
                   value="{{ old('parcelas', $divida->parcelas ?? '') }}"
                   {{ old('recorrente', $divida->recorrente ?? false) ? 'disabled' : '' }}>
        </div>

        <div class="col d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="recorrente" name="recorrente"
                    {{ old('recorrente', $divida->recorrente ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="recorrente">Recorrente</label>
            </div>
        </div>
    </div>
    
    <input type="hidden" name="dia" id="diaHidden">
    <input type="hidden" name="mes" id="mesHidden">
    <input type="hidden" name="ano" id="anoHidden">
    <button type="submit" class="btn btn-primary">
        {{ $method === 'PUT' ? 'Atualizar' : 'Cadastrar' }}
    </button>
</form>
@vite('resources\js\regrasform.js')