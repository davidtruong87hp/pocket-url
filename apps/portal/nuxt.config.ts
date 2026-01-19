import tailwindcss from '@tailwindcss/vite'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
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

  modules: ['@nuxt/image', '@nuxt/fonts'],

  components: [
    {
      path: '~/components',
      pathPrefix: false,
    },
  ],
})
