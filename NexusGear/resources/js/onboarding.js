function nextStep(step) {
    document.querySelectorAll('.onboarding-step').forEach(el => el.classList.add('d-none'));
    document.getElementById('step-' + step).classList.remove('d-none');

    // El formulario tiene cinco pasos; la barra refleja el avance real antes de finalizar.
    let progress = (step / 5) * 100;
    document.getElementById('onboarding-progress').style.width = progress + '%';
    window.scrollTo(0,0);
}

function initialize2FA() {
    const spinner = document.getElementById('2fa-spinner');
    spinner.classList.remove('d-none');

    // Fortify crea primero la configuración 2FA en servidor; después se solicita el QR.
    fetch('/user/two-factor-authentication', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        return fetch('/onboarding/2fa-qr');
    })
    .then(res => res.json())
    .then(data => {
        spinner.classList.add('d-none');
        if (data.svg) {
            // Se muestran el QR y la clave manual para que el usuario pueda configurar su app.
            document.getElementById('2fa-qr-container').innerHTML = data.svg;
            document.getElementById('2fa-secret-key').innerText = data.secret;

            document.getElementById('2fa-init-section').classList.add('d-none');
            document.getElementById('2fa-setup-section').classList.remove('d-none');

            // No se permite terminar el onboarding hasta validar un código correcto.
            document.getElementById('btn-finish-onboarding').disabled = true;
        }
    })
    .catch(error => {
        spinner.classList.add('d-none');
        console.error('Error inicializando 2FA:', error);
    });
}

// Verifica el código OTP introducido por el usuario durante el onboarding.
function confirm2FA() {
    const codeInput = document.getElementById('2fa-verification-code').value;
    const errorMsg = document.getElementById('2fa-error-msg');
    errorMsg.classList.add('d-none');

    // Fortify confirma el segundo factor y deja la cuenta con 2FA activado.
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

        document.getElementById('2fa-setup-section').classList.add('d-none');
        document.getElementById('2fa-success-section').classList.remove('d-none');

        // Tras confirmar 2FA, se evita volver al paso anterior para no dejar un estado incoherente.
        document.getElementById('btn-finish-onboarding').disabled = false;
        document.getElementById('btn-back-to-4').classList.add('d-none');
    })
    .catch(error => {
        errorMsg.innerText = "El código introducido no es válido. Inténtalo de nuevo.";
        errorMsg.classList.remove('d-none');
    });
}

// Permite finalizar el onboarding sin activar 2FA en ese momento.
function skip2FAAndFinish() {
    document.getElementById('onboarding-form').submit();
}
