<script setup lang="ts">
import Navbar from "@/Components/Navbar.vue";
</script>

<template>
    <div class="flex flex-col md:flex-row">
        <Navbar />

        <main class="flex flex-col flex-1 md:ml-56">
            <header v-if="$slots.header">
                <div class="py-4 px-4 h-fit border-b">
                    <slot name="header" />
                </div>
            </header>

            <slot />
        </main>
    </div>
</template>
