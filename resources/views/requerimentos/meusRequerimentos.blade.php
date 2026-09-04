@extends('layouts.app')

@section('title', 'Novo Requerimento')
@section('tag', 'Aluno')

@section('content')
    <div class="overflow-x-auto rounded-lg shadow">
        <table class="table w-full text-sm text-gray-600">
    <thead class="bg-gray-100">
        <tr>
            <th class="py-3 px-6 text-center w-[20px]">Objeto do requerimento</th>
            <th class="py-3 px-6 text-center w-[20px]">Motivo</th>
            <!--Campo situação: para indicar qual o status do andamento do requerimento(análise,concluído...)-->
            <th class="py-3 px-6 text-center w-[20px]">Situação</th>
        </tr>
    </thead>

    <tbody class="bg-white dark:bg-gray-800">
        @forelse($requerimentos as $requerimento)
            <tr class="border-b">
                <td class="py-3 px-6 text-center dark:text-white">
                    {{$requerimento['objetoDoRequerimento']}}
                </td>
                <td class="py-3 px-6 text-center dark:text-white">
                    {{$requerimento['motivo']}}
                </td>
                <td class="py-3 px-6 text-center dark:text-white">
                    {{$requerimento['situação']}}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="py-3 px-6 text-center dark:text-white">
                    Nenhum requerimento encontrado
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection