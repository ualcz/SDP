{{-- Modal de Pré-visualização de Documentos --}}
<div id="modalPreviewDocumento" class="modal-preview-overlay" style="display: none;" onclick="fecharPreviewDocumento(event)">
    <div class="modal-preview-container" onclick="event.stopPropagation()">
        {{-- Cabeçalho do Modal --}}
        <div class="modal-preview-header">
            <div class="modal-preview-title-box">
                <svg width="20" height="20" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div>
                    <h3 id="modalPreviewTitulo" class="modal-preview-titulo">Visualizar Documento</h3>
                    <small id="modalPreviewSubtitulo" class="modal-preview-subtitulo">arquivo.pdf</small>
                </div>
            </div>

            <div class="modal-preview-actions">
                <a id="modalPreviewBtnNovaAba" href="#" target="_blank" class="btn-modal-acao" title="Abrir em nova aba">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Nova Aba</span>
                </a>

                <a id="modalPreviewBtnDownload" href="#" class="btn-modal-acao btn-modal-download" title="Baixar arquivo">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Baixar</span>
                </a>

                <button type="button" class="btn-modal-fechar" onclick="fecharPreviewModal()" title="Fechar (Esc)">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Corpo do Modal --}}
        <div class="modal-preview-body" id="modalPreviewBody">
            <div id="modalPreviewLoading" class="modal-preview-loading" style="display: none;">
                <div class="spinner-preview"></div>
                <span>Carregando documento...</span>
            </div>

            {{-- Container Iframe para PDF --}}
            <iframe id="modalPreviewIframe" class="modal-preview-iframe" style="display: none;" src="" title="Visualização de Documento"></iframe>

            {{-- Container Imagem --}}
            <div id="modalPreviewImgContainer" class="modal-preview-img-container" style="display: none;">
                <img id="modalPreviewImg" class="modal-preview-img" src="" alt="Documento Anexo">
            </div>

            {{-- Container Fallback (outros formatos) --}}
            <div id="modalPreviewFallback" class="modal-preview-fallback" style="display: none;">
                <svg width="48" height="48" fill="none" stroke="#6b7280" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <h4>Pré-visualização não suportada no navegador</h4>
                <p>Este formato de arquivo não pode ser visualizado diretamente. Baixe o documento para abri-lo em seu dispositivo.</p>
                <a id="modalPreviewFallbackBtn" href="#" class="btn-atualizar" style="margin-top: 12px; text-decoration: none;">
                    Baixar Arquivo
                </a>
            </div>
        </div>
    </div>
</div>
