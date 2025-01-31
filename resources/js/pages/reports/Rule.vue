<template>
    <div class="d-flex justify-content-between align-items-center border p-2">
        <div class="d-flex align-items-center">
            <select class="form-select" style="width:100px; background-color: #ddd;" v-model="rule['condition']">
                <option value="AND">AND</option>
                <option value="OR">OR</option>
            </select>

            <select class="form-select" v-model="rule['field']">
                <template v-for="(option, i) in fields" :key="i">
                    <option :value="option.key">
                        {{ option.text }}
                    </option>
                </template>
            </select>
            <select class="form-select" v-model="rule['operator']">
                <option value="EQUAL">is equal</option>
                <option value="notEqual">is not equal</option>
                <option value="IN">contains</option>
                <option value="NOT_IN">does not contain</option>
                <option value="GREATER_THAN">is greater than(>)</option>
                <option value="GREATER_THAN_OR_EQUAL">is greater than(>=)</option>
                <option value="LESS_THAN">is greater than(<) </option>
                <option value="LESS_THAN_OR_EQUAL">is greater than(<=) </option>
                <option value="IS_NULL">is Empty/Blank</option>
                <option value="BETWEEN">Between</option>
            </select>
            <input class="form-control" type="text" v-model="rule['value']" />
        </div>
        <button class="btn btn-danger" @click="remove">
            <i class="fa-solid fa-trash"></i>
            Remove
        </button>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import { useQueryGroup } from '../../composables/useQueryGroup';

const props = defineProps(['fields', 'index']);
const emit = defineEmits(['removeRule']);

const {
    rule
} = useQueryGroup();

const ruleCondition = ref('');
const ruleField = ref('');
const ruleOperator = ref('');

const remove = () => {
    emit('removeRule', props.index);
}
</script>