document.addEventListener('DOMContentLoaded', function() {
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');

    confirmDeleteButton.addEventListener('click', function() {
        // Mettre à jour le lien du bouton de confirmation avec l'ID de l'utilisateur à supprimer
        const userId = document.getElementById('userId').value;
        confirmDeleteButton.href = "{{ path('delete') }}?id=" + userId;
    });
});