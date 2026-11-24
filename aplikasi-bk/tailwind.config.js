import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

const brandColors = {
    50: '#F3FFF5',
    100: '#DDF7E2',
    200: '#C2EDCC',
    300: '#9FE0B0',
    400: '#7ACD91',
    500: '#55B971',
    600: '#218A63',
    700: '#17736D',
    800: '#155C63',
    900: '#144954',
    950: '#103743',
};

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: brandColors,
                purple: brandColors,
                indigo: brandColors,
            },
        },
    },

    plugins: [forms],
};
