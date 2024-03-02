document.addEventListener('DOMContentLoaded', function() {
    const editButton = document.getElementById('editButton');
    const saveChangesButton = document.getElementById('saveChangesButton');
    const cancelChangesButton = document.getElementById('cancelChangesButton');
    const deleteButton = document.getElementById('deleteButton');
    const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    const inputFields = document.querySelectorAll('.editable'); // Sélectionnez tous les champs d'entrée éditables
    const genderSelect = document.querySelector('.form-select.editable');
    // Désactivez tous les champs d'entrée éditables et les boutons "Enregistrer" et "Annuler" par défaut
    inputFields.forEach(function(field) {
        field.readOnly = true;
        field.disabled = true; // Désactivez le champ de sélection
    });
    saveChangesButton.style.display = 'none';
    cancelChangesButton.style.display = 'none';

    genderSelect.disabled = true;

    // Désactivez le champ de sélection du genre par défaut

    // Empêcher les changements sur le champ de sélection du genre
    genderSelect.addEventListener('change', function(event) {
        // Annulez la sélection de la nouvelle option
        event.preventDefault();
        // Si vous souhaitez afficher un message d'erreur ou effectuer une autre action, vous pouvez le faire ici
        console.log("Vous ne pouvez pas modifier le genre.");
    });

    const url = window.location.href;

    // Vérifie si l'URL contient "/authentification/profil/update"
    if (url.includes("/authentification/profil/update")) {
        inputFields.forEach(function(field) {
            field.readOnly = false;
            field.disabled = false; // Activez le champ de sélection
        });
        deleteButton.style.display = 'none';
        editButton.style.display = 'none';
        saveChangesButton.style.display = 'inline-block';
        cancelChangesButton.style.display = 'inline-block';

        // Désactivez le champ de sélection du genre
        genderSelect.disabled = true;
    }

    // Ajoutez un gestionnaire d'événement au clic sur le bouton "Modifier"
    editButton.addEventListener('click', function() {
        // Activez tous les champs d'entrée éditables et affichez les boutons "Enregistrer" et "Annuler"
        inputFields.forEach(function(field) {
            field.readOnly = false;
            field.disabled = false; // Activez le champ de sélection
        });
        editButton.style.display = 'none';
        deleteButton.style.display = 'none';
        saveChangesButton.style.display = 'inline-block';
        cancelChangesButton.style.display = 'inline-block';
    });

    // Ajoutez un gestionnaire d'événement au clic sur le bouton "Enregistrer les modifications"
    saveChangesButton.addEventListener('click', function() {
        // Soumettre le formulaire
        document.querySelector('form').submit();
    });
    deleteButton.addEventListener('click', function() {
        confirmDeleteModal.show();
    });
});
