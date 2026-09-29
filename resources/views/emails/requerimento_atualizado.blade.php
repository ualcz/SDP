<x-mail::message>
# Atualização no Requerimento #{{ $requerimento->numero_protocolo }}

Olá,

Houve uma nova atualização no requerimento referente a **{{ $requerimento->objetoDoRequerimento }}**.

**Status Atual:** {{ $requerimento->status }}
**Enviado por:** {{ $autor === 'setor' ? 'Setor Responsável' : 'Aluno' }}

@if($requerimento->status === 'Indeferido' && $autor === 'setor')
Seu requerimento foi indeferido. Acesse o sistema para corrigir e enviar novamente o documento solicitado.

<x-mail::button :url="route('requerimentos.aluno.visualizar', $requerimento->id)">
Corrigir e reenviar documento
</x-mail::button>
@endif

---

### Mensagem / Observação:
> {!! nl2br(e($mensagem)) !!}

@if($autor === 'aluno')
Anexos adicionais ou atualizados foram disponibilizados pelo aluno nesta mensagem.
@endif

{{-- <x-mail::button :url="config('app.url')">
Acessar Sistema
</x-mail::button> --}}

Atenciosamente,<br>
{{ config('app.name') }}
</x-mail::message>
