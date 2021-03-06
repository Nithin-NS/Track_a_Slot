module.exports = {
  purge: [],
  purge: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      backgroundImage: theme => ({
        'hero-pattern': "url('/img/hero.jpg')",
       })
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
