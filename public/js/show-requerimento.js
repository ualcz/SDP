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


function toggleCampoNomeDocumento() {
    const solicitaSelect = document.getElementById('solicita_novo_documento');
    const boxNomeDocumento = document.getElementById('box-nome-documento');
    const inputNomeDocumento = document.getElementById('nome_documento_solicitado');

    if (solicitaSelect.value === '1') {
        boxNomeDocumento.style.display = 'block';
        inputNomeDocumento.setAttribute('required', 'required');
    } else {
        boxNomeDocumento.style.display = 'none';
        inputNomeDocumento.removeAttribute('required');
    }
}
