import './bootstrap';
import * as bootstrap from 'bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    // Escuchar cualquier modal que se abra en la app
    document.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        // Buscamos si el botón tiene los atributos necesarios
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