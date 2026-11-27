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

// =====================================================
// TROCA DINÂMICA DE SETOR (sem reload de página)
// =====================================================

let setorAtualId = null; // ID do setor atualmente carregado

function selecionarSetor(setorId) {
    const setores = window.SDP_SETORES || [];
    const setor = setores.find(s => String(s.id) === String(setorId));
    if (!setor) return;

    setorAtualId = setorId;

    // 1. Destacar card selecionado
    document.querySelectorAll('.cardapio-card').forEach(card => {
        card.classList.remove('cardapio-card-ativo');
        const btn = card.querySelector('.cardapio-btn');
        if (btn) {
            btn.classList.remove('active');
            btn.textContent = 'Acessar Formulário →';
        }
    });
    const cardAtivo = document.getElementById('card-setor-' + setorId);
    if (cardAtivo) {
        cardAtivo.classList.add('cardapio-card-ativo');
        const btn = cardAtivo.querySelector('.cardapio-btn');
        if (btn) {
            btn.classList.add('active');
            btn.textContent = 'Setor Selecionado ✓';
        }
    }

    // 2. Atualizar setor_id e setor nos hidden inputs do form
    const inputSetorId = document.querySelector('input[name="setor_id"]');
    const inputSetor   = document.querySelector('input[name="setor"]');
    if (inputSetorId) inputSetorId.value = setorId;
    if (inputSetor)   inputSetor.value   = setorId;

    // 3. Atualizar título e nome nas etapas 2 e 3
    const titulo = setor.titulo || 'Requerimento Geral';
    const nomeSetor = setor.setor_nome || 'Setor Responsável';
    ['etapa2', 'etapa3'].forEach(sufixo => {
        const elTitulo = document.getElementById('titulo-setor-' + sufixo);
        const elNome   = document.getElementById('nome-setor-' + sufixo);
        if (elTitulo) elTitulo.textContent = titulo;
        if (elNome)   elNome.textContent   = nomeSetor;
    });

    // 4. Reconstruir lista de objetos (etapa 2)
    const containerObjetos = document.getElementById('container-objetos');
    if (containerObjetos) {
        containerObjetos.innerHTML = '';
        const assuntos = setor.assuntos_detalhes || [];
        assuntos.forEach((assunto, idx) => {
            const descricao = assunto.descricao || assunto.titulo || '';
            const obs       = assunto.observacao || '';
            const linkNorma = assunto.link_norma || '';

            const label = document.createElement('label');
            label.className = 'opcao-objeto-card';

            let normaBtn = '';
            if (linkNorma) {
                normaBtn = `<a href="${linkNorma}" target="_blank" rel="noopener noreferrer"
                               onclick="event.stopPropagation()" class="opcao-norma-btn"
                               title="Consultar norma" aria-label="Consultar norma de ${descricao}">?</a>`;
            }

            let obsHtml = '';
            if (obs) {
                obsHtml = `<span class="opcao-observacao">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    ${obs}
                </span>`;
            }

            label.innerHTML = `
                <span class="opcao-header">
                    <input type="radio" class="opcao-radio" name="objetoDoRequerimento" value="${descricao}"
                           onchange="alternarObjetoOutro(false); mostrarDocumentosAssunto(${idx});
                                     document.querySelectorAll('.opcao-objeto-card').forEach(el => el.classList.remove('opcao-selecionada'));
                                     this.closest('.opcao-objeto-card').classList.add('opcao-selecionada');">
                    <span class="opcao-titulo">${descricao}</span>
                    ${normaBtn}
                </span>
                ${obsHtml}
            `;
            containerObjetos.appendChild(label);
        });
    }

    // 5. Reconstruir blocos de documentos (etapa 3)
    const containerDocs = document.getElementById('container-documentos');
    if (containerDocs) {
        containerDocs.innerHTML = '';
        const assuntos  = setor.assuntos_detalhes || [];
        const alunoMenor = window.SDP_MENOR || false;

        assuntos.forEach((assunto, idx) => {
            const docs = assunto.documentos_obrigatorios || [];
            const div  = document.createElement('div');
            div.className = 'bloco-documentos-assunto';
            div.id        = 'bloco-doc-' + idx;
            div.style.display = idx === 0 ? '' : 'none';

            let docsHtml = '';
            if (docs.length > 0) {
                docsHtml += `<h3 style="font-size:1.25rem;text-align:center;margin-bottom:12px;">Documentos Obrigatórios</h3>`;
                docs.forEach(doc => {
                    const obrigatorioLabel = doc.obrigatorio
                        ? '<span style="color:#dc2626;font-weight:normal;">(Obrigatório)</span>'
                        : '<span style="color:#6b7280;font-weight:normal;">(Opcional)</span>';
                    const descricaoBox = doc.descricao?.trim()
                        ? `<div style="background:#fffbeb;color:#78350f;padding:10px 14px;margin:8px 0 12px;font-size:15px;font-weight:600;line-height:1.5;border:1px solid #fcd34d;">${doc.descricao}</div>`
                        : '';
                    const tipos = (doc.tipos_aceitos || '.pdf,.doc,.docx,.png,.jpg,.jpeg')
                        .replace(/\s/g, '').replace(/\./g, '').split(',').map(t => '.' + t).join(',');
                    const docId = doc.id ?? idx;
                    const feedbackId = `feedback-documento-${idx}-${docId}`;
                    const exigeDocumentoResponsavel = alunoMenor && doc.exige_documentos_responsaveis;
                    docsHtml += `
                        <div class="campo documento-configurado ${doc.obrigatorio ? 'documento-obrigatorio' : ''}">
                            <label style="display:block;font-weight:bold;margin-bottom:4px;font-size:14px;">
                                ${doc.nome} ${obrigatorioLabel}
                            </label>
                            ${descricaoBox}
                            <small style="display:block;color:#666;margin-bottom:8px;font-size:12px;">
                                ${doc.obrigatorio ? 'Campo obrigatório.' : 'Documento opcional. Selecione um arquivo.'}
                            </small>
                            <label class="custom-file-upload">
                                <input type="file" class="doc-file-input"
                                       name="documentos[${docId}]"
                                       accept="${tipos}"
                                       data-obrigatorio="${doc.obrigatorio ? 'true' : 'false'}"
                                       data-feedback-id="${feedbackId}">
                                <div class="file-upload-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 8px;display:block;">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="17 8 12 3 7 8"/>
                                        <line x1="12" y1="3" x2="12" y2="15"/>
                                    </svg>
                                    <strong>Clique aqui</strong> para selecionar os arquivos
                                </div>
                            </label>
                            <div id="${feedbackId}" class="file-feedback-box">
                                <strong>Arquivos selecionados:</strong>
                                <ul></ul>
                            </div>
                            ${exigeDocumentoResponsavel ? `
                                <label style="display:block;font-weight:bold;margin:16px 0 4px;font-size:14px;">
                                    ${doc.nome} de um responsável legal
                                    <span style="color:#dc2626;font-weight:normal;">(Obrigatório)</span>
                                </label>
                                <small style="display:block;color:#666;margin-bottom:8px;font-size:12px;">
                                    Anexe a cópia do documento de um responsável.
                                </small>
                                <label class="custom-file-upload">
                                    <input type="file" class="doc-file-input"
                                           name="documentos_responsavel[${docId}]"
                                           accept="${tipos}"
                                           data-obrigatorio="true"
                                           data-feedback-id="${feedbackId}-responsavel">
                                    <div class="file-upload-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 8px;display:block;">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="17 8 12 3 7 8"/>
                                            <line x1="12" y1="3" x2="12" y2="15"/>
                                        </svg>
                                        <strong>Clique aqui</strong> para selecionar os arquivos
                                    </div>
                                </label>
                                <div id="${feedbackId}-responsavel" class="file-feedback-box">
                                    <strong>Arquivos selecionados:</strong>
                                    <ul></ul>
                                </div>
                            ` : ''}
                        </div>`;
                });
            } else {
                docsHtml = `<p style="color:#666;font-size:14px;margin-bottom:12px;">Este assunto não possui documentos obrigatórios.</p>`;
            }

            div.innerHTML = `
                <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:6px;padding:10px 14px;margin-bottom:16px;">
                    <span style="font-size:12px;color:#15803d;text-transform:uppercase;font-weight:600;display:block;">Assunto Selecionado:</span>
                    <strong style="font-size:16px;color:#047857;">${assunto.descricao}</strong>
                </div>
                ${docsHtml}
            `;
            containerDocs.appendChild(div);
        });

        // Reativar verificação de documentos obrigatórios
        if (typeof window.verificarDocumentosObrigatorios === 'function') {
            // Re-observar os novos blocos
            const observer = new MutationObserver(() => window.verificarDocumentosObrigatorios());
            containerDocs.querySelectorAll('.bloco-documentos-assunto').forEach(bloco => {
                observer.observe(bloco, { attributes: true, attributeFilter: ['style'] });
            });
            window.verificarDocumentosObrigatorios();
        }
    }

    // 6. Mostrar botão Próximo
    const btnProximo = document.getElementById('btn-proximo-setor');
    if (btnProximo) btnProximo.style.display = 'block';

    // 7. Limpar seleção de objeto anterior
    document.querySelectorAll('input[name="objetoDoRequerimento"]').forEach(r => r.checked = false);
    mostrarDocumentosAssunto(0);
}

function mudarPasso(passo) {
    // Etapa 1 → 2: validar se um setor foi selecionado
    if (passo === 2 && !setorAtualId) {
        const inputSetorId = document.querySelector('input[name="setor_id"]');
        if (!inputSetorId || !inputSetorId.value) {
            alert('Por favor, selecione um Setor antes de prosseguir.');
            return;
        }
    }

    if (passo === 3) {
        const radioChecked = document.querySelector('input[name="objetoDoRequerimento"]:checked');
        if (!radioChecked) {
            alert('Por favor, selecione um Objeto do Requerimento antes de prosseguir.');
            return;
        }

        const motivo = document.querySelector('textarea[name="motivo"]');
        const erroMotivo = document.getElementById('erro-motivo');
        if (!motivo || !motivo.value.trim()) {
            if (erroMotivo) erroMotivo.style.display = 'block';
            if (motivo) {
                motivo.style.borderColor = '#dc2626';
                motivo.focus();
                motivo.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            alert('Por favor, preencha a Justificativa / Motivo antes de prosseguir.');
            return;
        } else {
            if (erroMotivo) erroMotivo.style.display = 'none';
            if (motivo) motivo.style.borderColor = '';
        }
    }

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

    const radioSelecionado = form.querySelector('input[name="objetoDoRequerimento"]:checked');
    radioSelecionado?.dispatchEvent(new Event('change', { bubbles: true }));

    mudarPasso(1);

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

        const todoPreenchido = Array.from(inputs).every(input =>
            input.files && input.files.length > 0 && input.dataset.compressing !== 'true'
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
            const input = e.target;
            const processamento = processarCompressaoInput(input);
            verificarDocumentosObrigatorios();
            await processamento;
            const feedbackBox = document.getElementById(input.dataset.feedbackId);
            if (feedbackBox) {
                const lista = feedbackBox.querySelector('ul');
                lista.innerHTML = '';
                Array.from(input.files || []).forEach(file => {
                    const item = document.createElement('li');
                    item.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    lista.appendChild(item);
                });
                feedbackBox.style.display = input.files && input.files.length > 0 ? 'block' : 'none';
            }
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

    // Limpa aviso de erro da justificativa ao digitar
    const campoMotivo = form.querySelector('textarea[name="motivo"]');
    if (campoMotivo) {
        campoMotivo.addEventListener('input', () => {
            const erroMotivo = document.getElementById('erro-motivo');
            if (campoMotivo.value.trim()) {
                campoMotivo.style.borderColor = '';
                if (erroMotivo) erroMotivo.style.display = 'none';
            }
        });
    }

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
