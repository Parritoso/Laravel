document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.querySelector('#password');
        const toggleIcon = document.querySelector('#togglePasswordIcon');

        toggleIcon.addEventListener('click', function () {
            // Alterna visibilidad sin cambiar el valor escrito por el usuario.
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    });
