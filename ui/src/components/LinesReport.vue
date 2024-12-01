<template>
    <v-container>
        <v-row>
            <v-col cols="4" v-for="rule in rules">
                
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
    mounted() {
        this.getRules();
    }
}
</script>