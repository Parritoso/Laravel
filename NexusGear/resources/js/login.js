document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.querySelector('#password');
        const toggleIcon = document.querySelector('#togglePasswordIcon');

        toggleIcon.addEventListener('click', function () {
            // Cambiar el tipo de input
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Cambiar el icono (Bootstrap Icons)
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    });