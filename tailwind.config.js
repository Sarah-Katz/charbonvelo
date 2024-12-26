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
          },
      },
  },
  plugins: [],
};


