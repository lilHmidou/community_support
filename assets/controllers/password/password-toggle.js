document.addEventListener('DOMContentLoaded', function() {
    const toggleRegistrationButton = document.getElementById('toggleButton');
    const passwordRegistrationField = document.getElementById('passwordRegistrationField');
    const toggleLogInButton = document.getElementById('toggleLogInButton');
    const passwordLogInField = document.getElementById('passwordLogInField');
    const confirmPasswordField = document.getElementById('confirmPasswordField');

    if (toggleRegistrationButton && passwordRegistrationField && confirmPasswordField) {
        toggleRegistrationButton.addEventListener('click', function() {
            togglePasswordFieldVisibility(passwordRegistrationField, toggleRegistrationButton);
            togglePasswordFieldVisibility(confirmPasswordField, toggleRegistrationButton);
        });
    }

    if (toggleLogInButton && passwordLogInField) {
        toggleLogInButton.addEventListener('click', function() {
            togglePasswordFieldVisibility(passwordLogInField, toggleLogInButton);
        });
    }

    function togglePasswordFieldVisibility(field, button) {
        if (field.type === 'password') {
            field.type = 'text';
            button.innerHTML = '<i class="fa-regular fa-eye"></i>'; // Œil ouvert
        } else {
            field.type = 'password';
            button.innerHTML = '<i class="fa-regular fa-eye-slash"></i>'; // Œil fermé
        }
    }
});
