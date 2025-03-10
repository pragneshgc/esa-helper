<template>
    <div>
        <h1>Monthly Reports</h1>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Past Month ({{ lastMonth.month }})</th>
                        <th>Current Month ({{ currentMonth.month }})</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Total Shipped Orders
                        </td>
                        <td>
                            {{ lastMonth.shipped }}
                        </td>
                        <td>
                            {{ currentMonth.shipped }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Total Shipped Orders (Fridge)
                        </td>
                        <td>
                            {{ lastMonth.fridge_shipped }}
                        </td>
                        <td>
                            {{ currentMonth.fridge_shipped }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Returns
                        </td>
                        <td>
                            {{ lastMonth.return }}
                        </td>
                        <td>
                            {{ currentMonth.return }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Resends
                        </td>
                        <td>
                            {{ lastMonth.resend }}
                        </td>
                        <td>
                            {{ currentMonth.resend }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Inventory Units
                        </td>
                        <td>
                            {{ lastMonth.inventory_unit }}
                        </td>
                        <td>
                            {{ currentMonth.inventory_unit }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Inventory Value
                        </td>
                        <td>
                            {{ lastMonth.inventory_value }}
                        </td>
                        <td>
                            {{ currentMonth.inventory_value }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Import Batches
                        </td>
                        <td>
                            {{ lastMonth.batches }}
                        </td>
                        <td>
                            {{ currentMonth.batches }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Import Items
                        </td>
                        <td>
                            {{ lastMonth.batch_items }}
                        </td>
                        <td>
                            {{ currentMonth.batch_items }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            DOOP - Expired Items
                        </td>
                        <td>
                            {{ lastMonth.expired }}
                        </td>
                        <td>
                            {{ currentMonth.expired }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            DOOP - Expired Value
                        </td>
                        <td>
                            {{ lastMonth.expired_value }}
                        </td>
                        <td>
                            {{ currentMonth.expired_value }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            DOOP - Damaged Items
                        </td>
                        <td>
                            {{ lastMonth.damaged }}
                        </td>
                        <td>
                            {{ currentMonth.damaged }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            DOOP - Damaged Value
                        </td>
                        <td>
                            {{ lastMonth.damaged_value }}
                        </td>
                        <td>
                            {{ currentMonth.damaged_value }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            DOOP - Other Items
                        </td>
                        <td>
                            {{ lastMonth.doop_other }}
                        </td>
                        <td>
                            {{ currentMonth.doop_other }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            DOOP - Other Value
                        </td>
                        <td>
                            {{ lastMonth.doop_other_value }}
                        </td>
                        <td>
                            {{ currentMonth.doop_other_value }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script setup>
import axios from 'axios';
import { onMounted, onUnmounted, ref } from 'vue';

const currentMonth = ref('');
const lastMonth = ref('');
let interval;
onMounted(() => {
    getCurrentMonthReport();
    getLastMonthReport();
    interval = setInterval(() => {
        getCurrentMonthReport();
    }, 5 * 60 * 1000);
});

onUnmounted(() => {
    clearInterval(interval);
});

const getCurrentMonthReport = () => {
    axios.get('/get-current-month-report')
        .then(response => {
            currentMonth.value = response.data
        })
        .catch(error => {
            console.log(error);
        });
}
const getLastMonthReport = () => {
    axios.get('/get-past-month-report')
        .then(response => {
            lastMonth.value = response.data
        })
        .catch(error => {
            console.log(error);
        });
}
</script>