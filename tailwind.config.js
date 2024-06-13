/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: ["./app/Views/**/*.php",'node_modules/preline/dist/*.js'],
  theme: {
    extend: {
      fontFamily: {
        gilroy: ['Gilroy', 'sans-serif'],
      },
    },
  },

  plugins: [
    require('preline/plugin'),
  ],
};
