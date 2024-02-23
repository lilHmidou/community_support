document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleButton');
    const passwordField = document.getElementById('passwordField');

    toggleButton.addEventListener('click', function() {
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleButton.innerHTML = '<i class="fa-regular fa-eye-slash"></i>'; // Œil fermé
        } else {
            passwordField.type = 'password';
            toggleButton.innerHTML = '<i class="fa-regular fa-eye"></i>'; // Œil ouvert
        }
    });
});
