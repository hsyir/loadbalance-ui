<template>
  <v-container>
    <v-row class="border rounded-lg pa-10">
      <v-col>
        <v-sheet rounded="xl" class="mb-2">
          <rule-form :rule="rule" :errors="errors"></rule-form>
        </v-sheet>
        <v-sheet rounded="xl" class="mb-4">
          <Outputs :outputs="outputs" :errors="errors"></Outputs>
        </v-sheet>

        <v-btn @click="createRule(false)" :loading="loading" color="primary" rounded class="me-1">{{ $t("Create")
          }}</v-btn>
        <v-btn to="/rules" rounded color="secondary">{{ $t("Back") }}</v-btn>

      </v-col>
    </v-row>
    <v-dialog v-model="overlapModal" width="auto">
      <v-card>
        <v-card-title>{{ $t("Overlaped") }}!</v-card-title>
        <v-card-text>
          <v-alert color="red" variant="outlined" border="top" type="warning" class="mb-10">
            {{
              $t(
                "The selected time range overlaps with another scheduled program."
              )
            }}
          </v-alert>
          <v-table>
            <thead>
              <tr>
                <th>{{ $t("Line Name") }}</th>
                <th>{{ $t("Time From") }}</th>
                <th>{{ $t("Time To") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="r in overlap_rules" :key="r">
                <td>{{ r.line_name }}</td>
                <td>{{ r.time_from }}</td>
                <td>{{ r.time_to }}</td>
              </tr>
            </tbody>
          </v-table>
          <hr class="my-3" />
          <v-btn color="primary" rounded @click="forceSubmit" class="me-2">{{
            $t("Force Submit")
          }}</v-btn>
          <v-btn color="secondary" rounded @click="overlapModal = false">{{
            $t("Close")
          }}</v-btn>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import axios from "axios";
import RuleForm from "./Form.vue";
import Outputs from "./Outputs.vue";

export default {
  components: { RuleForm, Outputs },
  inject: ["backend_base_url"],
  data() {
    return {
      rule: {},
      rule_id: null,
      time_to: null,
      overlap_rules: [],
      overlapModal: false,
      loading: false,
      outputs: [],
      errors: {},
    };
  },
  methods: {
    validation() {
      let errors = {};
      let hasError = false;
      if (!this.rule.line_name) {
        hasError = true;
        errors['line_name'] = this.$t("Line name is required")
      }

      if (!this.rule.days || this.rule.days.length < 1) {
        hasError = true;
        errors['days'] = this.$t("Select one or more days of week")
      }

      if (!this.outputs || this.outputs.length < 1) {
        hasError = true;
        errors['outputs'] = this.$t("Add one or more outputs")
      } else if (this.outputs.reduce(function (a, b) {
        return +a + +b.percent;
      }, 0) != 100) {
        hasError = true;
        errors['outputs'] = this.$t("Total value of output percents must be equal to 100")
      }

      if (!this.rule.time_from) {
        hasError = true;
        errors['time_from'] = this.$t("Start time  is required")
      }

      if (!this.rule.time_to) {
        hasError = true;
        errors['time_to'] = this.$t("End time is required")
      }

      this.errors = errors;
      return !hasError;
    },
    forceSubmit() {
      this.createRule(true);
    },
    async createRule(forceOnOverlap) {

      if (!this.validation()) return;

      this.loading = true;
      this.rule.force_on_overlap = forceOnOverlap;

      axios.post(
        this.backend_base_url + "/rules",
        { ...this.rule, outputs: this.outputs }
      )
        .then(res => {
          this.$notify({
            type: "success",
            duration: 2000,
            text: "Data has been stored SUCCESSFULLY!",
          });

          this.$router.push("/rules");
        })
        .catch(err => {
          if (err.response.data.has_overlap) {
            this.overlap_rules = err.response.data.overlap_rules;
            this.overlapModal = true;
          }
        })
        .then(() => {
          this.loading = false;
        })


    },
  },
  computed: {},
  watch: {},
  mounted() {
    // this.getRules();
  },
};
</script>
