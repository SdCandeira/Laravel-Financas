<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Dívidas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light p-4">

    <div class="container">
        <h1 class="mb-4">Obrigações e Vencimentos</h1>
        <div>
            <a href="{{ route('dividas.create') }}" class="btn btn-success">Nova Despesa</a><br></br>
        </div>
        
        


        <div class="card" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">Total da casa/Mes Passado:</h5>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div>
                <h6 class="card-subtitle mb-2 text-muted">Valor Total</h6>
                <p class="card-text text-success fw-bold">R${{$totalCasa}}</p>
                </div>
                <div>
                <h6 class="card-subtitle mb-2 text-muted">Valor restante</h6>
                <p class="card-text text-success fw-bold">R${{$totalRestante}}</p>                    
                </div>
            </div>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-subtitle mb-2 text-muted">Mês Anterior</h6>
                    <p class="card-text fw-bold">R${{$mesAnterior}}</p>                    
                </div>
                <div>
                    <h6 class="card-subtitle mb-2 text-muted">Diferença Mês</h6>
                    <p @if($totalCasa - $mesAnterior <= 0) class="card-text text-primary fw-bold" @else class="card-text text-danger fw-bold"  @endif >R${{$totalCasa - $mesAnterior}}</p>                    
                </div>
            </div>
        </div>
        </div>

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <input type="text" id="search" class="form-control w-50" placeholder="Filtrar obrigações...">
            <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" id="mostrarPagasSwitch"
                    {{ request()->has('mostrarPagas') ? 'checked' : '' }}>
            <label class="form-check-label" for="mostrarPagasSwitch">Histórico</label>
            </div>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Vencimento</th>
                    <th>Parcelas</th>
                    <th>Tipo</th>
                    <th>Status</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody id="tabelaDividas">
                @forelse ($dividas as $divida)
                @php
                    $dataVencimento = \Carbon\Carbon::create($divida->ano, $divida->mes, $divida->vencimento);
                    $hoje = \Carbon\Carbon::today();
                @endphp
                    <tr data-status="{{ $divida->status }}"
                     @class([
                        'table-danger' => $dataVencimento->lt($hoje),
                        'table-warning' => $dataVencimento->equalTo($hoje),
                    ])>
                        <td>{{ $divida->divida }}</td>
                        <td>R$ {{ number_format($divida->valor, 2, ',', '.') }}</td>
                        <td>
                            {{
                                \Carbon\Carbon::createFromDate(
                                $divida->ano,
                                $divida->mes,
                                $divida->vencimento
                                )->format('d/m/Y')
                            }}
                        </td>
                        <td>{{ $divida->parcelas }}</td>
                        <td>{{ ucfirst($divida->tipo) }}</td>
                        <td>
                            @if ($divida->status)
                                <span class="badge bg-success">Pago</span>
                            @else
                                <span class="badge bg-warning text-dark">Pendente</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('dividas.edit', $divida->id_divida) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('dividas.pagar', $divida->id_divida) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-sm btn-success" title="Marcar como paga">
                                    <i class="fa-regular fa-square-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('dividas.destroy', $divida->id_divida) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta dívida?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Excluir">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Nenhuma dívida registrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @vite('resources\js\dividas.js')
</body>
</html>