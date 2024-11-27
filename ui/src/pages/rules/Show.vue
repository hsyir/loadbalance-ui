<template>
  <v-container>
    <v-row class="border rounded-lg pa-10">
      <v-col>
        <div>
          <strong>{{ $t("Line Name") }}:</strong> {{ rule.line_name }}
        </div>
        <div>
          <strong>{{ $t("Day Of Week") }}:</strong>
          {{
            [
              $t("Saturday"),
              $t("Sunday"),
              $t("Monday"),
              $t("Tuesday"),
              $t("Wednesday"),
              $t("Thursday"),
              $t("Friday"),
            ][rule.day_of_week]
          }}
        </div>
        <div>
          <strong>{{ $t("Time From") }}:</strong> {{ rule.time_from }}
        </div>
        <div>
          <strong>{{ $t("Time To") }}:</strong> {{ rule.time_to }}
        </div>
        <div>
          <strong>{{ $t("Check Sum") }}:</strong> {{ percents_sum }}
        </div>

        <div class="my-3">
          <v-table density="compact" class="rounded-xl">
            <thead>
              <tr>
                <th>{{ $t("Output Title") }}</th>
                <th>{{ $t("Percent") }} (%)</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td>
                  <v-text-field
                    class="my-1"
                    v-model="new_output.name"
                    variant="outlined"
                    density="compact"
                    rounded
                  />
                </td>
                <td>
                  <v-text-field
                    v-if="false"
                    variant="outlined"
                    density="compact"
                    rounded
                    type="number"
                    max="100"
                    v-model="new_output.percent"
                  />
                  <v-slider
                    v-model="new_output.percent"
                    step="5"
                    min="0"
                    max="100"
                  />
                </td>
                <td>
                  {{ new_output.percent }}
                </td>
                <td align="center">
                  <v-btn
                    rounded
                    size="small"
                    color="blue"
                    variant="flat"
                    @click="insertOutput"
                    :disabled="!new_output.name || !new_output.percent"
                    >{{ $t("Insert") }}</v-btn
                  >
                </td>
              </tr>
            </tfoot>
            <tbody>
              <tr v-for="(output, i) in rule.outputs" :key="output">
                <td>{{ output.name }}</td>
                <td>
                  <v-slider
                    v-model="output.percent"
                    step="5"
                    min="0"
                    max="100"
                    density="compact"
                  />
                </td>
                <td>{{ output.percent }}</td>
                <td align="center">
                  <v-btn @click="rule.outputs.splice(i, 1)" size="x-small" rounded color="red">{{
                    $t("Delete")
                  }}</v-btn>
                </td>
              </tr>
            </tbody>
          </v-table>
        </div>

        <hr class="my-2" />

        <v-btn
          @click="submitRuleOutputs"
          :disabled="percents_sum != 100"
          rounded
          color="success"
          class="me-2"
          >{{ $t("Save") }}</v-btn
        >
        <v-btn to="/rules" rounded color="secondary">{{ $t("Back") }}</v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from "axios";
import RuleForm from "./Form.vue";

export default {
  components: { RuleForm },
  props: ["rule_id"],
  inject: ["backend_base_url"],
  data() {
    return {
      rule: {},
      time_to: null,
      new_output: {},
      percentFocused: false,
      percents_sum: null,
    };
  },
  watch: {
    rule: {
      deep: true,
      handler(newVal) {
        this.percents_sum = newVal.outputs.reduce(function (a, b) {
          return +a + +b.percent;
        }, 0);
      },
    },
  },
  methods: {
    async getRule() {
      this.loading = true;
      const { data } = await axios.get(
        this.backend_base_url + "/rules/" + this.rule_id
      );
      console.log(data);
      this.rule = data.data;
    },
    insertOutput() {
      this.rule.outputs.push({
        name: this.new_output.name,
        percent: this.new_output.percent,
      });
      this.new_output = {};
      this.percentFocused = false;
    },
    async submitRuleOutputs() {
      const { data } = await axios.post(
        this.backend_base_url + "/rules/" + this.rule_id + "/outputs",
        { outputs: this.rule.outputs }
      );
      console.log(data);
    },
  },
  computed: {},
  mounted() {
    this.getRule();
  },
};
</script>
