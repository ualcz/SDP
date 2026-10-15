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

function alternarObjetoOutro(ativo) {
    const campoOutro = document.querySelector('input[name="objeto_outro"]');
    if (!campoOutro) return;

    campoOutro.disabled = !ativo;
    campoOutro.style.backgroundColor = ativo ? '#fff' : '#f1f5f9';
    campoOutro.style.borderColor = ativo ? '#ccc' : '#cbd5e1';
    campoOutro.style.color = ativo ? '#111827' : '#64748b';
    campoOutro.style.cursor = ativo ? 'text' : 'not-allowed';

    const ajudaOutro = document.getElementById('objeto-outro-ajuda');
    if (ajudaOutro) ajudaOutro.style.display = ativo ? 'none' : 'flex';

    if (ativo) {
        mostrarDocumentosAssunto('outro');
    } else {
        campoOutro.value = '';
    }
}

function salvarRascunhoRequerimento(form) {
    const chave = form?.dataset.draftKey;
    if (!chave || form.dataset.suspendDraft === 'true') return;

    const campos = {};
    Array.from(form.elements).forEach(campo => {
        if (!campo.name || campo.name === '_token' || campo.disabled ||
            ['file', 'submit', 'button', 'reset', 'password'].includes(campo.type)) return;

        if (campo.type === 'radio') {
            if (campo.checked) campos[campo.name] = campo.value;
        } else if (campo.type === 'checkbox') {
            campos[campo.name] ??= [];
            if (campo.checked) campos[campo.name].push(campo.value);
        } else {
            campos[campo.name] = campo.value;
        }
    });

    const etapaAtiva = Array.from(form.querySelectorAll('.form-step'))
        .find(etapa => window.getComputedStyle(etapa).display !== 'none');

    try {
        sessionStorage.setItem(chave, JSON.stringify({
            campos,
            etapa: etapaAtiva?.dataset.step ?? '1',
        }));
    } catch (error) {
        // O formulário continua funcionando mesmo se o armazenamento estiver indisponível.
    }
}

function restaurarRascunhoRequerimento(form) {
    const chave = form.dataset.draftKey;
    if (!chave) return null;

    try {
        const rascunho = sessionStorage.getItem(chave);
        if (!rascunho) return null;

        const dados = JSON.parse(rascunho);
        Object.entries(dados.campos ?? {}).forEach(([nome, valor]) => {
            Array.from(form.elements)
                .filter(campo => campo.name === nome)
                .forEach(campo => {
                    if (campo.type === 'radio') {
                        campo.checked = campo.value === valor;
                    } else if (campo.type === 'checkbox') {
                        campo.checked = Array.isArray(valor) && valor.includes(campo.value);
                    } else if (campo.type !== 'file') {
                        campo.value = valor;
                    }
                });
        });
        return dados;
    } catch (error) {
        sessionStorage.removeItem(chave);
        return null;
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

    salvarRascunhoRequerimento(document.getElementById('formRequerimento'));
}

function mostrarDocumentosAssunto(index) {
    document.querySelectorAll('.bloco-documentos-assunto').forEach((el) => {
        const ativo = (index === 'outro') ? (el.id === 'bloco-doc-outro') : (el.id === `bloco-doc-${index}`);
        el.style.display = ativo ? 'block' : 'none';
        el.querySelectorAll('input[type="file"]').forEach(input => {
            input.disabled = !ativo;
        });
    });

    if (typeof window.verificarDocumentosObrigatorios === 'function') {
        window.verificarDocumentosObrigatorios();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form[action*="enviar-email"]');
    if (!form) return;

    form.dataset.suspendDraft = 'true';
    if (form.dataset.clearDraft === 'true') {
        sessionStorage.removeItem(form.dataset.draftKey);
    }

    const rascunho = form.dataset.restoreDraft === 'true' && form.dataset.clearDraft !== 'true'
        ? restaurarRascunhoRequerimento(form)
        : null;

    const radioSelecionado = form.querySelector('input[name="objetoDoRequerimento"]:checked');
    radioSelecionado?.dispatchEvent(new Event('change', { bubbles: true }));

    mudarPasso(1);
    form.dataset.suspendDraft = 'false';

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

    window.verificarDocumentosObrigatorios = verificarDocumentosObrigatorios;

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
    form.addEventListener('change', async (e) => {
        if (e.target.matches('input[type="file"]')) {
            await processarCompressaoInput(e.target);
            verificarDocumentosObrigatorios();
        }
        salvarRascunhoRequerimento(form);
    });
    form.addEventListener('input', () => salvarRascunhoRequerimento(form));
    window.addEventListener('pagehide', () => salvarRascunhoRequerimento(form));

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

/**
 * Comprime uma imagem no navegador utilizando Canvas API.
 * Reduz resolução e qualidade preservando total legibilidade de textos e assinaturas.
 */
async function comprimirImagem(file, maxDimension = 1920, quality = 0.78) {
    if (!file.type.match(/^image\/(jpeg|jpg|png|webp)$/i) || file.size < 350 * 1024) {
        return file;
    }

    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.onload = (event) => {
            const img = new Image();
            img.onload = () => {
                let width = img.width;
                let height = img.height;

                if (width > maxDimension || height > maxDimension) {
                    if (width > height) {
                        height = Math.round((height * maxDimension) / width);
                        width = maxDimension;
                    } else {
                        width = Math.round((width * maxDimension) / height);
                        height = maxDimension;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.fillStyle = '#FFFFFF';
                ctx.fillRect(0, 0, width, height);
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(
                    (blob) => {
                        if (!blob || blob.size >= file.size) {
                            resolve(file);
                            return;
                        }

                        const novoNome = file.name.replace(/\.[^.]+$/, '.jpg');
                        const compressedFile = new File([blob], novoNome, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });

                        compressedFile.tamanhoOriginal = file.size;
                        resolve(compressedFile);
                    },
                    'image/jpeg',
                    quality
                );
            };
            img.onerror = () => resolve(file);
            img.src = event.target.result;
        };
        reader.onerror = () => resolve(file);
        reader.readAsDataURL(file);
    });
}

/**
 * Intercepta a seleção de arquivos e comprime imagens pesadas antes do upload.
 */
async function processarCompressaoInput(input) {
    if (!input.files || input.files.length === 0 || input.dataset.compressing === 'true') {
        return;
    }

    const arquivosOriginais = Array.from(input.files);
    const temImagemPesada = arquivosOriginais.some(
        f => f.type.match(/^image\/(jpeg|jpg|png|webp)$/i) && f.size >= 350 * 1024
    );

    if (!temImagemPesada) {
        return;
    }

    input.dataset.compressing = 'true';
    const cardCampo = input.closest('.campo') || input.parentElement;
    const feedbackBox = cardCampo?.querySelector('.file-feedback-box');

    let statusAviso = cardCampo?.querySelector('.status-otimizando');
    if (!statusAviso) {
        statusAviso = document.createElement('div');
        statusAviso.className = 'status-otimizando';
        statusAviso.style.cssText = 'color: #0284c7; font-size: 12px; margin-top: 6px; font-weight: 500; display: flex; align-items: center; gap: 4px;';
        statusAviso.innerHTML = '⚡ <em>Otimizando imagens para envio rápido...</em>';
        if (feedbackBox) {
            feedbackBox.appendChild(statusAviso);
        } else if (cardCampo) {
            cardCampo.appendChild(statusAviso);
        }
    }

    try {
        const arquivosProcessados = await Promise.all(
            arquivosOriginais.map(file => comprimirImagem(file))
        );

        const dataTransfer = new DataTransfer();
        arquivosProcessados.forEach(f => dataTransfer.items.add(f));
        input.files = dataTransfer.files;

        const lista = cardCampo?.querySelector('ul');
        if (lista) {
            lista.innerHTML = '';
            Array.from(input.files).forEach(file => {
                const li = document.createElement('li');
                const tamanhoMB = (file.size / 1024 / 1024).toFixed(2);
                if (file.tamanhoOriginal) {
                    const originalMB = (file.tamanhoOriginal / 1024 / 1024).toFixed(2);
                    li.innerHTML = `<strong>${file.name}</strong> (${tamanhoMB} MB <span style="color:#059669; font-weight:600;">✓ Reduzido de ${originalMB} MB</span>)`;
                } else {
                    li.textContent = `${file.name} (${tamanhoMB} MB)`;
                }
                lista.appendChild(li);
            });
        }
    } catch (err) {
        console.warn('Erro ao otimizar imagem no cliente:', err);
    } finally {
        if (statusAviso) {
            statusAviso.remove();
        }
        input.dataset.compressing = 'false';
    }
}



