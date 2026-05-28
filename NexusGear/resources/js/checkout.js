document.querySelectorAll('input[name="direccion_id"]').forEach((radio) => {
    radio.addEventListener('change', function() {
        const newAddressFields = document.getElementById('new-address-fields');
        // Solo se muestran los campos manuales cuando el usuario decide usar una dirección nueva.
        if (this.value === 'new') {
            newAddressFields.classList.remove('d-none');
            newAddressFields.scrollIntoView({ behavior: 'smooth' });
        } else {
            newAddressFields.classList.add('d-none');
        }
    });
});
