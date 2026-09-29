@extends('layouts.app')

@section('title', 'Dashboard - SDP')
@section('tag', 'Administração')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-800 text-center">Histórico de Alterações</h2>

    <div class="relative wrap overflow-hidden p-10 h-full">
        <!-- Verificação: somente aparece a linha da timeline de alterações se tiver registros -->
        @if($historicos->isNotEmpty())
            <div class="border-2-2 absolute border-opacity-20 border-gray-700 h-full border" style="left: 50%"></div>
        @endif

    @forelse($historicos as $index => $h)
        <div class="mb-8 flex justify-between items-center w-full right-timeline">
            <div class="w-5/12"></div>
            <div class="z-20 flex items-center order-1 bg-blue-600 shadow-xl w-8 h-8 rounded-full">
                <h1 class="mx-auto font-semibold text-lg text-white">•</h1>
            </div>
            <div class="order-1 bg-white rounded-lg shadow-md w-5/12 px-6 py-4 border border-gray-100">
                <span class="mb-3 text-xs text-gray-500 font-medium">
                    {{ $h->created_at->format('d/m/Y H:i') }}
                </span>
                <h4 class="mb-1 font-bold text-gray-800 text-lg">
                    {{ $h->description }}
                </h4>
                <p class="text-sm leading-snug text-gray-600 text-opacity-100">
                    @if($h->properties->isNotEmpty())
                        Status definido para <strong>{{ $h->properties->get('status') }}</strong>
                        no setor <strong>{{ $h->properties->get('setor') }}</strong>.
                    @endif
                </p>
                <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded">
                    Por: {{ $h->causer?->nome ?? 'Sistema' }}
                </span>
            </div>
        </div>
        @empty
        <div class="text-center text-gray-500 font-medium mt-10">
            Nenhuma alteração registrada.
        </div>
    @endforelse
        </div>
    </div>
</div>
@endsection
