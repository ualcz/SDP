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
    // Oculta todas as etapas
    document.querySelectorAll('.form-step').forEach(el => {
        el.style.display = 'none';
    });

    // Exibe a etapa solicitada
    const etapaAlvo = document.querySelector(`.form-step[data-step="${passo}"]`);
    if (etapaAlvo) {
        etapaAlvo.style.display = 'block';
        etapaAlvo.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Atualiza o indicador de etapas
    document.querySelectorAll('.step-item').forEach(item => {
        item.classList.remove('active');
    });
    const indicadorAtivo = document.getElementById(`step-ind-${passo}`);
    if (indicadorAtivo) {
        indicadorAtivo.classList.add('active');
    }
}
