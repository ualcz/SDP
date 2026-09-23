 function toggleMensagemIndeferido() {
        const selectStatus = document.getElementById('status');
        const campoMensagem = document.getElementById('campo-mensagem');
        const textareaMensagem = document.getElementById('observacao');

        if (selectStatus.value === 'Indeferido') {
            campoMensagem.style.display = 'block';
            textareaMensagem.setAttribute('required', 'required');
        } else {
            campoMensagem.style.display = 'none';
            textareaMensagem.removeAttribute('required');
        }
    }
