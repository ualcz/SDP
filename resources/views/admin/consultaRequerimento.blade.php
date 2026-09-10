@extends('layouts.app')

@section('title', 'Painel Administrativo - SDP')
@section('tag', 'Administração')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow-lg max-w-lg mx-auto mt-8 dark:bg-gray-800">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Consultar requerimentos</h1>
    </div>

    <form action="#" method="get">            
    <div>
        <label for="numero_protocolo" class="block mb-2 text-md font-medium text-gray-700">Número do protocolo*</label>
        <input name="numero_protocolo" class="w-full px-3 py-2 border rounded-lg"></input><br>
    </div><br>
    <div>
        <label for="turma_codigo" class="block mb-2 text-md font-medium text-gray-700">Matrícula do usuário*</label>
        <input name="turma_codigo" class="w-full px-3 py-2 border rounded-lg"></input><br>
    </div>
    <br>
    <small class="text-red-700">Os campos marcados com * são obrigatórios.</small>
    <div class="flex justify-end">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg cursor-pointer">Consultar</button>
    </div>
    </form>
</div>
@endsection