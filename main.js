document.addEventListener("DOMContentLoaded", () => {
    const app = {
        init() {
            this.handleFlashMessage();
            // Tu ajouteras ici d’autres fonctions comme :
            // this.initToggleTache();
            // this.initFiltrage();
        },

        handleFlashMessage() {
            const message = document.querySelector('.message');
            if (message) {
                setTimeout(() => {
                    message.style.opacity = '0';
                    message.style.transition = 'opacity 0.6s ease-out';
                    setTimeout(() => message.remove(), 700);
                }, 3000);
            }
        }
    };

    app.init();
});
