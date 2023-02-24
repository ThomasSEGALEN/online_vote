<script setup lang="ts">
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import route from "ziggy-js";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Navbar from "@/Components/Navbar.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const { user } = usePage().props?.auth as any;

const showingNavigationDropdown = ref<Boolean>(false);
</script>

<template>
    <div>
        <div class="flex">
            <div class="w-64">
                <Navbar />
            </div>

            <main class="flex-1">
                <slot />
            </main>
        </div>
    </div>
</template>
