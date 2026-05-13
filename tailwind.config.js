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
                forest: {
                    50:  '#f0f7f4',
                    100: '#dcede5',
                    200: '#bcdacc',
                    300: '#8fc0a8',
                    400: '#5ea080',
                    500: '#3d8463',
                    600: '#2d6a4f',
                    700: '#245840',
                    800: '#1e4734',
                    900: '#1a3c2c',
                },
            },
            backgroundColor: {
                'page': '#f5f4ef',
            },
        },
    },

    plugins: [forms],
};
