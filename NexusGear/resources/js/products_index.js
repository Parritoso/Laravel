document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteProductModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const nombre = button.getAttribute('data-nombre');
                const action = button.getAttribute('data-action');
                
                const modalNameDisplay = deleteModal.querySelector('#productNameDisplay');
                const modalForm = deleteModal.querySelector('#deleteProductForm');
                
                modalNameDisplay.textContent = nombre;
                modalForm.setAttribute('action', action);
            });
        }
    });