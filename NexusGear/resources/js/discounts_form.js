// Mantiene la unidad del campo "valor" coherente con el tipo de descuento seleccionado.
document.getElementById('tipo').addEventListener('change', function() {
    const addon = document.getElementById('valor-addon');
    addon.textContent = this.value === 'porcentaje' ? '%' : '€';
});

window.addEventListener('DOMContentLoaded', () => {
    const tipo = document.getElementById('tipo').value;
    if(tipo) document.getElementById('valor-addon').textContent = tipo === 'porcentaje' ? '%' : '€';
});
