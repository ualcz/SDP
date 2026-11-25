
class Modal {
    constructor(element) {
        this.element = element;
    }

    open() {
        this.element.classList.add('active');
        this.element.setAttribute('aria-hidden', 'false');

        document.body.classList.add('modal-open');

        // Guarda o elemento que estava em foco
        this.previousFocus = document.activeElement;

        // Direciona o foco para o modal
        const focusable = this.element.querySelector(
            'button, input, select, textarea, a[href], [tabindex]:not([tabindex="-1"])'
        );

        (focusable || this.element.querySelector('.modal-container'))
            ?.focus();
    }

    close() {
        this.element.classList.remove('active');
        this.element.setAttribute('aria-hidden', 'true');

        // Só libera o scroll quando não houver outro modal aberto
        if (!document.querySelector('.modal.active')) {
            document.body.classList.remove('modal-open');
        }

        // Devolve o foco ao elemento anterior
        this.previousFocus?.focus();
    }
}


// Gerenciador automático de modais
class ModalManager {
    static instances = new Map();

    static init() {
        // Identifica todos os modais existentes
        document.querySelectorAll('.modal[id]').forEach(element => {
            this.instances.set(element.id, new Modal(element));
        });

        // Evento global de abertura e fechamento
        document.addEventListener('click', event => {
            const openButton = event.target.closest('[data-modal-target]');

            if (openButton) {
                event.preventDefault();

                const id = openButton.dataset.modalTarget;
                this.open(id);
                return;
            }

            const closeButton = event.target.closest('[data-modal-close]');

            if (closeButton) {
                const modalElement = closeButton.closest('.modal');

                if (modalElement) {
                    this.close(modalElement.id);
                }
            }
        });

        // Fecha o modal superior com ESC
        document.addEventListener('keydown', event => {
            if (event.key !== 'Escape') return;

            const activeModals = document.querySelectorAll('.modal.active');
            const lastModal = activeModals[activeModals.length - 1];

            if (lastModal) {
                this.close(lastModal.id);
            }
        });
    }

    static open(id) {
        const modal = this.instances.get(id);

        if (!modal) {
            console.warn(`Modal "${id}" não encontrado.`);
            return;
        }

        modal.open();
    }

    static close(id) {
        const modal = this.instances.get(id);

        if (!modal) {
            console.warn(`Modal "${id}" não encontrado.`);
            return;
        }

        modal.close();
    }
}


// Inicialização automática
document.addEventListener('DOMContentLoaded', () => {
    ModalManager.init();
});
