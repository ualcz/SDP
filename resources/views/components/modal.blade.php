
@props([
    'id',
    'title' => 'Modal',
    'size' => 'md',
])

<div
    id="{{ $id }}"
    class="modal"
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
>
    <div class="modal-overlay" data-modal-close></div>

    <div class="modal-container modal-{{ $size }}"
     tabindex="-1">
        <div class="modal-header">
            <h2 id="{{ $id }}-title">
                {{ $title }}
            </h2>

            <button
                type="button"
                class="modal-close"
                data-modal-close
                aria-label="Fechar modal"
            >
                &times;
            </button>
        </div>

        <div class="modal-body">
            {{ $slot }}
        </div>

        @isset($footer)
            <div class="modal-footer">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
