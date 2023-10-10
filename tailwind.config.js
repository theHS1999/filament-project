/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/filament/**/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
    safelist: ["rounded-tr-xl"],
};
