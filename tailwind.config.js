/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        screens: {
            sm: "480px",
            md: "768px",
            lg: "976px",
            xl: "1440px",
        },
        extend: {
            colors: {
                tealLight: "#4FFFE0",
                orangeLight: "hsla(36, 100%, 52%, 0.3)",
            },
            fontFamily: {
                "nova-flat": ['"Nova Flat"', "sans-serif"],
            },
        },
    },
    plugins: [],
};
