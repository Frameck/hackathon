const colors = require('tailwindcss/colors');

module.exports = {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './vendor/filament/**/*.blade.php',
        './app/Filament/**/*.php',
    ],
    theme: {
        container: {
            center: true,
        },
        extend: {
            colors: {
                // primary: colors.amber,
                primary: {
                    50: 'rgb(var(--theme-primary-color-var-50) / <alpha-value>)',
                    100: 'rgb(var(--theme-primary-color-var-100) / <alpha-value>)',
                    200: 'rgb(var(--theme-primary-color-var-200) / <alpha-value>)',
                    300: 'rgb(var(--theme-primary-color-var-300) / <alpha-value>)',
                    400: 'rgb(var(--theme-primary-color-var-400) / <alpha-value>)',
                    500: 'rgb(var(--theme-primary-color-var-500) / <alpha-value>)',
                    600: 'rgb(var(--theme-primary-color-var-600) / <alpha-value>)',
                    700: 'rgb(var(--theme-primary-color-var-700) / <alpha-value>)',
                    800: 'rgb(var(--theme-primary-color-var-800) / <alpha-value>)',
                    900: 'rgb(var(--theme-primary-color-var-900) / <alpha-value>)',
                },
                secondary: {
                    50: 'rgb(var(--theme-secondary-color-var-50) / <alpha-value>)',
                    100: 'rgb(var(--theme-secondary-color-var-100) / <alpha-value>)',
                    200: 'rgb(var(--theme-secondary-color-var-200) / <alpha-value>)',
                    300: 'rgb(var(--theme-secondary-color-var-300) / <alpha-value>)',
                    400: 'rgb(var(--theme-secondary-color-var-400) / <alpha-value>)',
                    500: 'rgb(var(--theme-secondary-color-var-500) / <alpha-value>)',
                    600: 'rgb(var(--theme-secondary-color-var-600) / <alpha-value>)',
                    700: 'rgb(var(--theme-secondary-color-var-700) / <alpha-value>)',
                    800: 'rgb(var(--theme-secondary-color-var-800) / <alpha-value>)',
                    900: 'rgb(var(--theme-secondary-color-var-900) / <alpha-value>)',
                },
                accent: {
                    50: 'rgb(var(--theme-accent-color-var-50) / <alpha-value>)',
                    100: 'rgb(var(--theme-accent-color-var-100) / <alpha-value>)',
                    200: 'rgb(var(--theme-accent-color-var-200) / <alpha-value>)',
                    300: 'rgb(var(--theme-accent-color-var-300) / <alpha-value>)',
                    400: 'rgb(var(--theme-accent-color-var-400) / <alpha-value>)',
                    500: 'rgb(var(--theme-accent-color-var-500) / <alpha-value>)',
                    600: 'rgb(var(--theme-accent-color-var-600) / <alpha-value>)',
                    700: 'rgb(var(--theme-accent-color-var-700) / <alpha-value>)',
                    800: 'rgb(var(--theme-accent-color-var-800) / <alpha-value>)',
                    900: 'rgb(var(--theme-accent-color-var-900) / <alpha-value>)',
                },
                success: colors.green,
                warning: colors.yellow,
                danger: colors.rose,
                // blue: colors.blue,
                // sky: colors.sky
            },
        },
    },
    plugins: [
		require('@tailwindcss/forms'), 
        require('@tailwindcss/typography'),
    ]
};
