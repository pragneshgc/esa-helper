<template>
    <div class="d-flex border p-2 flex-column">
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-primary" @click="addRule">Add Rule</button>
                <p class="text-info m-0 ps-3">Add rule to filter records.</p>
            </div>
            <button class="btn btn-danger" @click="remove">
                <i class="fa-solid fa-trash"></i>
                Remove Group
            </button>
        </div>

        <div class="d-flex flex-column flex-wrap mt-3">
            <template v-for="(rule, index) in rules" :key="index">
                <Rule :fields="fields" :index="rule.id" @removeRule="onRemoveRule" />
            </template>
        </div>

    </div>
</template>
<script setup>
import { ref } from 'vue';
import Rule from './Rule.vue';

const rules = ref([]);
const props = defineProps(['fields', 'index']);
const emit = defineEmits(['removeGroup']);

const addRule = () => {
    rules.value.push({
        'id': `rule-${Math.random()}`
    });
}

const onRemoveRule = (index) => {
    rules.value = rules.value.filter((rule) => {
        return rule.id !== index
    });
}

const remove = () => {
    emit('removeGroup', props.index);
}

</script>