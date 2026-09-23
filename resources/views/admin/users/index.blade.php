@extends('layouts.app')

@section('title', 'Dashboard - SDP')
@section('tag', 'Administração')

@section('content')
    <style>
        {!! file_get_contents(public_path('css/users-table.css')) !!}
    </style>


<section class="dash-section">
    <h3 class="dash-section-title">Usuários</h3>

        <div style="overflow-x: auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $user)
                        <tr>
                            <td>{{ $user->nome }}</td>
                            <td>{{ $user->email }}</td>
                            @if ($user->role == 'professor')
                                <td>Professor</td>
                            @endif
                            @if ($user->role == 'servidor')
                                <td>Servidor</td>
                            @endif
                            @if ($user->role == 'admin')
                                <td>Administrador</td>
                            @endif
                    @endforeach
                </tbody>
            </table>
        </div>
</section>
@endsection