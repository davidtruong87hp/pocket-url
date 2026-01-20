import tailwindcss from '@tailwindcss/vite'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  ssr: false,
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  css: ['~/assets/css/main.css'],

  fonts: {
    families: [
      {
        name: 'Outfit',
        provider: 'google',
        weights: [400, 500, 600, 700, 800],
      },
    ],
  },

  // App config
  app: {
    head: {
      title: 'Pocket URL - URL Shortener for your needs',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'Modern URL Shortener Platform' },
      ],
      link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
    },
  },

  vite: {
    plugins: [tailwindcss()],
  },

  modules: [
    '@nuxt/image',
    '@nuxt/fonts',
    '@nuxtjs/color-mode',
    '@nuxt/icon',
    'nuxt-auth-sanctum',
  ],

  colorMode: {
    classSuffix: '',
    preference: 'system',
    fallback: 'light',
  },

  components: [
    {
      path: '~/components',
      pathPrefix: false,
    },
  ],

  nitro: {
    devProxy: {
      '/gateway': {
        target: import.meta.env.NUXT_PUBLIC_API_GATEWAY_URL,
        changeOrigin: true,
      },
    },
  },

  sanctum: {
    mode: 'token',
    baseUrl: 'http://localhost:8888/gateway',
    origin: 'http://localhost:8888',
    endpoints: {
      csrf: '/sanctum/csrf-cookie',
      login: '/api/login',
      logout: '/api/logout',
      user: '/api/profile',
    },
    userStateKey: 'sanctum.user.identity',
    client: {
      retry: false,
    },
    redirect: {
      keepRequestedRoute: false,
      onLogin: '/dashboard',
      onLogout: '/',
      onAuthOnly: '/sign-in',
      onGuestOnly: '/dashboard',
    },
    csrf: {
      cookie: 'XSRF-TOKEN',
      header: 'X-XSRF-TOKEN',
    },
  },
})
