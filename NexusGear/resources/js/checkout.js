document.querySelectorAll('input[name="direccion_id"]').forEach((radio) => {
    radio.addEventListener('change', function() {
        const newAddressFields = document.getElementById('new-address-fields');
        if (this.value === 'new') {
            newAddressFields.classList.remove('d-none');
            newAddressFields.scrollIntoView({ behavior: 'smooth' });
        } else {
            newAddressFields.classList.add('d-none');
        }
    });
});