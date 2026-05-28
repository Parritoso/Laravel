import './bootstrap';
import * as bootstrap from 'bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        // Los modales de borrado se reutilizan: el botón indica el texto visible y la ruta del form.
        const modal = event.target;
        const nombre = button.getAttribute('data-nombre') || button.getAttribute('data-codigo');
        const action = button.getAttribute('data-action');

        if (nombre && action) {
            const display = modal.querySelector('.modal-body h4');
            const form = modal.querySelector('form');
            
            if (display) display.textContent = `«${nombre}»`;
            if (form) form.setAttribute('action', action);
        }
    });
});
