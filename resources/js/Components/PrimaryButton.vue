<script setup lang="ts">
import { PropType } from "vue";

defineProps({
    type: {
        type: String as PropType<"submit" | "button" | "reset">,
        default: "submit",
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none outline-none transition duration-150 ease-in-out"
    >
        <slot />
    </button>
</template>
