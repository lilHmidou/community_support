document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.getElementById('passwordRegistrationField');
    const criteriaListItems = document.querySelectorAll('.criteria-list li');

    passwordField.addEventListener('input', function() {
        const password = passwordField.value;
        criteriaListItems.forEach(item => {
            const criterion = item.textContent.trim();
            if (checkCriterion(password, criterion)) {
                item.classList.add('criteria-met');
            } else {
                item.classList.remove('criteria-met');
            }
        });
    });

    function checkCriterion(password, criterion) {
        switch (criterion) {
            case 'au moins 8 caractères':
                return password.length >= 8;
            case 'une lettre majuscule':
                return /[A-Z]/.test(password);
            case 'une lettre minuscule':
                return /[a-z]/.test(password);
            case 'un chiffre':
                return /\d/.test(password);
            case 'un caractère spécial @ $ ! % * ? & .':
                return /[@$!%*?&.]/.test(password);
            default:
                return false;
        }
    }
});
