<script setup lang="ts">
import { onMounted, ref } from "vue";

defineProps(["modelValue"]);

defineEmits(["update:modelValue"]);

const input = ref<HTMLInputElement>();

onMounted(() => {
    if (input.value?.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <input
        class="text-gray-700 px-2 rounded-md shadow-sm border-2 border-gray-300 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 focus:outline-none outline-none transition duration-150 ease-in-out"
        :value="modelValue"
        @input="
            $emit(
                'update:modelValue',
                ($event.target as HTMLInputElement).value
            )
        "
        ref="input"
    />
</template>
