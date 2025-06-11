/**
 * Configuration de Tailwind CSS (mode build)
 * Permet d'étendre les animations ou autres styles personnalisés
 */

module.exports = {
  content: [
    './index.php',      // Analyse Tailwind dans le HTML principal
    './main.js',        // Analyse les classes utilisées dynamiquement en JS
    './success.js'      // Pour les classes utilisées par l'animation ✔️
  ],
  theme: {
    extend: {
      // Animation personnalisée utilisée pour faire apparaître un élément
      keyframes: {
        pop: {
          '0%':   { transform: 'scale(0)', opacity: '0' },
          '60%':  { transform: 'scale(1.2)', opacity: '1' },
          '100%': { transform: 'scale(1)',   opacity: '0' },
        },
      },
      animation: {
        'pop-once': 'pop 1.4s ease-in-out forwards',
      },
    },
  },
  plugins: [],
};



