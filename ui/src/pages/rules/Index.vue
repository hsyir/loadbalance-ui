<template>
  <v-container>
    <v-row>
      <v-col class="text-end">

        <v-btn to="/rules/create" color="primary" class="mb-2" variant="outlined" prepend-icon="mdi-plus" rounded
          :loading="loading">
          {{ $t("Create New Rule") }}</v-btn>
      </v-col>
    </v-row>
    <v-row class="border rounded-lg pa-10">

      <v-col>



        <v-card class="mb-2" rounded="xl" elevation="0">
          <v-card-text>
            <v-row>
              <v-col>{{ $t("Filter") }}</v-col>
            </v-row>
            <v-row>
              <v-col cols="4">
                <v-select clearable :items="[
                  { name: $t('Saturday'), value: '0' },
                  { name: $t('Sunday'), value: '1' },
                  { name: $t('Monday'), value: '2' },
                  { name: $t('Tuesday'), value: '3' },
                  { name: $t('Wednesday'), value: '4' },
                  { name: $t('Thursday'), value: '5' },
                  { name: $t('Friday'), value: '6' },
                ]" item-title="name" item-value="value" variant="outlined" :label="$t('Day Of Week')" density="compact"
                  v-model="filter.day" rounded></v-select>
              </v-col>

              <v-col cols="4">
                <v-select clearable :items="allLineNames" variant="outlined" :label="$t('Line Name')" density="compact"
                  v-model="filter.line_name" rounded></v-select>
              </v-col>

            </v-row>
          </v-card-text>
        </v-card>


        <v-expand-transition>
          <v-card class="mb-2" rounded="xl" elevation="0" v-if="selected_rules.length > 0">
            <v-card-text>
              <v-row>
                <v-col>{{ $t("Actions") }}</v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-dialog max-width="500" v-model="activeBulkEdit">
                    <template v-slot:activator="{ props: activatorProps }">
                      <v-btn v-bind="activatorProps" v-model="activeBulkEdit" color="info" rounded density="compact"
                        :text="$t('Edit Outputs')" variant="outlined"></v-btn>
                    </template>

                    <template v-slot:default="{ isActive }">
                      <v-card title="Dialog">
                        <v-card-text>
                          <outputs :outputs="edit_outputs" :errors="errors" />
                        </v-card-text>

                        <v-card-actions>
                          <v-spacer></v-spacer>

                          <v-btn :text="$t('Save')" @click="submitBulkEdit"></v-btn>
                          <v-btn :text="$t('Back')" @click="isActive.value = false"></v-btn>
                        </v-card-actions>
                      </v-card>
                    </template>
                  </v-dialog>




                </v-col>

              </v-row>
            </v-card-text>
          </v-card>
        </v-expand-transition>


        <v-data-table :items="filteredRules" :headers="headers" class="rounded-xl">
          <template v-slot:item.outputs="{ value }">
            <v-chip v-for="output in value" :key="output" class="ma-1" size="small" color="success">
              {{ output.name }} : {{ output.percent }}%
            </v-chip>
          </template>
          <template v-slot:item.select="{ item, value }">
            <v-checkbox :value="item.id" v-model="selected_rules" />
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
import Outputs from "./Outputs.vue";

export default {
  components: { Outputs },
  data() {
    return {
      selected_rules: [],
      errors: {},
      bulkEditLoading: false,
      edit_outputs: [],
      activeBulkEdit: false,
      filter: {},
      headers: [
        {
          title: "",
          key: "select",
        },
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
  computed: {
    allLineNames() {
      var rules;
      rules = this.rules.map((item) => {
        return item.line_name
      }).reduce(function (a, b) {
        if (a.indexOf(b) < 0) a.push(b);
        return a;
      }, [])
      return rules;
    },
    filteredRules() {
      let filteredData = this.rules;
      if (this.filter.day) {
        filteredData = filteredData.filter(item => item.day_of_week == this.filter.day)
      }

      if (this.filter.line_name) {
        filteredData = filteredData.filter(item => item.line_name == this.filter.line_name)
      }

      return filteredData;

    }
  },
  watch: {},
  methods: {

    submitBulkEdit() {
      let hasError = false;

      if (!this.edit_outputs || this.edit_outputs.length < 1) {
        hasError = true;
        this.errors['outputs'] = this.$t("Add one or more outputs")
      } else if (this.edit_outputs.reduce(function (a, b) {
        return +a + +b.percent;
      }, 0) != 100) {
        hasError = true;
        this.errors['outputs'] = this.$t("Total value of output percents must be equal to 100")
      }


      if (hasError)
        return;

      this.bulkEditLoading = true;
      axios.post(this.backend_base_url + '/rules/bulk_edit', {
        selected_rules: this.selected_rules,
        outputs: this.edit_outputs,
      })
        .then(res => {
          if (res.data.success) {


            this.$notify({
              type: "success",
              duration: 2000,
              text: "Data has been removed SUCCESSFULLY!",
            });

            this.activeBulkEdit = false;
            this.selected_rules = [];
            this.edit_outputs = [];
            this.getRules();
            this.errors = {};


          }
        })
        .catch(err=>{
          this.$notify({
              type: "error",
              duration: 2000,
              text: "there are some errors!",
            });

        })
        .then(() => {
          this.bulkEditLoading = false;
        })
    },
    async getRules() {

      this.filter = {};

      this.loading = true;
      const { data } = await axios.get(this.backend_base_url + "/rules");
      this.rules = data.data;

      this.loading = false;
    },

    remove(ruleId) {

      if (!confirm("Are you sure?")) return;

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
