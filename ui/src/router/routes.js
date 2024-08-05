import { createRouter, createMemoryHistory } from 'vue-router'

// //public
import Homepage from '../pages/index.vue';

import Report from '../pages/report.vue';

const baseRoutes = [
  {
    path: '/',
    component: Homepage,
    name: "home",
  },

  {
    path: '/',
    component: Report,
    name: "report",
  },

];

const routes = baseRoutes

export default function (history) {
  return createRouter({
    history: createMemoryHistory(),
    routes,
  })
}