document.addEventListener('DOMContentLoaded', function() {
    const inputFields = document.querySelectorAll('.editable');
    const genderSelect = document.querySelectorAll('input[name="profil_form[Gender]"]');

    // Désactivez tous les champs d'entrée éditables et les boutons "Enregistrer" et "Annuler" par défaut
    inputFields.forEach(function(field) {
        field.readOnly = true;
    });
    genderSelect.forEach(function(radio) {
        radio.parentElement.style.pointerEvents = 'none'; // Désactiver les événements de souris pour empêcher les clics
    });
});
