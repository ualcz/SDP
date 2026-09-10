@extends('layouts.app')

@section('title', 'Novo Requerimento')
@section('tag', 'Aluno')

@section('content')

    <form method="GET" action="{{ route('requerimentos.aluno.meusRequerimentos') }}" class="flex flex-wrap gap-2 items-center mb-5">
        <div>
            <input type="text" name="objetoDoRequerimento" placeholder="Objeto do Requerimento" class="border border-gray-300 rounded p-2" />             
        </div>

        <div>
            <input type="text" name="status" placeholder="Status" class="border border-gray-300 rounded p-2" />
        </div>

        <div>
            <button type="submit" class="btn btn-primary flex items-center gap-1 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="28" height="28" viewBox="0 0 50 50">
                    <path fill="gray" d="M 21 3 C 11.601563 3 4 10.601563 4 20 C 4 29.398438 11.601563 37 21 37 C 24.355469 37 27.460938 36.015625 30.09375 34.34375 L 42.375 46.625 L 46.625 42.375 L 34.5 30.28125 C 36.679688 27.421875 38 23.878906 38 20 C 38 10.601563 30.398438 3 21 3 Z M 21 7 C 28.199219 7 34 12.800781 34 20 C 34 27.199219 28.199219 33 21 33 C 13.800781 33 8 27.199219 8 20 C 8 12.800781 13.800781 7 21 7 Z"></path>
                </svg>
            </button>
        </div>
    </form>

    <div class="overflow-x-auto rounded-lg shadow">
        <table class="table w-full text-sm text-gray-600">
    <thead class="bg-gray-100">
        <tr>
            <th class="py-3 px-6 text-center">Data</th>
            <th class="py-3 px-6 text-center">Objeto do requerimento</th>
            <th class="py-3 px-6 text-center">Motivo</th>
            <!--Campo situação: para indicar qual o status do andamento do requerimento(análise,concluído...)-->
            <th class="py-3 px-6 text-center">Status</th>
            <th class="py-3 px-6 text-center">Ações</th>
        </tr>
    </thead>

    <tbody class="bg-white dark:bg-gray-800">
        @forelse($requerimentos as $requerimento)
            <tr class="border-b" style="height:45px">
                <td class="py-3 px-6 text-center dark:text-white">
                    {{ isset($requerimento->created_at) && $requerimento->created_at ? $requerimento->created_at->format('d/m/Y H:i') : (isset($requerimento['created_at']) && $requerimento['created_at'] ? \Carbon\Carbon::parse($requerimento['created_at'])->format('d/m/Y H:i') : date('d/m/Y')) }}
                </td>
                <td class="py-3 px-6 text-center dark:text-white">
                    {{$requerimento['objetoDoRequerimento'] ?? $requerimento->objetoDoRequerimento}}
                </td>
                <td class="py-3 px-6 text-center dark:text-white">
                    {{$requerimento['motivo'] ?? $requerimento->motivo}}
                </td>
                <td class="py-3 px-6 text-center dark:text-white">
                    @if ($requerimento->status === 'Aprovado')
                        <span class="inline-block px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 border border-green-500 rounded-full dark:bg-green-400 border-green-600 text-green-900">
                            Aprovado
                        </span>
                    @elseif ($requerimento->status === 'Em análise' || $requerimento->status === 'Em Análise' || is_null($requerimento->status))
                        <span class="inline-block px-3 py-1 text-sm font-semibold text-yellow-800 bg-yellow-100 border border-yellow-500 rounded-full">
                            Em análise
                        </span>
                    @endif
                </td>
                <td class="py-3 px-6 text-center dark:text-white">
                    <a href="{{ route('requerimentos.gerar-comprovante', ['id' => $requerimento->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-4 rounded-lg">
                        Imprimir comprovante
                    </a>                 
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="py-3 px-6 text-center dark:text-white">
                    Nenhum requerimento encontrado
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection