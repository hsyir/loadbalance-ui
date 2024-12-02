/**
 * plugins/vuetify.js
 *
 * Framework documentation: https://vuetifyjs.com`
 */

// Styles
import '@mdi/font/scss/materialdesignicons.scss'
import 'vuetify/styles'

import customThemes from "./vuetifyThemes"

// Composables
import { createVuetify } from 'vuetify'



// https://vuetifyjs.com/en/introduction/why-vuetify/#feature-guides
export default createVuetify({
  theme: {
    defaultTheme: "light",
    themes: customThemes
  },
})

