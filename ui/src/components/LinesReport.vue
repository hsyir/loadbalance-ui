<template>
    <v-container>
        <v-row>
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
                                ]" item-title="name" item-value="value" variant="outlined" :label="$t('Day Of Week')"
                                    density="compact" v-model="filter.day" rounded></v-select>
                            </v-col>

                            <v-col cols="4">
                                <v-select clearable :items="allLineNames" variant="outlined" :label="$t('Line Name')"
                                    density="compact" v-model="filter.line_name" rounded></v-select>
                            </v-col>

                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row>

            <v-col cols="4" v-for="rule in filteredRules">

                <LineCard :rule="rule" />
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import LineCard from './LineCard.vue';
import axios from 'axios';
export default {
    components: { LineCard },
    data() {
        return {
            filter: {},
            rules: [],
        }
    },
    inject: ["backend_base_url"],
    methods: {

        async getRules() {
            const { data } = await axios.get(this.backend_base_url + "/rules");
            this.rules = data.data;
        },
    },
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
    mounted() {
        this.getRules();
    }
}
</script>