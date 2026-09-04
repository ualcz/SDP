@extends('layouts.app')

@section('title', 'Novo Requerimento')
@section('tag', 'Aluno')

@section('content')
    <div class="overflow-x-auto rounded-lg shadow">
        <table class="table w-full text-sm text-gray-600">
    <thead class="bg-gray-100">
        <tr>
            <th class="py-3 px-6 text-center">Data</th>
            <th class="py-3 px-6 text-center">Objeto do requerimento</th>
            <th class="py-3 px-6 text-center">Motivo</th>
            <!--Campo situação: para indicar qual o status do andamento do requerimento(análise,concluído...)-->
            <th class="py-3 px-6 text-center">Situação</th>
        </tr>
    </thead>

    <tbody class="bg-white dark:bg-gray-800">
        @forelse($requerimentos as $requerimento)
            <tr class="border-b">
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
                    {{$requerimento['situação'] ?? $requerimento->situação ?? 'Em Análise'}}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="py-3 px-6 text-center dark:text-white">
                    Nenhum requerimento encontrado
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection