<template>
  <div>
    <h1>Simotel Loadbalance System </h1>
    <div>salam
      {{ ll }}
      <v-select :items="linesArray" v-model="selected_line"></v-select>
      <v-select :items="['w1', 'w2']" v-model="selected_week"></v-select>
      <v-select :items="tcs" v-model="selected_tc"></v-select>
    </div>
    <div v-if="selected_line != ''">
      <table>
        <tr v-for="item in allLines[selected_line]['outputs_count']">
          <td>output-{{ item }}</td>
          <td><v-text-field variant="outlined" color="primary" compact="density" v-model="output_percents[item - 1]"  />
            <v-slider v-model="output_percents[item - 1]"  step="1" color="primary"/>
          </td>
        </tr>
      </table>
    </div>
    {{ output_percents }}
    <hr>
    {{ percents_sum }}
    <hr>
    {{ selected_line + '-' + selected_week + '-' + selected_tc }}
  </div>
</template>

<script>

import lines from '../simotelSmartApps'


export default {
  data() {
    return {
      linesArray: [],
      selected_line: '',
      selected_week: 'w1',
      selected_tc: '',
      output_percents: [],
      percents_sum: 0,
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

  },
  watch: {
    output_percents: {
      deep: true,
      handler(newVal) {
        this.percents_sum = newVal.reduce((a, b) => +a + +b, 0)
      }
    }
  },
  mounted() {
    // console.log(lines.getLine('line1'));
  }
}
</script>
