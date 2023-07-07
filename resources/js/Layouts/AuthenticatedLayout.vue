<script setup lang="ts">
import Navbar from "@/Components/Navbar.vue";
</script>

<template>
    <div class="lg:ml-56">
        <Navbar />

        <header v-if="$slots.header">
            <div class="flex p-4 border-b">
                <slot name="header" />
            </div>
        </header>

        <main>
            <slot />
        </main>
    </div>
</template>
