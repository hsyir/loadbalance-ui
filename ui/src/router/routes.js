import { createRouter, createMemoryHistory } from 'vue-router'

// //public
import Homepage from '../pages/index.vue';

import RulesIndex from '../pages/rules/Index.vue';
import RulesCreate from '../pages/rules/Create.vue';
import RulesShow from '../pages/rules/Show.vue';

const baseRoutes = [
  {
    path: '/',
    component: Homepage,
    name: "home",
  },

  {
    path: '/rules',
    component: RulesIndex,
    name: "rules.index",
  },
  {
    path: '/rules/:rule_id/show',
    component: RulesShow,
    name: "rules.show",
    props: true,
  },
  {
    path: '/rules/create',
    component: RulesCreate,
    name: "rules.create",
  },

];

const routes = baseRoutes

export default function (history) {
  return createRouter({
    history: createMemoryHistory(),
    routes,
  })
}