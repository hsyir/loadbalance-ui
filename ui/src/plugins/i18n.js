import { createI18n } from 'vue-i18n';

// فایل‌های ترجمه را وارد کنید
import en from './../locales/en.json';
import fa from './../locales/fa.json';

const savedLocale = localStorage.getItem('locale') || 'fa';

export default createI18n({
  locale: savedLocale, // زبان پیش‌فرض
  fallbackLocale: 'en', // زبان پشتیبان
  messages: {
    en,
    fa,
  },
});
