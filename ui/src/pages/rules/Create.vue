<template>
  <v-container>
    <v-row class="border rounded-lg pa-10">
      <v-col>
        <rule-form :rule="rule"></rule-form>
        <v-btn
          @click="createRule(false)"
          :loading="loading"
          color="primary"
          rounded
          class="me-1"
          >{{ $t("Create") }}</v-btn
        >
        <v-btn to="/rules" rounded color="secondary">{{ $t("Back") }}</v-btn>
      </v-col>
    </v-row>
    <v-dialog v-model="overlapModal" width="auto">
      <v-card>
        <v-card-title>{{ $t("Overlaped") }}!</v-card-title>
        <v-card-text>
          <v-alert
            color="red"
            variant="outlined"
            border="top"
            type="warning"
            class="mb-10"
          >
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

export default {
  components: { RuleForm },
  inject: ["backend_base_url"],
  data() {
    return {
      rule: {},
      rule_id: null,
      time_to: null,
      overlap_rules: [],
      overlapModal: false,
      loading: false,
    };
  },
  methods: {
    forceSubmit() {
      this.createRule(true);
    },
    async createRule(forceOnOverlap) {
      this.loading = true;
      this.rule.force_on_overlap = forceOnOverlap;
      const { data } = await axios.post(
        this.backend_base_url + "/rules",
        this.rule
      );
      this.loading = false;

      if (data.has_overlap) {
        this.overlap_rules = data.overlap_rules;
        this.overlapModal = true;
        return;
      }

      this.overlapModal = false;

      this.$notify({
        type: "success",
        text: "Data has been stored SUCCESSFULLY!",
      });

      this.$router.push("/rules/" + data.rule.id + "/show");
    },
  },
  computed: {},
  watch: {},
  mounted() {
    // this.getRules();
  },
};
</script>
