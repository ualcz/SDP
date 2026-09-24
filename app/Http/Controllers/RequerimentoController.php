<?php

namespace App\Http\Controllers;

use App\Models\Requerimento;
use Illuminate\Http\Request;
use App\Models\Setor;
use Illuminate\Support\Facades\Auth;

class RequerimentoController extends Controller
{
    public function create(Request $request)
    {
        $modelos = Setor::obterSetoresFormatados();

        $matricula = strtoupper($request->user()->matricula ?? '');
        $coordenacaoPermitida = null;

        // Remove os 5 primeiros caracteres (4 dígitos do ano + 1 do período)
        // Exemplo: '20211180001' vira '180001'
        $sufixoMatricula = substr($matricula, 5);

        // Identifica o setor com base no início do código do curso
        if (str_starts_with($sufixoMatricula, '18')) {
            $coordenacaoPermitida = 'COINF';
        } elseif (str_starts_with($sufixoMatricula, 'SEAADS')) {
            $coordenacaoPermitida = 'COADS';
        } elseif (str_starts_with($sufixoMatricula, '28')) {
            $coordenacaoPermitida = 'COMAM';
        } else {
            $coordenacaoPermitida = 'COLIC';
        }

        // Siglas de todas as coordenações que dependem do curso do aluno
        $coordenacoesRestritas = ['COINF', 'COADS', 'COMAM', 'COLIC'];

        // 1. Filtra a lista de modelos
        $modelos = array_filter($modelos, function ($mod) use ($coordenacaoPermitida, $coordenacoesRestritas) {
            $sigla = strtoupper($mod['setor_sigla'] ?? '');

            // Se o setor for uma coordenação de curso, só mantém se for a do aluno
            if (in_array($sigla, $coordenacoesRestritas)) {
                return $sigla === $coordenacaoPermitida;
            }

            // Outros setores (ex: Biblioteca, SRA, DAE) aparecem normalmente
            return true;
        });

        // 2. Define o setor selecionado via query string (?setor=...)
        $setorParam = $request->query('setor', $request->query('modelo'));
        $modeloAtivo = null;

        if ($setorParam) {
            if (isset($modelos[$setorParam])) {
                $modeloAtivo = $modelos[$setorParam];
            } else {
                foreach ($modelos as $mod) {
                    if (strcasecmp($mod['setor_sigla'], $setorParam) === 0) {
                        $modeloAtivo = $mod;
                        break;
                    }
                }
            }
        }

        // 3. Se o setor selecionado for inválido/indisponível para o aluno, pega o primeiro da lista filtrada
        if (!$modeloAtivo) {
            $modeloAtivo = !empty($modelos) ? reset($modelos) : null;
        }

        $modeloChave = $modeloAtivo['id'] ?? null;
        $setorDestino = [
            'nome' => $modeloAtivo['setor_nome'] ?? 'Setor Responsável',
            'email' => $modeloAtivo['email'] ?? 'protocolos.seabra@ifba.edu.br',
        ];

        return view('requerimentos.form', compact('modelos', 'modeloChave', 'modeloAtivo', 'setorDestino'));
    }

    //Método para mostrar requerimentos que já foram realizados pelo usuário;
    public function index(Request $request)
    {
        //Implementação da lógica de que o usuário logado só pode ver os seus próprios requerimentos;
        //Uso de chave estrangeira na tabela requerimentos;
        $query = auth()->user()->requerimentos();

        if ($request->filled('busca')) {
            $busca = $request->input('busca');
            $query->where(function($q) use ($busca) {
                $q->where('objetoDoRequerimento', 'LIKE', '%' . $busca . '%')
                  ->orWhere('numero_protocolo', 'LIKE', '%' . $busca . '%')
                  ->orWhere('status', 'LIKE', '%' . $busca . '%');
            });
        }
        if ($request->filled('objetoDoRequerimento')) {
            $query->where('objetoDoRequerimento', 'LIKE', '%' . $request->input('objetoDoRequerimento') . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', 'LIKE', '%' . $request->input('status') . '%');
        }
        if ($request->filled('numero_protocolo')) {
            $query->where('numero_protocolo', 'LIKE', '%' . $request->input('numero_protocolo') . '%');
        }

        $requerimentos = $query->get();
        return view('requerimentos.meusRequerimentos', compact('requerimentos'));
    }
    public function show(Requerimento $requerimento)
    {
        $donoId = $requerimento->usuario_id ?? $requerimento->user_id;
        if ((int) $donoId !== (int) auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }
        $requerimento->load(['usuario.endereco', 'assunto', 'historicos.usuario']);
        return view('requerimentos.show', compact('requerimento'));
    }
}
