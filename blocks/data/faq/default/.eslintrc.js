module.exports = {
  root: true,
  extends: ["airbnb-base"],
  parserOptions: {
    parser: "babel-eslint",
    sourceType: "module",
  },
  settings: {
    "html/indent": "+2",
    "html/report-bad-indent": "error",
  },
  env: {
    browser: true,
    node: true,
    es6: true,
  },
  rules: {
    "no-multiple-empty-lines": [
      "error",
      {
        max: 1,
        maxBOF: 1,
      },
    ],
    "comma-dangle": [1, {
      objects: "always",
      arrays: "ignore",
      imports: "ignore",
      exports: "ignore",
      functions: "ignore",
    }],
    "max-len": "off",
    quotes: ["error", "double"],
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
    "valid-jsdoc": ["error", {
      requireReturn: true,
      requireReturnType: true,
      requireParamDescription: true,
      requireReturnDescription: true,
    }],
    "import/extensions": 0,
    "require-jsdoc": ["error", {
      require: {
        FunctionDeclaration: true,
        MethodDefinition: true,
        ClassDeclaration: true,
      },
    }],
  },
};
