<template>

    <v-sheet class="pa-5" rounded elevation="4">
        <div class="text-info">{{ rule.line_name }}</div>
        <div>
            <strong>روز هفته: </strong>
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
        <div class="my-1"><strong>{{ $t("Time From") }}</strong>: <v-chip>{{ rule.time_from }}</v-chip></div>
        <div><strong>{{ $t("Time To") }}</strong>: <v-chip>{{ rule.time_to }}</v-chip></div>
        <v-table>
            <thead>
                <tr>
                    <th>{{ $t("Title") }}</th>
                    <th>{{ $t("Count") }}</th>
                    <th>{{ $t("Percent") }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(count, output_name ) in rule.report">
                    <td>
                        {{ output_name }}
                    </td>
                    <td>
                        {{ count }}
                    </td>
                    <td>
                        {{ percentages[output_name] }}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>{{ $t("Total") }}</td>
                    <td>
                        {{ total }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </v-table>

    </v-sheet>
</template>
<script>
import axios from 'axios';
export default {
    props: ["rule"],
    data() {
        return {
        }
    },
    computed: {
        total() {
            return Object.values(this.rule.report).reduce((sum, value) => sum + value, 0)
        },

        percentages() {
            return Object.entries(this.rule.report).reduce((result, [key, value]) => {
                result[key] = ((value / this.total) * 100).toFixed(2); // با دقت دو رقم اعشار
                return result;
            }, {});
        }


    }
}
</script>