const app = {
  taches: [],

  init: function () {
    this.taches = Array.from(document.querySelectorAll('.tache'));
    this.initEditionInline();
    this.initStatut();
    this.initRecherche();
    this.initTri();
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
          const id = input.closest('.tache').querySelector('.checkbox-terminee').dataset.id;

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
        });
      });
    });
  },

  initRecherche: function () {
    const champ = document.getElementById('recherche');
    if (!champ) return;
    champ.addEventListener('input', () => {
      const valeur = champ.value.toLowerCase();
      this.taches.forEach(tache => {
        const texte = tache.querySelector('.texte').textContent.toLowerCase();
        tache.style.display = texte.includes(valeur) ? '' : 'none';
      });
    });
  },

  initTri: function () {
    document.querySelectorAll('[data-tri]').forEach(bouton => {
      bouton.addEventListener('click', () => {
        const critere = bouton.getAttribute('data-tri');
        this.trier(critere);
      });
    });
  },

  trier: function (type) {
    const getTexte = (el) => el.querySelector('.texte').textContent.toLowerCase();
    const getPriorite = (el) => {
      const priorite = el.dataset.priorite;
      const ordre = ['urgente', 'importante', 'normale'];
      return ordre.indexOf(priorite);
    };

    const getter = type === 'texte' ? getTexte : getPriorite;

    this.taches.sort((a, b) => {
      const valA = getter(a);
      const valB = getter(b);
      return valA > valB ? 1 : valA < valB ? -1 : 0;
    });

    const parent = this.taches[0].parentNode;
    this.taches.forEach(tache => parent.appendChild(tache));
  }
};

document.addEventListener('DOMContentLoaded', () => {
  app.init();

  // Gestion du thème sombre
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

  // Modale de confirmation de suppression
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






