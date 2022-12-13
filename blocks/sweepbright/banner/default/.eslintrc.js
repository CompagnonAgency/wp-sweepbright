module.exports = {
  root: true,
  env: {
    browser: true,
    node: true,
  },
  parserOptions: {
    ecmaVersion: 6,
    parser: "@babel/eslint-parser",
    requireConfigFile: false,
    sourceType: "module",
  },
  plugins: ["vue"],
  extends: ["prettier"],
  // add your custom rules here
  rules: {
    "vue/script-indent": ["error"],
    "vue/multi-word-component-names": "off",
    "vue/script-setup-uses-vars": "error",
    "vue/html-indent": ["error"],
    "vue/html-end-tags": ["error"],
    "vue/first-attribute-linebreak": ["error"],
    "vue/html-closing-bracket-newline": ["error"],
    "vue/no-v-html": "off",
    "vue/max-attributes-per-line": [
      "error",
      {
        "singleline": {
          "max": 1,
        },
        "multiline": {
          "max": 1,
        },
      }
    ],
    "no-multiple-empty-lines": [
      "error",
      {
        "max": 1,
        "maxBOF": 1,
      }
    ],
    "comma-dangle": [
      1,
      {
        "objects": "always",
        "arrays": "ignore",
        "imports": "ignore",
        "exports": "ignore",
        "functions": "ignore",
      }
    ],
    "no-lonely-if": 0,
    "max-len": "off",
    "quotes": ["error", "double"],
    "no-restricted-syntax": 0,
    "o-absolute-path": 0,
    "no-new": 0,
    "no-console": 0,
    "no-bitwise": 0,
    "no-useless-escape": 0,
    "no-underscore-dangle": 0,
    "global-require": 0,
    "import/no-unresolved": 0,
    "no-param-reassign": 0,
    "no-shadow": 0,
    "valid-jsdoc": [
      "error",
      {
        "requireReturn": true,
        "requireReturnType": true,
        "requireParamDescription": true,
        "requireReturnDescription": true,
      }
    ],
    "import/extensions": 0,
    "require-jsdoc": "off",
    "class-methods-use-this": 0,
    "semi": ["error", "always"],
    "import/no-named-as-default": "off",
    "object-curly-spacing": ["error"],
    "comma-spacing": ["error", {"before": false, "after": true,}],
    "space-in-parens": ["error", "never"],
    "array-bracket-spacing": ["error", "never"],
    "computed-property-spacing": ["error", "never"],
    "func-call-spacing": ["error", "never"],
    "space-before-function-paren": ["error", "never"],
    "keyword-spacing": ["error", {"after": true,}],
    "space-before-blocks": ["error"],
    "no-whitespace-before-property": ["error"],
    "no-trailing-spaces": 2,
    "indent": ["error", 2, {SwitchCase: 1,}],
  },
};
