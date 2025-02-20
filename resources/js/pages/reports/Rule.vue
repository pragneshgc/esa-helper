<template>
    <div class="d-flex justify-content-between align-items-center border p-2">
        <div class="d-flex align-items-center">
            <div class="d-flex flex-column">
                <select class="form-select form-select-sm" style="width:80px; background-color: #ddd;"
                    v-model="rule.condition" @change="ruleUpdated" v-if="props.itemNumber > 0">
                    <option value="AND">AND</option>
                    <option value="OR">OR</option>
                </select>

                <div class="d-flex mt-1">
                    <select class="form-select" v-model="rule.field" @change="ruleUpdated">
                        <template v-for="(option, i) in fields" :key="i">
                            <option :value="option.key">
                                {{ option.text }}
                            </option>
                        </template>
                    </select>
                    <select class="form-select" v-model="rule.operator" @change="ruleUpdated">
                        <option value="EQUAL">is equal</option>
                        <option value="NOT_EQUAL">is not equal</option>
                        <option value="LIKE">like</option>
                        <option value="NOT_LIKE">not like</option>
                        <option value="IN">contains</option>
                        <option value="NOT_IN">does not contain</option>
                        <option value="GREATER_THAN">is greater than</option>
                        <option value="GREATER_THAN_OR_EQUAL">is greater than or equal</option>
                        <option value="LESS_THAN">is less than </option>
                        <option value="LESS_THAN_OR_EQUAL">is less than or equal</option>
                        <option value="IS_NULL">is empty</option>
                        <option value="IS_NOT_NULL">is not empty</option>
                        <option value="BETWEEN">between</option>
                        <option value="NOT_BETWEEN">not between</option>
                        <option value="WHERE_DATE">date</option>
                        <option value="WHERE_DATE_BETWEEN">date between</option>
                        <option value="WHERE_DATETIME">datetime</option>
                        <option value="WHERE_DATETIME_BETWEEN">datetime between</option>
                    </select>
                    <template v-if="rule.operator === 'BETWEEN' || rule.operator === 'NOT_BETWEEN'">
                        <input class="form-control" type="text" v-model="valueArr[0]" @change="updateBetween" />
                        <input class="form-control" type="text" v-model="valueArr[1]" @change="updateBetween" />
                    </template>
                    <template v-else-if="rule.operator === 'WHERE_DATE'">
                        <VueDatePicker v-model="rule.value" :enable-time-picker="false" format="dd/MM/yyyy">
                        </VueDatePicker>
                    </template>
                    <template v-else-if="rule.operator === 'WHERE_DATE_BETWEEN'">
                        <VueDatePicker v-model="rule.value" range :enable-time-picker="false" format="dd/MM/yyyy">
                        </VueDatePicker>
                    </template>
                    <template v-else-if="rule.operator === 'WHERE_DATETIME'">
                        <VueDatePicker v-model="rule.value" format="dd/MM/yyyy HH:mm">
                        </VueDatePicker>
                    </template>
                    <template v-else-if="rule.operator === 'WHERE_DATETIME_BETWEEN'">
                        <VueDatePicker v-model="rule.value" range format="dd/MM/yyyy HH:mm">
                        </VueDatePicker>
                    </template>
                    <template v-else>
                        <input class="form-control" type="text" v-model="rule.value" @change="ruleUpdated" />
                    </template>
                </div>
            </div>
        </div>
        <button class="btn btn-danger" @click="remove(groupIndex, index)">
            <i class="fa-solid fa-trash"></i>
            Remove
        </button>
    </div>
</template>
<script setup>
import { onMounted, onUpdated, ref, watch } from 'vue';
import { useQueryGroup } from '../../composables/useQueryGroup';
import VueDatePicker from '@vuepic/vue-datepicker';

const props = defineProps(['fields', 'groupIndex', 'index', 'itemNumber', 'values']);

const rule = ref({
    condition: '',
    field: '',
    operator: '',
    value: '',
});
const valueArr = ref([]);

onMounted(() => {
    rule.value = props.values;
});
onUpdated(() => {
    rule.value = props.values;
});

const {
    removeRule,
    updateRuleFilter
} = useQueryGroup();

const remove = (group, rule) => {
    removeRule(group, rule);
}

const ruleUpdated = () => {
    updateRuleFilter(props.groupIndex, props.index, rule.value);
}

const updateBetween = () => {
    rule.value.value = valueArr.value.join(',');
    console.log(valueArr.value, rule.value);
    updateRuleFilter(props.groupIndex, props.index, rule.value);
}

</script>