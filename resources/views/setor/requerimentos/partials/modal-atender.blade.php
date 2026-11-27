<x-modal id="modal-atender" title="Atender Requerimento">

    <form action="{{ route('setor.requerimentos.atualizarStatus', [$setor->id, $requerimento->id]) }}" method="POST" enctype="multipart/form-data" id="formAtender">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="Em Análise">

        <p>Ao clicar em Confirmar, o status do requerimento será atualizado para "Em Análise" e outras ações serão disponibilizadas.</p>
    </form>



            <x-slot:footer>
            <button type="button" data-modal-close class="btn-atualizar">
                Cancelar
            </button>

            <button
                type="submit"
                form="formAtender"
                class="btn-atualizar"
            >
               Confirmar
            </button>
        </x-slot:footer>

    @if(session('sucesso'))
        <div class="alert-sucesso">
            {{ session('sucesso') }}
        </div>
    @endif
        <x-loading-overlay
        form-id="formAtender"
        :mensagens="[
            'Atualizando status...',
            'Enviando email para o aluno...',
            'Atualizando sistema...',
            'Só mais um instante...'
        ]"
    />
</x-modal>


