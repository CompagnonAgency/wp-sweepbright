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
      gray: {
        50: '#F4F4F4',
        100: '#F1F1F1',
        200: '#C7C7C7',
        300: '#A5A5A5',
        400: '#616161',
        500: '#1D1D1D',
        600: '#1A1A1A',
        700: '#111111',
        800: '#0D0D0D',
        900: '#090909',
      },
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
    enabled: process.env.NODE_ENV === 'production',
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
