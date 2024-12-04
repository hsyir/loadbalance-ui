<template>
  <v-container>
    <v-row>
      <v-col>
        <div class="my-3">
          <v-table density="compact" class="rounded-xl">
            <thead>
              <tr>
                <th>{{ $t("Output Title") }}</th>
                <th :class="percent_color">{{ $t("Percent") }} ({{ percents_sum }}%)</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td>
                  <v-text-field :error-messages="errors.outputs" class="my-1" v-model="new_output.name" variant="outlined" density="compact" rounded />
                </td>
                <td>
                  <v-text-field v-if="false" variant="outlined" density="compact" rounded type="number" max="100"
                    v-model="new_output.percent" />
                  <v-slider v-model="new_output.percent" step="5" min="0" max="100" />
                </td>
                <td>
                  {{ new_output.percent }}
                </td>
                <td align="center">
                  <v-btn rounded size="small" color="blue" variant="flat" @click="insertOutput"
                    :disabled="!new_output.name || !new_output.percent">{{ $t("Insert") }}</v-btn>
                </td>
              </tr>
            </tfoot>
            <tbody>
              <tr v-if="outputs.length == 0">
                <td colspan="4" class="py-5 text-center">{{ $t("Any output has not been added yet.") }}</td>
              </tr>
              <tr v-for="(output, i) in outputs" :key="output">
                <td>{{ output.name }}</td>
                <td>
                  <v-slider v-model="output.percent" step="5" min="0" max="100" density="compact" />
                </td>
                <td>{{ output.percent }}</td>
                <td align="center">
                  <v-btn @click="outputs.splice(i, 1)" size="x-small" rounded color="red">{{
                    $t("Delete")
                  }}</v-btn>
                </td>
              </tr>
            </tbody>
          </v-table>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>

export default {
  props: ["outputs","errors"],
  data() {
    return {
      new_output: {
        percent: 0,
      },
      percentFocused: false,
      percents_sum: null,
    };
  },
  computed: {
    percent_color() {
      return this.percents_sum == 100 ? "text-green" : (this.percents_sum > 100 ? 'text-red' : "")
    }
  },
  watch: {
    outputs: {
      immediate: true,
      deep: true,
      handler(newVal) {
        this.percents_sum = newVal.reduce(function (a, b) {
          return +a + +b.percent;
        }, 0) + +this.new_output.percent;
      },
    },
    new_output: {
      immediate: true,
      deep: true,
      handler(newVal) {
        this.percents_sum = this.outputs.reduce(function (a, b) {
          return +a + +b.percent;
        }, 0) + +newVal.percent;
      },
    },
  },
  methods: {

    insertOutput() {
      this.outputs.push({
        name: this.new_output.name,
        percent: this.new_output.percent,
      });
      this.new_output = {
        percent: 0,
      };
      this.percentFocused = false;
    },
  },
};
</script>
