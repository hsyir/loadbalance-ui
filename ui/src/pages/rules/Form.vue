<template>
  <v-container>
    <v-row class=" px-1">
      <v-col>
        <v-text-field rounded variant="outlined" density="compact" :label="$t('Line Name')" v-model="rule.line_name"
          :error-messages="errors.line_name" />

      </v-col>
      <v-col>
        <v-select rounded multiple chips closable-chips clearable :items="[
          { name: $t('Saturday'), value: '0' },
          { name: $t('Sunday'), value: '1' },
          { name: $t('Monday'), value: '2' },
          { name: $t('Tuesday'), value: '3' },
          { name: $t('Wednesday'), value: '4' },
          { name: $t('Thursday'), value: '5' },
          { name: $t('Friday'), value: '6' },
        ]" item-title="name" item-value="value" variant="outlined" :label="$t('Day Of Week')" density="compact"
          v-model="rule.days" :error-messages="errors.days"></v-select>
      </v-col>
    </v-row>

    <v-row class="">
      <v-col cols="6">
        <v-text-field rounded v-model="rule.time_from" :active="modal_from" :focused="modal_from" variant="outlined"
          density="compact" :label="$t('Time From')" prepend-icon="mdi-clock-time-four-outline" readonly
          :error-messages="errors.time_from">
          <v-dialog v-model="modal_from" activator="parent" width="auto">
            <v-time-picker v-if="modal_from" v-model="rule.time_from" :max="rule.time_to" format="24hr">

              <template v-slot:actions><v-btn @click="modal_from = null">Ok</v-btn>
              </template></v-time-picker>
          </v-dialog> </v-text-field>

        <v-text-field rounded v-model="rule.time_to" :active="modal_to" variant="outlined" density="compact"
          :focused="modal_to" :label="$t('Time To')" prepend-icon="mdi-clock-time-four-outline" readonly
          :error-messages="errors.time_to">
          <v-dialog v-model="modal_to" activator="parent" width="auto">
            <v-time-picker v-if="modal_to" v-model="rule.time_to" :min="rule.time_from" format="24hr">
              <template v-slot:actions><v-btn @click="modal_to = null">Ok</v-btn>
              </template>
            </v-time-picker>
          </v-dialog>
        </v-text-field>
      </v-col>
      <v-col cols="6">
        <v-alert rounded="xl" type="info" variant="outlined">
          <ul>
            <li>
              در قسمت نام مسیر، عنوان smartApi را وارد کنید
            </li>
            <li>
              در صورتی که مسیر مورد نظر پیدا نشود، از خروجی default خارج خواهد شد
            </li>
          </ul>
        </v-alert>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from "axios";
import { VTimePicker } from "vuetify/labs/VTimePicker";

export default {
  components: { VTimePicker },
  props: ["rule", "errors"],
  data() {
    return {
      modal_from: null,
      modal_to: null,
    };
  },
  methods: {},
};
</script>
