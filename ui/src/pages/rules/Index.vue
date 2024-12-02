<template>
  <v-container>
    <v-row class="border rounded-lg pa-10">
      <v-col>
        <v-btn to="/rules/create" color="primary" class="mb-2" variant="outlined" prepend-icon="mdi-plus"
          :loading="loading">
          {{ $t("Create New Rule") }}</v-btn>
        <v-data-table :items="rules" :headers="headers">
          <template v-slot:item.outputs="{ value }">
            <v-chip v-for="output in value" :key="output" class="ma-1" size="small" color="success">
              {{ output.name }} : {{ output.percent }}%
            </v-chip>
          </template>
          <template v-slot:item.actions="{ item, value }">
            <v-btn :to="'rules/' + item.id + '/show'" size="x-small" rounded variant="flat" color="primary">{{
              $t("Edit")
            }}</v-btn>
            <v-btn @click="remove(item.id)" size="x-small" rounded variant="flat" color="red">{{
              $t("Remove")
            }}</v-btn>
          </template>
          <template v-slot:item.day_of_week="{ item, value }">
            {{
              [
                $t("Saturday"),
                $t("Sunday"),
                $t("Monday"),
                $t("Tuesday"),
                $t("Wednesday"),
                $t("Thursday"),
                $t("Friday"),
              ][value]
            }}
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      headers: [
        {
          title: this.$t("Line"),
          key: "line_name",
        },
        {
          title: this.$t("Day Of Week"),
          key: "day_of_week",
        },
        {
          title: this.$t("Time From"),
          key: "time_from",
        },
        {
          title: this.$t("Time To"),
          key: "time_to",
        },
        {
          title: this.$t("Outputs"),
          key: "outputs",
        },
        {
          title: this.$t("Actions"),
          key: "actions",
        },
      ],
      rules: [],
    };
  },
  inject: ["backend_base_url"],
  methods: {},
  computed: {},
  watch: {},
  methods: {
    async getRules() {

      this.loading = true;
      const { data } = await axios.get(this.backend_base_url + "/rules");
      this.rules = data.data;

      this.loading = false;
    },
    remove(ruleId) {

      this.loading = true;
      axios.post(this.backend_base_url + "/rules/remove", {
        rule_id: ruleId
      })
        .then(res => {
          console.log(res)
          if (res.data.success) {

            this.$notify({
              type: "success",
              duration: 2000,
              text: "Data has been removed SUCCESSFULLY!",
            });

            this.loading = false;

            this.getRules();
          }
        })
        .catch(() => {
          this.loading = false;
        })
        .then();
    }
  },
  mounted() {
    this.getRules();
  },
};
</script>./Index.vue
