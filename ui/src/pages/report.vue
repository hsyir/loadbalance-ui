<template>
  <v-container>
  </v-container>
</template>

<script>

import lines from '../simotelSmartApps'
import axios from 'axios'

export default {
  inject:['page_title'],
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
    this.page_title = "salam"
  }
}
</script>
