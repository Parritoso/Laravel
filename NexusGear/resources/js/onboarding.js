function nextStep(step) {
    document.querySelectorAll('.onboarding-step').forEach(el => el.classList.add('d-none'));
    document.getElementById('step-' + step).classList.remove('d-none');
    
    // Actualizar barra de progreso (25% por paso)
    let progress = (step / 4) * 100;
    document.getElementById('onboarding-progress').style.width = progress + '%';
    window.scrollTo(0,0);
}