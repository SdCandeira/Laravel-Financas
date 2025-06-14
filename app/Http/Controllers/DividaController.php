<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Divida;
use App\Models\Historico;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DividaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->boolean('mostrarPagas')) {
            // Mostrar todas as dívidas em vigor OU já pagas
            $dividas = Historico::where(function ($query) {
                    
                })
                ->orderBy('ano')
                ->orderBy('mes')
                ->orderBy('vencimento')
                ->get();
        } else {
            // Mostrar somente dívidas em vigor e não pagas
            $dividas = Divida::where('em_vigor', 1)
                ->where('status', 0)
                ->orderBy('ano')
                ->orderBy('mes')
                ->orderBy('vencimento')
                ->get();
        }
        $mesAtual = Carbon::now()->month;

        $totalRestante = Divida::where('mes', $mesAtual)
        ->where('tipo', 'casa')
        ->sum('valor');
        $totalCasa = Historico::where('mes', $mesAtual)
        ->where('tipo', 'casa')
        ->sum('valor');
        $totalCasa = $totalCasa +  $totalRestante;
        $mesAnterior = Historico::where('mes', $mesAtual-1)
        ->where('tipo', 'casa')
        ->sum('valor');

        return view('dividas.index', compact('dividas', 'totalCasa', 'totalRestante', 'mesAnterior'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'divida' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'dia' => 'required|integer',
            'mes' => 'required|integer',
            'ano' => 'required|integer',
            'tipo' => 'required|in:pessoal,casa',
            'parcelas' => 'nullable|integer',
        ]);

        Divida::create([
            'divida' => $request->divida,
            'valor' => $request->valor,
            'vencimento' => $request->dia,
            'ano' => $request->ano,
            'mes' => $request->mes,
            'tipo' => $request->tipo,
            'parcelas' => $request->recorrente ? 0 : $request->parcelas,
            'recorrente' => $request->recorrente ? 1 : 0,
            'status' => 0,
            'em_vigor' => 1,
        ]);

        return redirect()->route('dividas.index')->with('success', 'Despesa cadastrada com sucesso!');
    }

    public function create()
    {
        return view('dividas.create');
    }

    public function edit($id_divida)
    {
        $divida = Divida::findOrFail($id_divida);
        $dividas = Divida::where('em_vigor', 1)->orderBy('mes')->get();
    
        return view('dividas.edit', compact('divida', 'dividas'));
    }
    
    public function update(Request $request, $id_divida)
    {
        $dados = $request->validate([
            'divida' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'dia' => 'required|integer',
            'ano' => 'required|integer',
            'mes' => 'required|integer',
            'tipo' => 'required|in:pessoal,casa',
            'parcelas' => 'nullable|integer',
        ]);
    
    
        $divida = Divida::findOrFail($id_divida);
        $divida->update([
            'divida' => $dados['divida'],
            'valor' => $dados['valor'],
            'vencimento' => $request->dia,
            'ano' => $request->ano,
            'mes' => $request->mes,
            'tipo' => $dados['tipo'],
            'parcelas' => $request['recorrente'] ? 0 : ($dados['parcelas'] ?? 0),
            'recorrente' => $request->recorrente ? 1 : 0,
        ]);
    
        return redirect()->route('dividas.index')->with('success', 'Dívida atualizada com sucesso!');
    }

    public function pagar($id_divida)
    {
        $divida = Divida::findOrFail($id_divida);
        // Insere na tab_historico (assumindo que seja um Model chamado Historico)
        DB::table('tab_historico')->insert([
            'id_divida' => $divida->id_divida,
            'divida' => $divida->divida,
            'valor' => $divida->valor,
            'vencimento' => $divida->vencimento,
            'ano' => $divida->ano,
            'mes' => $divida->mes,
            'tipo' => $divida->tipo,
            'parcelas' => $divida->parcelas,
            'recorrente' => $divida->recorrente,
            'status' => $divida->status,
            'em_vigor' => $divida->em_vigor,
            'excluida' => false,
            'data_hora' => Carbon::now(), // timestamp
        ]);

        // Verifica se é recorrente
        if ($divida->recorrente) {
            // Caso seja recorrente: só atualiza o mês
            $novoMes = $divida->mes + 1;
            $novoAno = $divida->ano;
            if ($novoMes > 12) {
                $novoMes = 1;
                $novoAno = $divida->ano + 1;
            }

            $divida->update([
                'mes' => $novoMes,
                'ano' => $novoAno
            ]);
        } else {
            // Caso NÃO seja recorrente
            if ($divida->parcelas == 1) {
                // Última parcela
                $divida->update([
                    'parcelas'  => 0,
                    'em_vigor'  => 0,
                    'status'    => 1
                ]);
            } elseif ($divida->parcelas > 1) {
                // Ainda restam parcelas
                $novoMes = $divida->mes + 1;
                $novoAno = $divida->ano;
                if ($novoMes > 12) {
                    $novoMes = 1;
                    $novoAno = $divida->ano + 1;
                }

                $divida->update([
                    'parcelas' => $divida->parcelas - 1,
                    'mes'      => $novoMes,
                    'ano'      => $novoAno
                ]);
            }
        }
    
        return redirect()->route('dividas.index')->with('success', 'Pagamento registrado com sucesso.');
    }
    public function excluir($id_divida)
    {
        $divida = Divida::findOrFail($id_divida);
        // Insere na tab_historico (assumindo que seja um Model chamado Historico)
        DB::table('tab_historico')->insert([
            'id_divida' => $divida->id_divida,
            'divida' => $divida->divida,
            'valor' => $divida->valor,
            'vencimento' => $divida->vencimento,
            'ano' => $divida->ano,
            'mes' => $divida->mes,
            'tipo' => $divida->tipo,
            'parcelas' => $divida->parcelas,
            'recorrente' => $divida->recorrente,
            'status' => $divida->status,
            'em_vigor' => $divida->em_vigor,
            'excluida' => true,
            'data_hora' => Carbon::now(), // timestamp
        ]);

        $divida->delete();

        return redirect()->route('dividas.index')->with('success', 'Despesa excluída com sucesso!');
    }
}
