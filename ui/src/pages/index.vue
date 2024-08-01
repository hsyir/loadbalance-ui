<template>
  <v-container>
    <v-row>
      <v-col cols=" 12">
        <h1 class="mb-5">Simotel Load Balance System </h1>
      </v-col>
    </v-row>
    <v-row class="border rounded-lg pa-10">
      <v-col cols="6">
        <v-select :items="linesArray" v-model="selected_line" variant="outlined" color="primary" label="Selected Line"
          density="compact"></v-select>
        <v-select :items="['w1', 'w2']" v-model="selected_week" variant="outlined" color="primary" label="Days of week"
          density="compact"></v-select>
        <v-select :items="tcs" v-model="selected_tc" variant="outlined" color="primary" density="compact"
          label="Selected Times"></v-select>
      </v-col>
      <v-col cols="6">
        <v-slide-y-transition>
          <v-row v-if="selected_line != ''">
            <v-col cols="12" sm="6" md="4" lg="3" v-for="item in allLines[selected_line]['outputs_count']">
              <v-text-field :label="'output-' + item" class="border-0 mb-n2"
                :color="output_percents[item - 1] < 0 || output_percents[item - 1] > 100 ? 'error' : 'primary'"
                density="compact" v-model="output_percents[item - 1]" />
              <v-slider v-model="output_percents[item - 1]" step="5" min="0" max="100"
                :color="output_percents[item - 1] < 0 || output_percents[item - 1] > 100 ? 'error' : 'primary'" />

            </v-col>
            <v-col cols="12">
            </v-col>
          </v-row>
        </v-slide-y-transition>

      </v-col>
      <v-col cols="12" class=" pa-1 ">
        <div class="">Data Validation: </div>
        <ul class="pa-5">
          <li class="d-inline-block me-10" :class="{ 'text-error': !selected_line, 'text-success': selected_line }">
            <strong>Line: {{ selected_line }}</strong>
          </li>
          <li class="d-inline-block me-10" :class="{ 'text-error': !selected_week, 'text-success': selected_week }">
            <strong>Week Days: {{ selected_week }}</strong>
          </li>
          <li class="d-inline-block me-10" :class="{ 'text-error': !selected_tc, 'text-success': selected_tc }">
            <strong>Times: {{ selected_tc }}</strong>
          </li>
          <li class="d-inline-block me-10"
            :class="{ 'text-error': percents_sum != 100, 'text-success': percents_sum == 100 }"><strong>Check Sum: {{
              percents_sum }}</strong></li>
        </ul>
      </v-col>
      <v-col cols="12" class=" pa-1 mt-5 ">

        <v-slide-y-transition leave-absolute hide-on-leave>
          <div v-if="!validation" class="mb-5">
            <v-alert type="error" variant="tonal" border>There is some problem ...</v-alert>
          </div>
        </v-slide-y-transition>

        <div class="text-center  ">
          {{ postData }}
          <v-btn variant="tonal" class="border border-primary border-opacity-100" :loading="loading" color="primary"
            :disabled="!validation" block @click="storeData">Send Data</v-btn>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>

import lines from '../simotelSmartApps'
import axios from 'axios'

export default {
  data() {
    return {
      linesArray: [],
      selected_line: '',
      selected_week: 'w1',
      selected_tc: '',
      output_percents: [],
      percents_sum: 0,
      loading: false,
    }
  },
  methods: {},
  computed: {
    allLines() {
      return lines.getAllLines()
    },
    ll() {
      for (const [key, value] of Object.entries(this.allLines)) {
        this.linesArray.push(key)
      }
    },
    tcs() {
      if (!this.selected_line) return;
      return this.allLines[this.selected_line]['week'][this.selected_week];
    },
    validation() {
      return (
        this.percents_sum == 100
        && (this.output_percents.every(item => item >= 0 && item <= 100))
        && (this.selected_line && this.selected_tc && this.selected_week)
      )
    },
    postData() {
      if (!this.selected_line) return;

      return {
        smartapi: `${this.selected_line}-${this.selected_week}-${this.selected_tc}`,
        out_total: this.allLines[this.selected_line]['outputs_count'],
        out_percent: (() => {
     
          let outputs = {};

          for (let i = 1; i <= this.allLines[this.selected_line]['outputs_count']; i++) {
            outputs['out-' + i] = this.output_percents[i - 1];
          }
          return outputs
     
        })()
      }

    },

  },
  watch: {
    selected_line: {
      deep: true,
      handler(newVal) {
        this.output_percents = [];
        this.selected_tc = "";
        for (let i = 0; i < this.allLines[this.selected_line]['outputs_count']; i++) {
          this.output_percents[i] = 0
        }

      }
    },
    output_percents: {
      deep: true,
      handler(newVal) {
        this.percents_sum = newVal.reduce((a, b) => +a + +b, 0)
      }
    }
  },
  methods: {
    async storeData() {
      this.loading = true;
      const { data } = await axios.post('http://127.0.0.1/tmp/output-percent/post/set-smartapi-percent-post.php', this.postData);
      if (data.status == 1) {
        this.$notify({
          type: 'success',
          text: "Data has been stored SUCCESSFULLY!"
        });

      }
      this.loading = false;
    }
  },
  mounted() {
    // console.log(lines.getLine('line1'));
    this.ll;
  }
}
</script>
