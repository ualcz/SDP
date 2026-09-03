@extends('layouts.app')

@section('title', 'Novo Requerimento')
@section('tag', 'Aluno')

@section('content')
<style>
    form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    label {
        font-weight: bold;
    }

    input, textarea, select {
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
    }


</style>
<h1 class="text-2xl font-bold mb-4 text-center">Novo Requerimento</h1>
<form action="">
    @csrf
    <div>
        <label for="nome">Nome Completo</label>
        <input type="text" id="nome" name="nome" required value="{{ auth()->user()->nome }}">
    </div>
    <div>
        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required value="{{ auth()->user()->endereco }}">
    </div>


</form>
@endsection
