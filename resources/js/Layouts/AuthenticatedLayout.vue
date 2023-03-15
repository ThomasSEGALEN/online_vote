<script setup lang="ts">
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import route from "ziggy-js";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Navbar from "@/Components/Navbar.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
</script>

<template>
    <div class="flex flex-col md:flex-row">
        <Navbar />

        <main class="flex flex-col w-full">
            <header v-if="$slots.header">
                <div class="py-4 px-4 h-14 border-b">
                    <slot name="header" />
                </div>
            </header>
            <slot />
        </main>
    </div>
</template>
