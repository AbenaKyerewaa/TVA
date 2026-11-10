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
            colors: {
                gold: {
                    50: '#faf7f0',
                    100: '#f3ecdc',
                    200: '#e6d6b8',
                    300: '#d7bc8e',
                    400: '#c8a268',
                    500: '#b98c4a',
                    600: '#a3743c',
                    700: '#835733',
                    800: '#6c462e',
                    900: '#593a28',
                    950: '#321e13',
                },
                noir: {
                    50: '#f6f6f6',
                    100: '#e7e7e7',
                    200: '#d1d1d1',
                    300: '#b0b0b0',
                    400: '#888888',
                    500: '#555555',
                    600: '#333333',
                    700: '#262626',
                    800: '#1a1a1a',
                    900: '#111111',
                    950: '#080808',
                },
                ivory: {
                    50: '#fdfcfb',
                    100: '#faf7f2',
                    200: '#f4ede2',
                    300: '#e8dcce',
                },
            },
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'Inter', ...defaultTheme.fontFamily.sans],
                serif: ['Cormorant Garamond', 'Playfair Display', 'Georgia', 'serif'],
            },
            letterSpacing: {
                ultra: '0.25em',
                prestige: '0.35em',
            },
        },
    },

    plugins: [forms],
};
