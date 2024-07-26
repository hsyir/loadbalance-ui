<template>
  <v-app>
    <v-app-bar>
      <v-app-bar-nav-icon></v-app-bar-nav-icon>
    </v-app-bar>
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
  </v-app>
</template>

<script setup>
import AppFooter from './components/AppFooter.vue';
//
// import { VApp, VMain, VAppBar, VAppBarNavIcon } from 'vuetify/components';

</script>
