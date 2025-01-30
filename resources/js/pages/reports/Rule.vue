<template>
    <div class="d-flex justify-content-between align-items-center border p-2">
        <div class="d-flex align-items-center">
            <select class="form-select">
                <option value="AND">AND</option>
                <option value="OR">OR</option>
            </select>

            <select class="form-select">
                <template v-for="(option, i) in fields">
                    <option :value="option.key">
                        {{ option.text }}
                    </option>
                </template>
            </select>
            <select class="form-select">
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
            <input class="form-control" type="text" />
        </div>
        <button class="btn btn-danger">
            <i class="fa-solid fa-trash"></i>
            Remove
        </button>
    </div>
</template>
<script setup>
const props = defineProps(['fields']);
</script>