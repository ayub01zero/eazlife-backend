const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],
    darkMode: "class",
    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                danger: colors.rose,
                primary: {
                    100: "#ff6700",
                    200: "#ff6700",
                    300: "#ff6700",
                    400: "#ff6700",
                    500: "#ff6700",
                    600: "#ff6700",
                    700: "#ff6700",
                    800: "#ff6700",
                    900: "#ff6700",
                },
                success: colors.green,
                warning: colors.yellow,
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
