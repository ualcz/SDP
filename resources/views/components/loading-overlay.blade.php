@props([
    'formId',
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
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    text-align: center;
    max-width: 320px;
    width: 90%;
}

.spinner {
    width: 45px;
    height: 45px;
    border: 4px solid #e2e8f0;
    border-top: 4px solid #2563eb; /* Cor do spinner (Azul) */
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 15px auto;
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
}

</style>

<div id="loadingOverlay" class="loading-overlay" style="display: none;" role="status" aria-hidden="true">
    <div class="loading-box">
        <div class="spinner"></div>
        <p id="loadingText" class="loading-text">{{ $mensagens[0] }}</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const formId = @json($formId);
        const mensagens = @json($mensagens);

        const form = document.getElementById(formId);
        const loadingOverlay = document.getElementById('loadingOverlay');
        const loadingText = document.getElementById('loadingText');

        if (form && loadingOverlay && loadingText) {
            form.addEventListener('submit', function () {
                if (form.checkValidity()) {
                    loadingOverlay.style.display = 'flex';
                    loadingOverlay.setAttribute('aria-hidden', 'false');

                    const btnSubmit = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (btnSubmit) {
                        btnSubmit.disabled = true;
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
                }
            });
        }
    });
</script>
