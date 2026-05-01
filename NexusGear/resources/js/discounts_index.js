document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                // Botón que disparó el modal
                const button = event.relatedTarget;
                
                // Extraer info de los atributos data-*
                const codigo = button.getAttribute('data-codigo');
                const action = button.getAttribute('data-action');
                
                // Actualizar el contenido del modal
                const modalCodeDisplay = deleteModal.querySelector('#discountCodeDisplay');
                const modalForm = deleteModal.querySelector('#deleteForm');
                
                modalCodeDisplay.textContent = '«' + codigo + '»';
                modalForm.setAttribute('action', action);
            });
        }
    });