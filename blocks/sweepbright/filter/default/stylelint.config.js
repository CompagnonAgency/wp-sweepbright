module.exports = {
  customSyntax: "postcss-html",
  extends: [
    "stylelint-config-standard",
    "stylelint-config-recommended-vue",
    "stylelint-config-prettier",
  ],
  // add your custom config here
  // https://stylelint.io/user-guide/configuration
  rules: {
    "selector-class-pattern": "^[a-z][a-zA-Z0-9_-]+$",
    "selector-id-pattern": "^[a-z][a-zA-Z0-9_-]+$",
  },
};
