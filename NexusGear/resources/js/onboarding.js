function nextStep(step) {
    document.querySelectorAll('.onboarding-step').forEach(el => el.classList.add('d-none'));
    document.getElementById('step-' + step).classList.remove('d-none');
    
    // Actualizar barra de progreso (25% por paso)
    let progress = (step / 5) * 100;
    document.getElementById('onboarding-progress').style.width = progress + '%';
    window.scrollTo(0,0);
}

function initialize2FA() {
    const spinner = document.getElementById('2fa-spinner');
    spinner.classList.remove('d-none');

    // 1. Llamada POST nativa de Fortify para inicializar el 2FA en el servidor
    fetch('/user/two-factor-authentication', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        // 2. Si el servidor respondió bien, solicitamos el QR y la clave secreta descifrada
        return fetch('/onboarding/2fa-qr');
    })
    .then(res => res.json())
    .then(data => {
        spinner.classList.add('d-none');
        if (data.svg) {
            // Inyectamos el QR y la clave manual en la vista
            document.getElementById('2fa-qr-container').innerHTML = data.svg;
            document.getElementById('2fa-secret-key').innerText = data.secret;
            
            // Ocultamos la pregunta inicial y revelamos la sección de escaneo/confirmación
            document.getElementById('2fa-init-section').classList.add('d-none');
            document.getElementById('2fa-setup-section').classList.remove('d-none');
            
            // Bloqueamos el botón de finalizar hasta que el usuario confirme el código con éxito
            document.getElementById('btn-finish-onboarding').disabled = true;
        }
    })
    .catch(error => {
        spinner.classList.add('d-none');
        console.error('Error inicializando 2FA:', error);
    });
}

// Verificar el código OTP metido por el usuario en el Onboarding
function confirm2FA() {
    const codeInput = document.getElementById('2fa-verification-code').value;
    const errorMsg = document.getElementById('2fa-error-msg');
    errorMsg.classList.add('d-none');

    // Llamada POST nativa de Fortify para confirmar y dar por válido el 2FA
    fetch('/user/confirmed-two-factor-authentication', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ code: codeInput })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Código inválido');
        }
        // Si todo es correcto, mostramos la sección de éxito definitivo
        document.getElementById('2fa-setup-section').classList.add('d-none');
        document.getElementById('2fa-success-section').classList.remove('d-none');
        
        // Desbloqueamos el botón final y ocultamos el botón de ir atrás para evitar inconsistencias
        document.getElementById('btn-finish-onboarding').disabled = false;
        document.getElementById('btn-back-to-4').classList.add('d-none');
    })
    .catch(error => {
        // Mensaje directo en caso de error de sincronización o código erróneo
        errorMsg.innerText = "El código introducido no es válido. Inténtalo de nuevo.";
        errorMsg.classList.remove('d-none');
    });
}

// Enviar el formulario directamente saltándose la configuración del 2FA
function skip2FAAndFinish() {
    document.getElementById('onboarding-form').submit();
}