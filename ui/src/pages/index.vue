<template>
  <v-container>
    <div>{{ $t("Dashboard") }}</div>
    <hr>
    <lines-report />
  </v-container>
</template>

<script>
import lines from "../simotelSmartApps";
import axios from "axios";
import LinesReport from "@/components/LinesReport.vue";

export default {
  components: { LinesReport },
  data() {
    return {
      linesArray: [],
      selected_line: "",
      selected_week: "w1",
      selected_tc: "",
      output_percents: [],
      percents_sum: 0,
      loading: false,
      report: null,
    };
  },
  methods: {},
  computed: {
    allLines() {
      return lines.getAllLines();
    },
    ll() {
      for (const [key, value] of Object.entries(this.allLines)) {
        this.linesArray.push(key);
      }
    },
    tcs() {
      if (!this.selected_line) return;
      return this.allLines[this.selected_line]["week"][this.selected_week];
    },
    storeValidation() {
      return (
        this.percents_sum == 100 &&
        this.output_percents.every((item) => item >= 0 && item <= 100) &&
        this.selected_line &&
        this.selected_tc &&
        this.selected_week
      );
    },
    getValidation() {
      return this.selected_line && this.selected_tc && this.selected_week;
    },
    preparedData() {
      if (!this.selected_line) return;

      return {
        smartapi: `${this.selected_line}-${this.selected_week}-${this.selected_tc}`,
        out_total: this.allLines[this.selected_line]["outputs_count"],
        out_percent: (() => {
          let outputs = {};

          for (
            let i = 1;
            i <= this.allLines[this.selected_line]["outputs_count"];
            i++
          ) {
            outputs["out-" + i] = this.output_percents[i - 1];
          }
          return outputs;
        })(),
      };
    },
  },
  watch: {
    selected_line: {
      deep: true,
      handler(newVal) {
        this.output_percents = [];
        this.selected_tc = "";
        for (
          let i = 0;
          i < this.allLines[this.selected_line]["outputs_count"];
          i++
        ) {
          this.output_percents[i] = 0;
        }
      },
    },
    output_percents: {
      deep: true,
      handler(newVal) {
        this.percents_sum = newVal.reduce((a, b) => +a + +b, 0);
      },
    },
  },
  methods: {
    async storeData() {
      this.loading = true;
      try {
        const { data } = await axios.post(
          "http://185.211.85.6/tmp/output-percent/post/set-smartapi-percent-post.php",
          this.preparedData,
          {
            withCredentials: true,
            auth: {
              username: "tipax",
              password: "tipax",
            },
          }
        );
        if (data.status == 1) {
          this.$notify({
            type: "success",
            text: "Data has been stored SUCCESSFULLY!",
          });
        }
      } catch (error) {
        console.log(error);
      }
      this.loading = false;
    },
    async getData() {
      this.loading = true;
      try {
        let postdata = {
          smartapi: this.preparedData.smartapi,
        };
        const { data } = await axios.post(
          "http://185.211.85.6/tmp/output-percent/post/get-smartapi-percent-post.php",
          postdata,
          {
            withCredentials: true,
            auth: {
              username: "tipax",
              password: "tipax",
            },
          }
        );
        if (data.status == 1) {
          this.report = data;
          for (let i = 1; i < 10; i++) {
            if (this.report?.out_percent["out-" + i] != undefined)
              this.output_percents[i - 1] =
                this.report?.out_percent["out-" + i];
            else this.output_percents[i - 1] = 0;
          }

          this.$notify({
            type: "success",
            text: "Data Fetched!",
          });
        }
      } catch (error) {
        console.log(error);
      }

      this.loading = false;
    },
  },
  mounted() {
    // console.log(lines.getLine('line1'));
    this.ll;
  },
};
</script>
