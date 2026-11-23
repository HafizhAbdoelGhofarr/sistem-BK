import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

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
                brand: {
                    50: '#FAF5FF',
                    100: '#F3E8FF',
                    200: '#E9D8FD',
                    300: '#D6BCFA',
                    400: '#B794F4',
                    500: '#9F7AEA',
                    600: '#805AD5',
                    700: '#6B46C1',
                    800: '#553C9A',
                    900: '#44337A',
                },
            },
        },
    },

    plugins: [forms],
};
