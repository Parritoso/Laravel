document.addEventListener('DOMContentLoaded', function () {
        // Seleccionamos todos los iconos de "ojo"
        const toggleButtons = document.querySelectorAll('.toggle-password');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Buscamos el input que está dentro del mismo contenedor que el icono clicado
                const input = this.parentElement.querySelector('input');
                
                // Cambiamos el tipo
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                // Cambiamos el icono solo para este elemento
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        });
    });