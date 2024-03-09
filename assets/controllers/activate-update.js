document.addEventListener('DOMContentLoaded', function() {
    const editButton = document.getElementById('editButton');
    const saveChangesButton = document.getElementById('saveChangesButton');
    const cancelChangesButton = document.getElementById('cancelChangesButton');
    const deleteButton = document.getElementById('deleteButton');
    const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    const inputFields = document.querySelectorAll('.editable');
    const genderSelect = document.querySelectorAll('input[name="registration[Gender]"]');

    // Désactivez tous les champs d'entrée éditables et les boutons "Enregistrer" et "Annuler" par défaut
    inputFields.forEach(function(field) {
        field.readOnly = true;
    });
    genderSelect.forEach(function(radio) {
        radio.disabled = true;
    });

    saveChangesButton.style.display = 'none';
    cancelChangesButton.style.display = 'none';

    const url = window.location.href;

    // Vérifie si l'URL contient "/authentification/profil/update"
    if (url.includes("/authentification/profil/update")) {
        inputFields.forEach(function(field) {
            field.readOnly = false;
        });
        genderSelect.forEach(function(radio) {
            radio.disabled = false;
        });
        deleteButton.style.display = 'none';
        editButton.style.display = 'none';
        saveChangesButton.style.display = 'inline-block';
        cancelChangesButton.style.display = 'inline-block';
    }

    // Ajoutez un gestionnaire d'événement au clic sur le bouton "Enregistrer les modifications"
    saveChangesButton.addEventListener('click', function() {
        // Soumettre le formulaire
        document.querySelector('form').submit();
    });
    deleteButton.addEventListener('click', function() {
        confirmDeleteModal.show();
    });
});
