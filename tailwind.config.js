/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      './**/*.php', // Inclure vos fichiers PHP
  ],
  theme: {
      extend: {
          colors: {
              primary: '#002043',   // Couleur principale
              secondary: '#D3B04D', // Couleur secondaire
              accent: '#CE3939',    // Couleur d'accentuation
              background: '#FEFEFE', // Couleur d'arrière-plan
              text: '#222222', // Couleur de texte
              white_text: "#FEFEFE" // Texte sur fond sombre
          },
      },
  },
  plugins: [],
};


