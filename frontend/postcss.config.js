module.exports = {
    plugins: {
      '@tailwindcss/postcss': {
        // يمكنك إضافة إعدادات Tailwind هنا
        content: [
          "./src/**/*.{js,jsx,ts,tsx}",
          "./public/index.html"
        ],
        theme: {
          extend: {},
        },
        plugins: [],
      },
      autoprefixer: {},
    },
  }