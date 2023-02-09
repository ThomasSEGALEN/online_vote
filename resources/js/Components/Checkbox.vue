<script setup lang="ts">
import { computed } from "vue";

const props = defineProps({
    modelValue: [Boolean, Array],
});

const emit = defineEmits(["update:modelValue"]);

const modelValue = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        return emit("update:modelValue", value);
    },
});
</script>

<template>
    <input
        class="rounded shadow-sm text-indigo-600 hover:text-indigo-500 active:text-indigo-700 border-2 border-gray-300 focus:ring-offset-0 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 focus:outline-none outline-none transition duration-150 ease-in-out"
        type="checkbox"
        v-model="modelValue"
    />
</template>
