<template>
    <h2>App</h2>

    <h3>Count: {{ count }}</h3>

    <button type="button" @click="increment"> + </button>
    <button type="button" @click="decrement"> - </button>

    <nav>
        <RouterLink to="/">Home</RouterLink>
        <RouterLink to="/settings">Settings</RouterLink>
    </nav>
    <hr />

    <router-view></router-view>
</template>

<script setup>
import { ref } from 'vue';
import Modal from './components/Modal.vue';

const count = ref(0);

const increment = () => count.value++;
const decrement = () => count.value--;
</script>