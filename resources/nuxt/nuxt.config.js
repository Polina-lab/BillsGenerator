const serverUrl = 'https://test3.gloreal.ee'
const serverToListenPush = "https://test3.gloreal.ee"
const serverPort = '6001'

export default {
    target: 'server',
    loading: {
        color: '#26a647',
        failedColor: '#ff0000',
        height: '2px'
    },

    head: {
        title: 'Bills Generator',
        meta: [
            { charset: 'utf-8' },
            { name: 'viewport', content: 'width=device-width, initial-scale=1' },
            { hid: 'description', name: 'description', content: 'Bill Generator' },
            { hid: "keywords", name: 'keywords', content: 'Bills , Generator' },
            { hid: 'og:title', name: 'og:title', content: 'Bill Generator' },
            { hid: 'og:image', property: 'og:image', content: `/icon.png` },
            { hid: 'og:description', property: 'og:description', content: 'Bills  Generator' },
        ],
        link: [
            { rel: "stylesheet", href: "/fonts/css/all.min.css" },
            { rel: "stylesheet", href: "/css/bootstrap.min.css" },
            {
              rel: 'stylesheet',
              href: "https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
            },
            { rel: 'icon', type: 'image/x-icon', href: '/icon.png' },

        ]
    },

    // Global CSS (https://go.nuxtjs.dev/config-css)
    css: [
        '@/assets/css/global_styles.css',
        '@/assets/css/v-tooltips.css'
    ],

    // Plugins to run before rendering page (https://go.nuxtjs.dev/config-plugins)
    plugins: [
    '~/plugins/axios.client',
    '~/plugins/v-mask.client',
    '~/plugins/v-datepicker.client',
    { src: '@/plugins/v-tooltips.js', mode: 'client' },
    { src: '@/plugins/vue-paycard.js', mode: 'client' },
    { src: '@/plugins/vue-touch', ssr: false },
    { src: '@/plugins/vue-country-code.js', mode: 'client' },
    { src: '@/plugins/vue-html2pdf', mode: 'client' },
    { src: '@/plugins/vue-awesome-swiper', mode: 'client' },
    { src: "@/plugins/v-img" , mode : 'client' }
    ],

    // Auto import components (https://go.nuxtjs.dev/config-components)
    components: true,

    // Modules for dev and build (recommended) (https://go.nuxtjs.dev/config-modules)
    buildModules: [
        '@nuxtjs/laravel-echo',
    ],

    // echo: {
    //     broadcaster: 'socket.io',
    //     authModule: true,
    //     //serveClient: false,
    //     connectOnLogin: true,
    //     disconnectOnLogout: false,
    //     transports: ['websocket', 'polling', 'flashsocket'],
    //     host: `${serverToListenPush}:${serverPort}`,
    //     plugins: ['~/plugins/socket.client'],
    // },

    // Modules (https://go.nuxtjs.dev/config-modules)
    modules: [
        '@nuxtjs/axios',
        '@nuxtjs/pwa',
        '@nuxtjs/auth',
        '@nuxtjs/toast',
        'nuxt-i18n',
        'nuxt-vue-multiselect', ['cookie-universal-nuxt', { alias: 'cookies' }],
    ],
    axios: {
        baseUrl: serverUrl,
    },
    i18n: {
        locales: [{
                id: 1,
                name: "EST",
                code: 'ee',
                file: 'ee.json'
            },
            {
                id: 2,
                name: "ENG",
                code: 'en',
                file: 'en.json'
            },
            {
                id: 3,
                name: "RUS",
                code: 'ru',
                file: 'ru.json'
            }
        ],
        defaultLocale: 'en',
        fallbackLocale: 'en',
        seo: true,
        //lazy: true,
        strategy: 'no_prefix',
        langDir: 'locales/',
        detectBrowserLanguage: {
            useCookie: true,
            cookieKey: 'pma_lang',
            redirectOn: 'root',
            alwaysRedirect: true,
        },
    },


    auth: {
        redirect: {
            logout: '/login'
        },
        strategies: {
            local: {
                autoFetchUser: true,
                /*
                token: {
                  property: 'token',
                  // required: true,
                  type: 'Bearer'
                },
                 */
                user: {
                    property: 'user',
                    // autoFetch: true
                },

                endpoints: {
                    login: { url: `${serverUrl}/api/login`, method: 'post', propertyName: 'token' },
                    register: { url: `${serverUrl}/api/register`, method: 'post', },
                    logout: { url: `${serverUrl}/api/admin/logout`, method: 'post' },
                    user: { url: `${serverUrl}/api/admin/user`, method: 'get', propertyName: 'user' }
                },
                tokenRequired: true,
                tokenType: "Bearer"
            }
        },
        localStorage: false
    },
    toast: {
        position: 'top-right'
    },

    render: {
      bundleRenderer: {
        shouldPreload: (file, type) => {
          return ['script', 'style', 'font'].includes(type)
        }
      }
    },

    // Build Configuration (https://go.nuxtjs.dev/config-build)
    build: {
        extend(config, ctx) {
            config.module.rules.push({
                test: /\.i18n$/,
                loader: `@intlify/vue-i18n-loader`,
                options: {
                    forceStringify: true
                }
            })
        },
        transpile: ['@nuxtjs/auth']
    },
    env: {
        serverUrl: serverUrl,
        isDev: process.env.NODE_ENV !== 'production'
    }
}
