/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: ["./app/Views/**/*.php",'node_modules/preline/dist/*.js'],
  theme: {
  },

  plugins: [
    require('preline/plugin'),
  ],
};
