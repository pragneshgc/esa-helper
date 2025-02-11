<template>
    <header class="container-fluid d-flex flex-wrap align-items-center sticky-top">
        <div class="logo me-auto">
            <a href="/"></a>
        </div>
        <ul class="nav gap-2">
            <li class="nav-item">
                <i class="fa-solid fa-user text-primary"></i>
                {{ username }}
            </li>
            <!-- <li class="nav-item">
                <i class="fa-solid fa-right-from-bracket text-primary"></i>
            </li> -->
        </ul>
    </header>
</template>
<script setup>
import { computed } from 'vue';

const username = computed(() => {
    let user = JSON.parse(localStorage.getItem('esa.user'));
    console.log(user, user.name);
    return user.name + " " + user.surname;
})
</script>