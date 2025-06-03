// ✅ Attendre que la page soit complètement chargée
document.addEventListener("DOMContentLoaded", () => {
    // 🔍 Sélectionner l'élément contenant le message de confirmation ou d'erreur
    const message = document.querySelector('.message');

    // ✅ S'il y a un message, lancer le processus de disparition
    if (message) {
        setTimeout(() => {
            // 🕒 Après 3 secondes, commencer à le faire disparaître en douceur
            message.style.opacity = '0';
            message.style.transition = 'opacity 0.6s ease-out';

            // ⏳ Une fois la transition terminée (0.6s), supprimer complètement l'élément du DOM
            setTimeout(() => {
                message.remove(); // 🔥 Libère de l'espace mémoire et nettoie la page
            }, 700); // Légèrement plus long que la transition pour s'assurer que c’est fluide
        }, 3000); // ⏱️ Attente initiale de 3 secondes avant de lancer l’effet
    }
});

