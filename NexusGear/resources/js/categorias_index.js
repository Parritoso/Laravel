document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteCategoryModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Botón que abrió el modal
                
                // Extraer datos
                const nombre = button.getAttribute('data-nombre');
                const action = button.getAttribute('data-action');
                
                // Actualizar elementos del modal
                const modalNameDisplay = deleteModal.querySelector('#categoryNameDisplay');
                const modalForm = deleteCategoryModal.querySelector('#deleteCategoryForm');
                
                modalNameDisplay.textContent = '«' + nombre + '»';
                modalForm.setAttribute('action', action);
            });
        }
    });