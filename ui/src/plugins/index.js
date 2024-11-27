/**
 * plugins/index.js
 *
 * Automatically included in `./src/main.js`
 */

// Plugins
import vuetify from './vuetify'
import router from '@/router'
import i18n from "./i18n"
import Notifications from '@kyvg/vue3-notification'


export function registerPlugins(app) {
  app
    .use(i18n)
    .use(vuetify)
    .use(router)
    .use(Notifications)
}
