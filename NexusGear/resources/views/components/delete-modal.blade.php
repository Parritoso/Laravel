@props([
    'id',           // ID único para el modal
    'formId',       // ID para el formulario
    'title',        // Título traducido
    'message',      // Mensaje principal traducido
    'buttonText',   // Texto del botón de confirmar
    'icon' => 'bi-trash3', // Icono por defecto
    'showWarning' => false // Para mostrar el aviso de categorías
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="text-danger mb-3">
                    <i class="bi {{ $icon }}" style="font-size: 3.5rem;"></i>
                </div>
                <p class="mb-1 text-muted">{{ $message }}</p>
                {{-- Aquí se inyectará el nombre dinámicamente vía JS --}}
                <h4 class="fw-bold px-3" id="{{ $id }}-display"></h4>
                
                @if($showWarning)
                    <div class="alert alert-warning mx-3 mt-3 mb-0" style="font-size: 0.85rem;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ __('components/delete-modal.delete_warning_cascade') }}
                    </div>
                @endif

                <p class="text-muted small mt-3">{{ __('components/delete-modal.confirm_undone') }}</p>
            </div>
            <div class="modal-footer border-0 bg-light d-flex justify-content-center">
                <button type="button" class="btn btn-secondary px-4 fw-semibold" data-bs-dismiss="modal">
                    {{ __('components/delete-modal.cancel') }}
                </button>
                <form id="{{ $formId }}" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 fw-bold">{{ $buttonText }}</button>
                </form>
            </div>
        </div>
    </div>
</div>