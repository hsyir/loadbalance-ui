<template>
    <v-sheet class="pa-5" rounded="xl" elevation="1">
        <div class="text-info mb-2"><strong>نام مسیر: </strong>{{ rule.line_name }}</div>
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
        <v-table class="mt-2">
            <thead>
                <tr>
                    <th>{{ $t("Title") }}</th>
                    <th>{{ $t("Count") }}</th>
                    <th>{{ $t("Percent") }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(output) in rule.outputs">
                    <td>
                        {{ output.name }}
                    </td>
                    <td>
                        {{ counts[output.name] ?? "--" }}
                    </td>
                    <td>{{ output.percent }} <span class="text-grey">({{ percentages[output.name] ?? "--" }})</span>
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
        },

        counts() {
            return Object.entries(this.rule.report).reduce((result, [key, value]) => {
                result[key] = value  // با دقت دو رقم اعشار
                return result;
            }, {});
        }


    }
}
</script>