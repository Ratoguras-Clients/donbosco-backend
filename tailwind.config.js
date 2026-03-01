import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["poppins", ...defaultTheme.fontFamily.sans],
                Avhaya: ["Abhaya Libre", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: "#2B4DA1",
                "light-primary": "#E9F3FF",
                "text-muted": "#5F5F5F",
                "danger-red": "#EE003F",
                "info-blue": "#008CD9",
                'warning-yellow': '#FC8200',
            },
        },
    },
    variants: {
        extend: {
            backgroundColor: ["active"],
            textColor: ["active"],
        },
    },
    plugins: [require("@tailwindcss/forms"), forms],
    darkMode: "class",
};
