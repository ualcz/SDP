<x-mail::message>
@if($tipo === 'resposta')
# Resposta de Encaminhamento

O setor **{{ $setorOrigem->setor_sigla ?: $setorOrigem->setor_nome }}** respondeu ao encaminhamento do **Requerimento #{{ $requerimento->numero_protocolo ?? $requerimento->id }}** ({{ $requerimento->objetoDoRequerimento }}).

@if(!empty(trim($mensagem)))
<x-mail::panel>
**Resposta:**<br>
{!! nl2br(e($mensagem)) !!}
</x-mail::panel>
@endif

<x-mail::button :url="route('setor.requerimentos.show', [$setorDestino->id, $requerimento->id])" color="success">
Acessar no Painel
</x-mail::button>

@else
# Requerimento Encaminhado

O setor **{{ $setorOrigem->setor_sigla ?: $setorOrigem->setor_nome }}** encaminhou o **Requerimento #{{ $requerimento->numero_protocolo ?? $requerimento->id }}** ({{ $requerimento->objetoDoRequerimento }}) de **{{ $requerimento->usuario?->nome ?? 'Aluno' }}** para o seu setor.

@if(!empty(trim($mensagem)))
<x-mail::panel>
**Despacho:**<br>
{!! nl2br(e($mensagem)) !!}
</x-mail::panel>
@endif

<x-mail::button :url="route('setor.requerimentos.show', [$setorDestino->id, $requerimento->id])" color="success">
Acessar no Painel
</x-mail::button>
@endif

@if($tipo === 'encaminhamento')
*📎 O requerimento do aluno (PDF) e documentos foram anexados a este e-mail.*
@elseif(!empty($arquivos) && count($arquivos) > 0)
*📎 Possui documento(s) em anexo.*
@endif
</x-mail::message>
