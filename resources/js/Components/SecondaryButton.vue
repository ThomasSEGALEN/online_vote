<script setup lang="ts">
import { PropType } from "vue";

defineProps({
    type: {
        type: String as PropType<"submit" | "button" | "reset">,
        default: "button",
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
