<template>
  <v-app>
    <v-app-bar>
      <v-app-bar-nav-icon></v-app-bar-nav-icon>
      <v-btn href="/" type="outlined">Back to Simotel</v-btn>
    </v-app-bar>
    <v-main>
      <v-container>
        <v-row class="mb-5">
          <v-col cols="12">
            <h1>Simotel Load Balance System</h1>
          </v-col>
        </v-row>
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
  </v-app>
</template>

<script>
import AppFooter from './components/AppFooter.vue';
export default {
  provide() {
    return { page_title: '' }
  },
}
//
// import { VApp, VMain, VAppBar, VAppBarNavIcon } from 'vuetify/components';

</script>
