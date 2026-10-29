@props([
    'formId',
    'ajax' => false,
    'redirectUrl' => null,
    'successMessage' => 'Requerimento enviado com sucesso!',
    'mensagens' => [
        'Enviando requisição...',
        'Validando informações...',
        'Processando anexos...',
        'Gerando dados do sistema...',
        'Finalizando solicitação...',
        'Quase lá! Só mais um instante...'
    ]
])

<style>
/* Estilos do Overlay de Carregamento */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(15, 23, 42, 0.65); /* Fundo escuro semitransparente */
    backdrop-filter: blur(4px); /* Efeito de desfoque */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999; /* Fica acima de qualquer outro elemento */
}

.loading-box {
    background: #ffffff;
    padding: 32px 28px;
    border-radius: 14px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    text-align: center;
    max-width: 340px;
    width: 90%;
    transition: all 0.3s ease;
}

.spinner {
    width: 48px;
    height: 48px;
    border: 4px solid #e2e8f0;
    border-top: 4px solid #2563eb; /* Cor do spinner (Azul) */
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 16px auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-text {
    font-size: 0.95rem;
    font-weight: 600;
    color: #334155;
    margin: 0;
    transition: opacity 0.2s ease, color 0.3s ease;
}

.loading-subtext {
    font-size: 0.85rem;
    color: #64748b;
    margin: 6px 0 0 0;
    font-weight: 400;
}

/* Ícone animado do certo (sucesso) */
.success-checkmark-wrapper {
    width: 60px;
    height: 60px;
    margin: 0 auto 16px auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

.success-checkmark {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    display: block;
    stroke-width: 3;
    stroke: #10b981;
    stroke-miterlimit: 10;
    box-shadow: inset 0px 0px 0px #10b981;
    animation: success-fill 0.4s ease-in-out 0.4s forwards, success-scale 0.3s ease-in-out 0.8s both;
}

.checkmark-circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 3;
    stroke-miterlimit: 10;
    stroke: #10b981;
    fill: none;
    animation: checkmark-stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.checkmark-check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    stroke-width: 3.5;
    stroke: #ffffff;
    animation: checkmark-stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
}

@keyframes checkmark-stroke {
    100% {
        stroke-dashoffset: 0;
    }
}

@keyframes success-scale {
    0%, 100% {
        transform: none;
    }
    50% {
        transform: scale3d(1.08, 1.08, 1);
    }
}

@keyframes success-fill {
    100% {
        box-shadow: inset 0px 0px 0px 30px #10b981;
    }
}
</style>

<div id="loadingOverlay" class="loading-overlay" style="display: none;" role="status" aria-hidden="true">
    <div class="loading-box">
        <div id="loadingSpinner" class="spinner"></div>
        <div id="loadingSuccessIcon" class="success-checkmark-wrapper" style="display: none;">
            <svg class="success-checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>
        <p id="loadingText" class="loading-text">{{ $mensagens[0] }}</p>
        <p id="loadingSubtext" class="loading-subtext" style="display: none;"></p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const formId = @json($formId);
        const mensagens = @json($mensagens);
        const isAjax = @json($ajax);
        const redirectUrl = @json($redirectUrl);
        const successMessage = @json($successMessage);

        const form = document.getElementById(formId);
        const loadingOverlay = document.getElementById('loadingOverlay');
        const loadingText = document.getElementById('loadingText');
        const loadingSubtext = document.getElementById('loadingSubtext');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const loadingSuccessIcon = document.getElementById('loadingSuccessIcon');

        if (!form || !loadingOverlay || !loadingText) return;

        if (isAjax) {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                // Validação de justificativa / motivo
                const motivo = form.querySelector('textarea[name="motivo"]');
                if (motivo && !motivo.value.trim()) {
                    alert('Por favor, preencha a Justificativa / Motivo antes de enviar.');
                    if (typeof mudarPasso === 'function') {
                        mudarPasso(1);
                    }
                    motivo.style.borderColor = '#dc2626';
                    motivo.focus();
                    motivo.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }

                if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                if (loadingSpinner) loadingSpinner.style.display = 'block';
                if (loadingSuccessIcon) loadingSuccessIcon.style.display = 'none';
                if (loadingSubtext) loadingSubtext.style.display = 'none';
                loadingText.style.color = '#334155';
                loadingText.textContent = mensagens[0];

                loadingOverlay.style.display = 'flex';
                loadingOverlay.setAttribute('aria-hidden', 'false');

                const btnSubmit = form.querySelector('button[type="submit"], input[type="submit"]');
                if (btnSubmit) {
                    btnSubmit.style.pointerEvents = 'none';
                    btnSubmit.disabled = true;
                }

                let index = 0;
                const intervalId = setInterval(() => {
                    index = (index + 1) % mensagens.length;
                    loadingText.style.opacity = '0';
                    setTimeout(() => {
                        loadingText.textContent = mensagens[index];
                        loadingText.style.opacity = '1';
                    }, 200);
                }, 2000);

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: form.method || 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    clearInterval(intervalId);

                    if (response.ok) {
                        const data = await response.json();

                        // Mostra o ícone de certo (sucesso)
                        if (loadingSpinner) loadingSpinner.style.display = 'none';
                        if (loadingSuccessIcon) loadingSuccessIcon.style.display = 'flex';
                        if (loadingSubtext) {
                            loadingSubtext.textContent = 'Redirecionando para Meus Requerimentos...';
                            loadingSubtext.style.display = 'block';
                        }
                        loadingText.textContent = data.message || successMessage;
                        loadingText.style.color = '#065f46';
                        loadingText.style.opacity = '1';

                        const destino = data.redirect || redirectUrl || '{{ route("requerimentos.aluno.meusRequerimentos") }}';

                        setTimeout(() => {
                            window.location.href = destino;
                        }, 1600);
                    } else {
                        clearInterval(intervalId);
                        loadingOverlay.style.display = 'none';
                        loadingOverlay.setAttribute('aria-hidden', 'true');
                        if (btnSubmit) {
                            btnSubmit.style.pointerEvents = '';
                            btnSubmit.disabled = false;
                        }

                        let msg = 'Ocorreu um erro ao enviar o requerimento. Por favor, tente novamente.';
                        try {
                            const errData = await response.json();
                            if (errData.errors) {
                                const erros = Object.values(errData.errors).flat();
                                msg = erros.join('\n');
                            } else if (errData.message) {
                                msg = errData.message;
                            }
                        } catch (errJson) {}
                        alert(msg);
                    }
                } catch (networkError) {
                    clearInterval(intervalId);
                    loadingOverlay.style.display = 'none';
                    loadingOverlay.setAttribute('aria-hidden', 'true');
                    if (btnSubmit) {
                        btnSubmit.style.pointerEvents = '';
                        btnSubmit.disabled = false;
                    }
                    alert('Erro de conexão ou falha ao enviar o requerimento. Por favor, tente novamente.');
                    console.error(networkError);
                }
            });
        } else {
            // Modo padrão síncrono
            form.addEventListener('submit', function (e) {
                if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                    return;
                }

                loadingOverlay.style.display = 'flex';
                loadingOverlay.setAttribute('aria-hidden', 'false');

                const btnSubmit = form.querySelector('button[type="submit"], input[type="submit"]');
                if (btnSubmit) {
                    btnSubmit.style.pointerEvents = 'none';
                    setTimeout(() => {
                        btnSubmit.disabled = true;
                    }, 50);
                }

                let index = 0;
                setInterval(() => {
                    index = (index + 1) % mensagens.length;
                    loadingText.style.opacity = '0';
                    setTimeout(() => {
                        loadingText.textContent = mensagens[index];
                        loadingText.style.opacity = '1';
                    }, 200);
                }, 2000);
            });
        }
    });
</script>
