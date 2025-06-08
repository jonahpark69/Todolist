document.addEventListener("DOMContentLoaded", () => {
    const app = {
        init() {
            this.handleFlashMessage();
            this.initCheckboxTerminee(); // 👈 AJOUT ICI
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
        },

        initCheckboxTerminee() {
            const checkboxes = document.querySelectorAll('.checkbox-terminee');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', async (e) => {
                    const id = e.target.dataset.id;
                    const terminee = e.target.checked ? 1 : 0;

                    try {
                        const response = await fetch('update-terminee.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `id=${id}&terminee=${terminee}`
                        });

                        if (!response.ok) {
                            throw new Error("Erreur serveur");
                        }

                        console.log(`Tâche ${id} mise à jour`);
                    } catch (err) {
                        alert("Échec de la mise à jour");
                    }
                });
            });
        }
    };

    app.init();
});

