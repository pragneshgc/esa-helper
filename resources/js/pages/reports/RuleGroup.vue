<template>
    <div class="d-flex justify-content-between align-items-center border p-2">
        <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-primary" @click="addRule">Add Rule</button>
            <p class="text-info m-0 ps-3">Add rule to filter records.</p>
        </div>
        <div class="d-flex flex-column flex-wrap mt-3">
            <template v-for="(rule, index) in rules" :key="index">
                <component :is="rule" v-bind="ruleProps"></component>
            </template>
        </div>
        <button class="btn btn-danger">
            <i class="fa-solid fa-trash"></i>
            Remove Group
        </button>
    </div>
</template>
<script setup>
import { ref, computed } from 'vue';
import Rule from './Rule.vue';

const rules = ref([]);
const props = defineProps(['fields']);
const addRule = () => {
    rules.value.push(Rule);
}
const ruleProps = computed(() => {
    return {
        fields: props.fields
    };
});
</script>