import {
    fontFamily as _fontFamily,
    screens as _screens,
} from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export const content = [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.vue",
];
export const theme = {
    extend: {
        fontFamily: {
            sans: ["Nunito", ..._fontFamily.sans],
        },
    },
    screens: {
        xs: "512px",
        ..._screens,
    },
};
export const plugins = [require("@tailwindcss/forms")];
