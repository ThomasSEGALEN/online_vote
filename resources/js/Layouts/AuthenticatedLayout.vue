<script setup lang="ts">
import Navbar from "@/Components/Navbar.vue";
</script>

<template>
    <div>
        <div class="flex flex-col md:flex-row">
            <Navbar />

            <main class="flex-1 md:ml-64">
                <header class="" v-if="$slots.header">
                    <div class="py-4 px-4 h-14 border-b">
                        <slot name="header" />
                    </div>
                </header>
                <slot />
            </main>
        </div>
    </div>
</template>
