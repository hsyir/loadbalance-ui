<template>
  <v-app>
    <v-locale-provider app :rtl="rtl">
      <v-app-bar color="primary">
        <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
        {{ $t("Simotel Load Balance System") }}
        <v-spacer />
        <v-btn @click="changeLanguage">{{
          currentLang == "fa" ? "English" : "فارسی"
        }}</v-btn>
      </v-app-bar>
      <v-navigation-drawer v-model="drawer" temporary>
        <v-list>
          <v-list-item to="/">{{ $t("Dashboard") }}</v-list-item>
          <v-list-item to="/rules">{{ $t("Rules") }}</v-list-item>
        </v-list>
      </v-navigation-drawer>
      <v-main>
        <v-container>
          <router-view />
        </v-container>
        <v-container>
          <v-row>
            <v-col>
              <app-footer></app-footer>
            </v-col>
          </v-row>
        </v-container>
      </v-main>
      <notifications position="bottom center" class="mb-10" :duration="5" />
    </v-locale-provider>
  </v-app>
</template>

<script>
import AppFooter from "./components/AppFooter.vue";
export default {
  provide() {
    return {
      page_title: "",
      backend_base_url: "http://127.0.0.1:8000/api/loadbalance",
    };
  },
  data() {
    return {
      rtl: false,
      drawer: false,
    };
  },
  computed: {
    currentLang() {
      return this.$i18n.locale;
    },
  },
  methods: {
    changeLanguage() {
      const savedLocale = localStorage.getItem("locale") || "fa";
      const lang = savedLocale == "en" ? "fa" : "en";
      this.$i18n.locale = lang;
      localStorage.setItem("locale", lang); // ذخیره زبان انتخابی
      this.rtl = lang == "fa";
    },
  },
  mounted() {
    const savedLocale = localStorage.getItem("locale") || "fa";
    this.rtl = savedLocale == "fa";
  },
};
</script>
