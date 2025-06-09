const app = {
  init: function () {
    this.initEditionInline();
    this.initStatut();
    this.initRecherche();
    this.initTri();
    this.initDeleteAllModal();
  },

  initEditionInline: function () {
    document.querySelectorAll('.texte').forEach(texteEl => {
      texteEl.addEventListener('dblclick', () => {
        const ancienTexte = texteEl.textContent.trim();
        const input = document.createElement('input');
        input.type = 'text';
        input.value = ancienTexte;
        input.className = 'w-full p-1 border rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-white border-gray-300 dark:border-gray-600 transition';

        texteEl.replaceWith(input);
        input.focus();

        input.addEventListener('blur', () => {
          const nouveauTexte = input.value.trim();
          const parentTache = input.closest('.tache');
          const checkbox = parentTache ? parentTache.querySelector('.checkbox-terminee') : null;
          const id = checkbox ? checkbox.dataset.id : null;

          if (!id) return;

          fetch('update-texte.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id=' + encodeURIComponent(id) + '&texte=' + encodeURIComponent(nouveauTexte)
          }).then(() => location.reload());
        });
      });
    });
  },

  initStatut: function () {
    document.querySelectorAll('.checkbox-terminee').forEach(checkbox => {
      checkbox.addEventListener('change', () => {
        const id = checkbox.dataset.id;
        const terminee = checkbox.checked ? 1 : 0;

        fetch('update-terminee.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'id=' + encodeURIComponent(id) + '&terminee=' + encodeURIComponent(terminee)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const tache = checkbox.closest('.tache');
            const texte = tache.querySelector('.texte');

            if (terminee) {
              tache.classList.add('terminee', 'opacity-60');
              if (texte) texte.classList.add('line-through');
            } else {
              tache.classList.remove('terminee', 'opacity-60');
              if (texte) texte.classList.remove('line-through');
            }
          }
        });
      });
    });
  },

  initRecherche: function () {
  const champ = document.getElementById('recherche');
  if (!champ) return;

  // ▼ fonction utilitaire : on garde uniquement lettres/chiffres, sans accents
  const nettoyer = (txt) =>
    txt
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')   // accents → e, a, o…
      .replace(/[^\w\s]/g, '')           // retire ponctuation & invisi­bles
      .replace(/\s+/g, ' ')              // espaces multiples → 1 espace
      .trim();

  const filtrer = () => {
    const valeur = nettoyer(champ.value);          // texte tapé normalisé
    const taches = document.querySelectorAll('ul.taches > li.tache');

    taches.forEach((tache) => {
      const texteEl = tache.querySelector('.texte');
      if (!texteEl) return;

      const contenu = nettoyer(texteEl.textContent); // texte de la tâche

      /* — affichage — */
      if (valeur === '' || contenu.includes(valeur)) {
        // on enlève TOUT ce qui pourrait masquer l’élément
        tache.classList.remove('hidden');
        tache.style.display = '';          // vide l’éventuel display:none inline
      } else {
        tache.classList.add('hidden');     // classe Tailwind = display:none
        tache.style.display = 'none';      // assure qu’il est vraiment masqué
      }
    });
  };

  /* événements */
  champ.addEventListener('input', filtrer);
  champ.addEventListener('keyup', filtrer);

  /* premier appel : liste complète visible au chargement */
  filtrer();
},




  initTri: function () {
    document.querySelectorAll('[data-tri]').forEach(bouton => {
      bouton.addEventListener('click', () => {
        const critere = bouton.getAttribute('data-tri');
        const liste = document.querySelector('ul.taches');
        const items = Array.from(liste.querySelectorAll('li.tache'));

        const getTexte = (el) => el.querySelector('.texte').textContent.toLowerCase();
        const getPriorite = (el) => {
          const priorite = el.dataset.priorite;
          const ordre = ['urgente', 'importante', 'normale'];
          return ordre.indexOf(priorite);
        };

        const getter = critere === 'texte' ? getTexte : getPriorite;

        items.sort((a, b) => {
          const valA = getter(a);
          const valB = getter(b);
          return valA > valB ? 1 : valA < valB ? -1 : 0;
        });

        items.forEach(item => liste.appendChild(item));
      });
    });
  },

  initDeleteAllModal: function () {
    const modal = document.getElementById("modal-delete-all");
    const confirmBtn = document.getElementById("confirm-delete-all");
    const cancelBtn = document.getElementById("cancel-delete-all");
    const triggerBtn = document.getElementById("delete-all-completed");

    if (!modal || !confirmBtn || !cancelBtn || !triggerBtn) return;

    triggerBtn.addEventListener("click", () => {
      modal.classList.remove("hidden");
    });

    cancelBtn.addEventListener("click", () => {
      modal.classList.add("hidden");
    });

    confirmBtn.addEventListener("click", () => {
      fetch("delete-all-completed.php", {
        method: "POST",
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          document.querySelectorAll('.tache.terminee').forEach(tache => tache.remove());
          modal.classList.add("hidden");
        }
      });
    });
  }
};

document.addEventListener('DOMContentLoaded', () => {
  app.init();

  const html = document.documentElement;
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    html.classList.add('dark');
  }

  const toggleBtn = document.getElementById('toggle-theme');
  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      html.classList.toggle('dark');
      const newTheme = html.classList.contains('dark') ? 'dark' : 'light';
      localStorage.setItem('theme', newTheme);
    });
  }

  document.querySelectorAll('a[href*="supprimer="]').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const url = link.getAttribute('href');
      const modal = document.getElementById('modal-confirm');
      const confirmBtn = document.getElementById('confirm-btn');
      const cancelBtn = document.getElementById('cancel-btn');

      if (!modal || !confirmBtn || !cancelBtn) return;

      confirmBtn.setAttribute('href', url);
      modal.classList.remove('hidden');

      cancelBtn.onclick = () => {
        modal.classList.add('hidden');
        confirmBtn.setAttribute('href', '#');
      };
    });
  });
});












