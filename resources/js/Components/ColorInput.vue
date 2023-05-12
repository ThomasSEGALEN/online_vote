<script setup lang="ts">
import { onMounted, ref } from "vue";

defineProps({
    modelValue: {
        type: String,
        default: () => "#000000",
    },
});

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
        ref="input"
        type="color"
        class="h-11 bg-transparent border-none outline-none appearance-none"
        :value="modelValue"
        @input="
            $emit(
                'update:modelValue',
                ($event.target as HTMLInputElement).value
            )
        "
    />
</template>
