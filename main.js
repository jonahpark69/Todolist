/* =========================================================================
 *  main.js — Gestion complète de l’app Todo List
 *  Étape ajoutée : suppression groupée des tâches terminées (Jour 6 • Étape 3)
 * ========================================================================= */
const app = {
  init: function () {
    this.initTheme();
    this.initEditionInline();
    this.initStatut();
    this.initRecherche();
    this.initTri();
    this.initVueGrille();
    this.initDeleteAllModal();   // ← logique améliorée
    this.initModalSuppression();
  },

  /* -------------------- DARK / LIGHT MODE -------------------- */
  initTheme: function () {
    const html      = document.documentElement;
    const toggleBtn = document.getElementById('toggle-theme');

    if (localStorage.getItem('theme') === 'dark') html.classList.add('dark');

    toggleBtn?.addEventListener('click', () => {
      html.classList.toggle('dark');
      localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    });
  },

  /* -------------------- ÉDITION INLINE DU TEXTE -------------------- */
  initEditionInline: function () {
    document.querySelectorAll('.texte').forEach((texteEl) => {
      texteEl.addEventListener('dblclick', () => {
        const ancienTexte = texteEl.textContent.trim();
        const input       = document.createElement('input');
        input.type        = 'text';
        input.value       = ancienTexte;
        input.className   =
          'w-full p-1 border rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-white border-gray-300 dark:border-gray-600 transition';

        texteEl.replaceWith(input);
        input.focus();

        input.addEventListener('blur', () => {
          const nouveauTexte = input.value.trim();
          const parentTache  = input.closest('.tache');
          const checkbox     = parentTache?.querySelector('.checkbox-terminee');
          const id           = checkbox?.dataset.id;
          if (!id) return;

          fetch('update-texte.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(id) + '&texte=' + encodeURIComponent(nouveauTexte),
          }).then(() => location.reload());
        });
      });
    });
  },

  /* -------------------- STATUT TERMINÉE -------------------- */
 initStatut: function () {
  document.querySelectorAll('.checkbox-terminee').forEach((checkbox) => {
    checkbox.addEventListener('change', () => {
      const id       = checkbox.dataset.id;
      const terminee = checkbox.checked ? 1 : 0;

      fetch('update-terminee.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${encodeURIComponent(id)}&terminee=${encodeURIComponent(terminee)}`,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            const tache = checkbox.closest('.tache');
            const texte = tache.querySelector('.texte');
            tache.classList.toggle('terminee', !!terminee);
            tache.classList.toggle('opacity-60', !!terminee);
            texte?.classList.toggle('line-through', !!terminee);

            // ✅ Animation uniquement quand la case est cochée
            if (terminee === 1) {
              showSuccess();
            }
          }
        });
    });
  });
},


/* -------------------- RECHERCHE TEMPS RÉEL -------------------- */
initRecherche: function () {
  const champ = document.getElementById('recherche');
  if (!champ) return;

  /* Nettoyage : minuscules, sans accents ni ponctuation, espaces simples */
  const nettoyer = (txt) =>
    txt
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')  // accents
      .replace(/[^\w\s]/g, '')          // ponctuation
      .replace(/\s+/g, ' ')             // espaces multiples
      .trim();

  const filtrer = () => {
    const valeur = nettoyer(champ.value);
    const liste  = document.querySelector('ul.taches');
    const visibles   = [];
    const invisibles = [];

    /* Parcourt chaque tâche et décide si elle reste visible */
    liste.querySelectorAll('li.tache').forEach((tache) => {
      const contenu = nettoyer(tache.querySelector('.texte')?.textContent || '');
      const visible = valeur === '' || contenu.includes(valeur);

      tache.classList.toggle('hidden', !visible);
      tache.style.display = visible ? '' : 'none';

      (visible ? visibles : invisibles).push(tache);
    });

    /* ➜ Place d'abord les tâches visibles en haut de la liste */
    [...visibles, ...invisibles].forEach((el) => liste.appendChild(el));

    /* ➜ Remonte la liste pour que l’utilisateur voie immédiatement le résultat */
    liste.scrollTop = 0;
  };

  /* Un seul écouteur suffit */
  champ.addEventListener('input', filtrer);

  /* Appel initial pour afficher la liste complète */
  filtrer();
},





  /* -------------------- TRI A→Z / PRIORITÉ / DATE -------------------- */
  initTri: function () {
    document.querySelectorAll('[data-tri]').forEach((bouton) => {
      bouton.addEventListener('click', () => {
        const critere = bouton.dataset.tri;
        const liste   = document.querySelector('ul.taches');
        const items   = [...liste.querySelectorAll('li.tache')];

        const getTexte = (el) => el.querySelector('.texte')?.textContent.toLowerCase() || '';
        const getPriorite = (el) => ['urgente', 'importante', 'normale'].indexOf(el.dataset.priorite);

        const getter = critere === 'texte' ? getTexte : getPriorite;
        items.sort((a, b) => {
          const valA = getter(a), valB = getter(b);
          return valA > valB ? 1 : valA < valB ? -1 : 0;
        });

        items.forEach((el) => liste.appendChild(el));
      });
    });
  },

/* -------------------- LISTE vs GRILLE -------------------- */
initVueGrille: function () {
  const btn  = document.getElementById('toggleVue');
  const list = document.getElementById('liste-taches');
  if (!btn || !list) return;

  /* -------- Vue Grille -------- */
const toGrid = () => {
  list.classList.remove('flex', 'flex-col');
  list.classList.add('grid', 'grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3', 'gap-4');

  list.querySelectorAll('.tache').forEach((li) => {
    li.classList.remove('flex-row', 'justify-between');          //  ⟵ on retire
    li.classList.add   ('flex-col', 'gap-2', 'items-start');      //  ⟵ plus de h-full
  });

  btn.textContent = 'Vue Liste';
  localStorage.setItem('vueTaches', 'grille');
};

/* -------- Vue Liste -------- */
const toList = () => {
  list.classList.remove('grid', 'grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3', 'gap-4');
  list.classList.add('flex', 'flex-col');

  list.querySelectorAll('.tache').forEach((li) => {
    li.classList.remove('flex-col', 'gap-2', 'items-start');
    li.classList.add   ('flex-row', 'justify-between');
  });

  btn.textContent = 'Vue Grille';
  localStorage.setItem('vueTaches', 'liste');
};


  /* -------- Toggle & état initial -------- */
  btn.addEventListener('click', () =>
    list.classList.contains('flex') ? toGrid() : toList()
  );

  localStorage.getItem('vueTaches') === 'grille' ? toGrid() : toList();
},



  /* ------------------------------------------------------------------
   *  JOUR 6 • ÉTAPE 3  → Suppression groupée des tâches terminées
   * ------------------------------------------------------------------ */
  initDeleteAllModal: function () {
    const modal      = document.getElementById('modal-delete-all');
    const confirmBtn = document.getElementById('confirm-delete-all');
    const cancelBtn  = document.getElementById('cancel-delete-all');
    const triggerBtn = document.getElementById('delete-all-completed');

    if (!modal || !confirmBtn || !cancelBtn || !triggerBtn) return;

    triggerBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    cancelBtn .addEventListener('click', () => modal.classList.add   ('hidden'));

    /* --- Appel AJAX + ménage DOM --- */
    confirmBtn.addEventListener('click', async () => {
      try {
        const res  = await fetch('delete-all-completed.php', { method: 'POST' });
        const json = await res.json();
        if (!json.success) throw new Error(json.error || 'Erreur serveur');

        document.querySelectorAll('.tache.terminee').forEach((el) => el.remove());
      } catch (err) {
        alert('Impossible de supprimer : ' + err.message);
      } finally {
        modal.classList.add('hidden');
      }
    });
  },

  /* -------------------- MODALE SUPPRESSION INDIVIDUELLE -------------------- */
  initModalSuppression: function () {
    document.querySelectorAll('a[href*="supprimer="]').forEach((link) => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const url        = link.getAttribute('href');
        const modal      = document.getElementById('modal-confirm');
        const confirmBtn = document.getElementById('confirm-btn');
        const cancelBtn  = document.getElementById('cancel-btn');
        if (!modal || !confirmBtn || !cancelBtn) return;

        confirmBtn.setAttribute('href', url);
        modal.classList.remove('hidden');
        cancelBtn.onclick = () => {
          modal.classList.add('hidden');
          confirmBtn.setAttribute('href', '#');
        };
      });
    });
  },
};

document.addEventListener('DOMContentLoaded', () => app.init());














