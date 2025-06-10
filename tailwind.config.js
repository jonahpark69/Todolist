/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './index.php',       // ou index.html selon ton projet
    './main.js',         // ton script principal
    './success.js'       // si tu veux analyser aussi ce fichier
  ],
  theme: {
    extend: {
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
}


