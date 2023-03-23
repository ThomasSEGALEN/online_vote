<script setup lang="ts">
import { computed } from "vue";

const props = defineProps({
    modelValue: {
        type: [Number, String],
        default: () => 0,
    },
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
        v-model="modelValue"
        class="shadow-sm text-indigo-600 border-2 border-gray-300 focus:ring-offset-0 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 focus:outline-none outline-none transition duration-150 ease-in-out"
        type="radio"
    />
</template>
