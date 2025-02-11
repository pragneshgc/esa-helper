<template>
    <Header />
    <div class="mt-2">
        <router-view></router-view>
    </div>
</template>
<script setup>
import Header from './components/layouts/Header.vue';
</script>
