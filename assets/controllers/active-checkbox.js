const Checkbox = document.getElementById('reg-log');

// Sélection de la carte
const cardFront = document.querySelector('.card-front');
const cardBack = document.querySelector('.card-back');
const cardWrapper = document.querySelector('.card-3d-wrapper');
const body = document.getElementById('main-body');

let lastPath = window.location.pathname;

// Fonction pour ajuster la taille de la carte en fonction de l'état de connexion
function ajusterTailleCarte() {
    // Vérifier si le checkbox de connexion est coché
    const estCoche = Checkbox.checked;

    // Si le checkbox est coché, ajuster la taille de la carte
    if (estCoche) {
        cardFront.style.height = '100%'; // Hauteur normale
        cardWrapper.style.height = '100%'; // Hauteur réduite
        body.style.paddingBottom = '400px';
        cardFront.style.display = 'none';
        cardBack.style.display = 'block';
    } else {
        cardFront.style.height = '200%';
        cardWrapper.style.height = '50%'; // Hauteur réduite
        body.style.paddingBottom = '50px';
        cardFront.style.display = 'block';
        cardBack.style.display = 'none';
    }
}

// Fonction pour vérifier si un message flash d'erreur est présent
function hasErrorFlash() {
    const flashContainers = document.querySelectorAll('.alert-danger');
    return flashContainers.length > 0;
}

// Écouter les changements d'état du checkbox de connexion et mettre à jour l'URL
Checkbox.addEventListener('change', function(event) {
    if (hasErrorFlash()) {
        // S'il y a un message flash d'erreur, recharger la page
        window.location.href = event.target.checked ? '/register' : '/login';
    } else {
        // Sinon, mettre à jour l'URL et ajuster la taille de la carte
        history.pushState({}, null, event.target.checked ? '/register' : '/login');
        ajusterTailleCarte();
    }
});

// Écouter les changements d'URL
window.addEventListener('popstate', function(event) {
    if (lastPath !== window.location.pathname) {
        sessionStorage.removeItem('symfony_flashes');
        lastPath = window.location.pathname;
    }
    ajusterTailleCarte();
});

// Appel initial pour ajuster la taille de la carte au chargement de la page
ajusterTailleCarte();