module.exports = {
  variants: {
    opacity: ['responsive', 'hover', 'focus', 'active', 'group-hover'],
    display: ['responsive', 'hover', 'focus', 'active', 'group-hover'],
    width: ['responsive', 'hover', 'focus', 'active', 'group-hover'],
    backgroundColor: ['responsive', 'hover', 'focus', 'active', 'group-hover'],
    borderWidth: ['responsive', 'last', 'hover', 'focus'],
    padding: ['responsive', 'last', 'hover', 'focus'],
    margin: ['responsive', 'last', 'hover', 'focus'],
  },
  theme: {
    fontFamily: {
      body: [
        'system-ui',
        '-apple-system',
        'BlinkMacSystemFont',
        '"Segoe UI"',
        'Roboto',
        '"Helvetica Neue"',
        'Arial',
        '"Noto Sans"',
        'sans-serif',
      ],
    },
    screens: {
      sm: '640px',
      md: '768px',
      lg: '1024px',
      xl: '1280px',
    },
    extend: {
      opacity: {
        15: '.15',
      },
      minHeight: {
        banner: '600px',
      },
      height: {
        banner: 'calc(100vh - 153px)',
      },
      fontSize: {
        '7xl': '5rem',
        '8xl': '6rem',
        '9xl': '7rem',
        '10xl': '8rem',
        '11xl': '9rem',
      },
    },
  },
  purge: {
    // Learn more on https://tailwindcss.com/docs/controlling-file-size/#removing-unused-css
    enabled: 'production' === process.env.NODE_ENV,
    content: [
      './admin/**/*.php',
      './admin/vue-components/**/*.vue',
      './admin/js/**/*.js',
    ],
    defaultExtractor: (content) => content.match(/[\w-/:]+(?<!:)/g) || [],
    whitelist: ['post', 'wp', 'navigation'],
    whitelistPatterns: [/post/, /wp/, /navigation/],
    whitelistPatternsChildren: [/post/, /wp/, /navigation/],
  },
  plugins: [
    require('@neojp/tailwindcss-aspect-ratio-utilities'),
    require('@fullhuman/postcss-purgecss'),
  ],
};
