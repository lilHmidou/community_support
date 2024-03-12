document.addEventListener('DOMContentLoaded', function() {
    const deleteButton = document.getElementById('deleteButton');
    const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

    // Ajouter un gestionnaire d'événements pour le clic sur le bouton "Supprimer le compte"
    deleteButton.addEventListener('click', function() {
        // Afficher la fenêtre modale de confirmation de suppression
        confirmDeleteModal.show();
    });
});