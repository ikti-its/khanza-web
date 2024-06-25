/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: ["./app/Views/**/*.php", 'node_modules/preline/dist/*.js'],
  theme: {
    fontFamily: {
      'gilroy': ['Gilroy'],
    },
    fontWeight: {
      'gilroy-regular': 400,
      'gilroy-medium': 500,
      'gilroy-semibold': 600,
    }
  },

  plugins: [
    require('preline/plugin'),
  ],
};
