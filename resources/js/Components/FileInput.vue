<script setup lang="ts">
import { onMounted, ref } from "vue";

defineProps({
    modelValue: {
        type: String,
        default: () => "",
    },
});

defineEmits(["update:modelValue"]);

const input = ref<HTMLInputElement>();

onMounted(() => {
    if (input.value?.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({
    focus: () => input.value?.focus(),
    click: () => input.value?.click(),
});
</script>

<template>
    <input
        ref="input"
        type="file"
        :value="modelValue"
        @input="
            $emit(
                'update:modelValue',
                ($event.target as HTMLInputElement).value
            )
        "
    />
</template>
