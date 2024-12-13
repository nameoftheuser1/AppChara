import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                agu: ["Agu Display", "sans-serif"],
            },
            animation: {
                spin: "spin 1s linear infinite", // Define the animation
            },
            keyframes: {
                spin: {
                    "100%": { transform: "rotate(360deg)" }, // Define keyframe for the animation
                },
            },
        },
    },
    plugins: [],
};
