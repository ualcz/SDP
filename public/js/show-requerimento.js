function toggleMensagemIndeferido() {
    const selectStatus = document.getElementById('status');
    const campoMensagem = document.getElementById('campo-mensagem');
    const textareaMensagem = document.getElementById('observacao');

    if (!selectStatus || !campoMensagem) return;

    if (selectStatus.value === 'Indeferido') {
        campoMensagem.style.display = 'block';
        if (textareaMensagem) textareaMensagem.setAttribute('required', 'required');
    } else {
        campoMensagem.style.display = 'none';
        if (textareaMensagem) textareaMensagem.removeAttribute('required');
    }
}

// Suporte e transição da sanfona (accordion de informações do aluno)
document.addEventListener('DOMContentLoaded', function () {
    const accordions = document.querySelectorAll('.info-aluno-accordion');
    accordions.forEach(function (acc) {
        const header = acc.querySelector('.accordion-header');
        if (header) {
            header.addEventListener('click', function () {
                setTimeout(function () {
                    const seta = acc.querySelector('.icone-seta');
                    if (seta) {
                        seta.style.transform = acc.open ? 'rotate(180deg)' : 'rotate(0deg)';
                    }
                }, 20);
            });
        }
    });
});

function toggleCampoNomeDocumento() {
    const solicitaSelect = document.getElementById('solicita_novo_documento');
    const boxNomeDocumento = document.getElementById('box-nome-documento');
    const inputNomeDocumento = document.getElementById('nome_documento_solicitado');

    if (solicitaSelect && boxNomeDocumento) {
        if (solicitaSelect.value === '1') {
            boxNomeDocumento.style.display = 'block';
            if (inputNomeDocumento) inputNomeDocumento.setAttribute('required', 'required');
        } else {
            boxNomeDocumento.style.display = 'none';
            if (inputNomeDocumento) inputNomeDocumento.removeAttribute('required');
        }
    }
}
