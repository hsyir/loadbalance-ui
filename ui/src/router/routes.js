import { createRouter, createMemoryHistory } from 'vue-router'

// //public
import Homepage from '../pages/index.vue';

import About from '../pages/about.vue';

const baseRoutes = [
  {
    path: '/',
    component: Homepage,
    name: "home",
  },

  {
    path: '/',
    component: About,
    name: "about",
  },

];

const routes = baseRoutes

export default function (history) {
  return createRouter({
    history: createMemoryHistory(),
    routes,
  })
}