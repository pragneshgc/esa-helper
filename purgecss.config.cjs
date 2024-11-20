module.exports = {
    content: [
        './dist/*.js'
    ],
    css: ['./dist/*.css'],
    safelist: {
        deep: [/dropdown-menu$/]
    },
    output: "./dist/style.min.css"
};