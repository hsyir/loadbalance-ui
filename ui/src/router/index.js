import { createRouter, createWebHistory } from 'vue-router';

// تعریف مسیرها
const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../pages/index.vue'), // لود تنبل
  },
  // مسیرهای دیگر
];

// ایجاد و پیکربندی روتر
const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
