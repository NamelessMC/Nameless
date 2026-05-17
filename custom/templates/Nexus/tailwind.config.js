/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.tpl',
    './src/scripts/**/*.js',
  ],
  darkMode: ['class', '[data-theme="dark"]'],
  theme: {
    extend: {
      colors: {
        bg: {
          base:     'var(--bg-base)',
          surface:  'var(--bg-surface)',
          elevated: 'var(--bg-elevated)',
          inset:    'var(--bg-inset)',
          overlay:  'var(--bg-overlay)',
        },
        border: {
          subtle:  'var(--border-subtle)',
          DEFAULT: 'var(--border-default)',
          strong:  'var(--border-strong)',
        },
        text: {
          primary:   'var(--text-primary)',
          secondary: 'var(--text-secondary)',
          muted:     'var(--text-muted)',
          inverse:   'var(--text-inverse)',
        },
        accent: {
          DEFAULT: 'var(--accent)',
          hover:   'var(--accent-hover)',
          subtle:  'var(--accent-subtle)',
          fg:      'var(--accent-fg)',
        },
        success: 'var(--success)',
        warning: 'var(--warning)',
        danger:  'var(--danger)',
        info:    'var(--info)',
      },
      fontFamily: {
        sans:    ['var(--font-sans)'],
        display: ['var(--font-display)'],
        mono:    ['var(--font-mono)'],
      },
      borderRadius: {
        xs:  'var(--r-xs)',
        sm:  'var(--r-sm)',
        DEFAULT: 'var(--r-md)',
        md:  'var(--r-md)',
        lg:  'var(--r-lg)',
        xl:  'var(--r-xl)',
        '2xl': 'var(--r-2xl)',
      },
      boxShadow: {
        xs:    'var(--shadow-xs)',
        sm:    'var(--shadow-sm)',
        DEFAULT: 'var(--shadow-md)',
        md:    'var(--shadow-md)',
        lg:    'var(--shadow-lg)',
        xl:    'var(--shadow-xl)',
        glow:  'var(--shadow-glow)',
        inset: 'var(--shadow-inset)',
      },
      transitionTimingFunction: {
        'out-quint': 'cubic-bezier(.16,1,.3,1)',
        'in-out-quint': 'cubic-bezier(.65,0,.35,1)',
      },
      transitionDuration: {
        fast: '120ms',
        base: '200ms',
        slow: '320ms',
      },
      backgroundImage: {
        'gradient-mesh':
          'radial-gradient(60% 80% at 50% 0%, var(--accent-subtle) 0%, transparent 60%), radial-gradient(40% 60% at 80% 30%, color-mix(in oklab, var(--info) 35%, transparent) 0%, transparent 70%), radial-gradient(40% 60% at 10% 80%, color-mix(in oklab, var(--accent) 25%, transparent) 0%, transparent 70%)',
        'noise':
          "url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='1' stitchTiles='stitch'/><feColorMatrix type='matrix' values='0 0 0 0 1 0 0 0 0 1 0 0 0 0 1 0 0 0 .04 0'/></filter><rect width='100%' height='100%' filter='url(%23n)'/></svg>\")",
      },
      keyframes: {
        'fade-in': {
          '0%': { opacity: '0', transform: 'translateY(4px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        'scale-in': {
          '0%': { opacity: '0', transform: 'scale(.96)' },
          '100%': { opacity: '1', transform: 'scale(1)' },
        },
        'slide-up': {
          '0%': { opacity: '0', transform: 'translateY(8px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        'shimmer': {
          '0%':   { transform: 'translateX(-100%)' },
          '100%': { transform: 'translateX(100%)' },
        },
        'pulse-glow': {
          '0%, 100%': { boxShadow: '0 0 0 0 var(--accent-glow)' },
          '50%':      { boxShadow: '0 0 0 8px transparent' },
        },
      },
      animation: {
        'fade-in':    'fade-in 200ms cubic-bezier(.16,1,.3,1) both',
        'scale-in':   'scale-in 180ms cubic-bezier(.16,1,.3,1) both',
        'slide-up':   'slide-up 240ms cubic-bezier(.16,1,.3,1) both',
        'shimmer':    'shimmer 1.6s linear infinite',
        'pulse-glow': 'pulse-glow 2s ease-in-out infinite',
      },
      screens: {
        '3xl': '1792px',
      },
      spacing: {
        '4.5': '1.125rem',
        '18':  '4.5rem',
        '22':  '5.5rem',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms')({ strategy: 'class' }),
    require('@tailwindcss/typography'),
  ],
};
