// Pequeño script opcional para mejorar la UX: cambia el addon del input según el tipo seleccionado
    document.getElementById('tipo').addEventListener('change', function() {
        const addon = document.getElementById('valor-addon');
        addon.textContent = this.value === 'porcentaje' ? '%' : '€';
    });
    // Disparar una vez al cargar por si es edición
    window.addEventListener('DOMContentLoaded', () => {
        const tipo = document.getElementById('tipo').value;
        if(tipo) document.getElementById('valor-addon').textContent = tipo === 'porcentaje' ? '%' : '€';
    });