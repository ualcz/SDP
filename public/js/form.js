function verInfoAluno() {
    const divInfoAluno = document.querySelector('.info-aluno');
    divInfoAluno.classList.toggle('active');

    const imgBotao = document.querySelector('#ver-info-aluno img');

    if (divInfoAluno.classList.contains('active')) {
        imgBotao.src = "/img/icons/chevron-up.svg";
    } else {
        imgBotao.src = "/img/icons/chevron-down.svg";
    }
}

function mudarPasso(passo) {
    document.querySelectorAll('.form-step').forEach(el => {
        el.style.display = 'none';
    });

    const etapaAlvo = document.querySelector(`.form-step[data-step="${passo}"]`);
    if (etapaAlvo) {
        etapaAlvo.style.display = 'block';
        etapaAlvo.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function mostrarDocumentosAssunto(index) {
    document.querySelectorAll('.bloco-documentos-assunto').forEach((el, i) => {
        if (index === 'outro') {
            el.style.display = (el.id === 'bloco-doc-outro') ? 'block' : 'none';
        } else {
            el.style.display = (el.id === `bloco-doc-${index}`) ? 'block' : 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form[action*="enviar-email"]');
    if (!form) return;

    const btnEnviar = form.querySelector('button[type="submit"]');

    // Verifica se todos os documentos obrigatórios do bloco visível estão preenchidos
    function verificarDocumentosObrigatorios() {
        const blocoAtivo = Array.from(
            document.querySelectorAll('.bloco-documentos-assunto')
        ).find(b => b.style.display !== 'none' && window.getComputedStyle(b).display !== 'none');

        if (!blocoAtivo) {
            habilitarBotao(true);
            return;
        }

        const inputs = blocoAtivo.querySelectorAll('input[type="file"][data-obrigatorio="true"]');

        if (inputs.length === 0) {
            habilitarBotao(true);
            return;
        }

        const todoPreenchido = Array.from(inputs).every(
            input => input.files && input.files.length > 0
        );

        habilitarBotao(todoPreenchido);

        // Atualiza o estado visual de cada campo individualmente
        inputs.forEach(input => {
            const card = input.closest('.campo') || input.parentElement;
            if (input.files && input.files.length > 0) {
                input.style.outline = '2px solid #10b981';
                input.style.borderRadius = '4px';
            } else {
                input.style.outline = '';
            }
        });
    }

    function habilitarBotao(habilitar) {
        if (!btnEnviar) return;
        btnEnviar.disabled = !habilitar;

        if (habilitar) {
            btnEnviar.style.opacity = '1';
            btnEnviar.style.cursor = 'pointer';
            btnEnviar.title = '';
        } else {
            btnEnviar.style.opacity = '0.45';
            btnEnviar.style.cursor = 'not-allowed';
            btnEnviar.title = 'Anexe todos os documentos obrigatórios para enviar';
        }
    }

    // Reage a qualquer mudança de arquivo no formulário
    form.addEventListener('change', (e) => {
        if (e.target.matches('input[type="file"]')) {
            verificarDocumentosObrigatorios();
        }
    });

    // Reage à mudança de assunto (troca de bloco visível)
    const observer = new MutationObserver(() => {
        verificarDocumentosObrigatorios();
    });

    document.querySelectorAll('.bloco-documentos-assunto').forEach(bloco => {
        observer.observe(bloco, { attributes: true, attributeFilter: ['style'] });
    });

    // Estado inicial
    verificarDocumentosObrigatorios();
});
