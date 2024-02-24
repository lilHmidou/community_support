document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.faq-item-title').forEach(item => {
        item.addEventListener('click', () => {
            const faqItem = item.parentNode;
            const content = item.nextElementSibling;
            const toggle = item.querySelector('.faq-toggle');

            if (content.style.display === 'block') {
                content.style.display = 'none';
                faqItem.classList.remove('active');
                content.classList.remove('active-content'); // Enlève la classe pour la réponse
                toggle.textContent = '+'; // Change le signe en +

            } else {
                // Fermer tous les contenus actifs et remettre le signe à +
                document.querySelectorAll('.faq-item-content').forEach(c => {
                    c.style.display = 'none';
                    c.classList.remove('active-content'); // Enlève la classe pour toutes les réponses
                });
                document.querySelectorAll('.faq-item').forEach(i => {
                    i.classList.remove('active');
                    i.querySelector('.faq-toggle').textContent = '+';
                });

                // Ouvrir le contenu cliqué
                content.style.display = 'block';
                faqItem.classList.add('active');
                content.classList.add('active-content'); // Ajoute la classe pour la réponse déroulée
                toggle.textContent = '-'; // Change le signe en -
            }
        });
    });
});

