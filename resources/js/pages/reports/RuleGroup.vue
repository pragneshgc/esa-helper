<template>
    <div class="d-flex border p-2 flex-column">
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-primary" @click="onAddRule">Add Rule</button>
                <p class="text-info m-0 ps-3">Add rule to filter records.</p>
            </div>
            <button class="btn btn-danger" @click="removeQueryGroup(index)">
                <i class="fa-solid fa-trash"></i>
                Remove Group
            </button>
        </div>

        <div class="d-flex flex-column flex-wrap mt-3">
            <template v-for="(group, ind) in groups" :key="ind">
                <template v-for="(rule, i) in group.rules" :key="i">
                    <Rule :fields="fields" :item-number="i" :group-index="index" :index="rule.id" :values="rule" />
                </template>

            </template>
        </div>

    </div>
</template>
<script setup>
import { computed, toRefs } from 'vue';
import Rule from './Rule.vue';
import { useQueryGroup } from '../../composables/useQueryGroup';
const props = defineProps(['fields', 'index']);
const {
    removeQueryGroup,
    addRule,
    queryGroups
} = useQueryGroup();

const groups = computed(() => {
    let groups = queryGroups.value.filter((group) => {
        if (group.id === props.index) {
            return group;
        }
    });
    return groups;
});

const onAddRule = () => {
    addRule(props.index, `rule-${Math.random()}`);
}

</script>