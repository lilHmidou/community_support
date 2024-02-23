document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleButton');
    const passwordField = document.getElementById('passwordField');
    const confirmPasswordField = document.getElementById('confirmPasswordField');

    toggleButton.addEventListener('click', function() {
        togglePasswordFieldVisibility(passwordField, toggleButton);
        togglePasswordFieldVisibility(confirmPasswordField, toggleButton);
    });

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
