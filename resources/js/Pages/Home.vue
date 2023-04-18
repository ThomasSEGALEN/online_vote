<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    sessions: {
        type: Object,
        default: () => {
            return {};
        },
    },
});
</script>

<template>
    <Head title="Votes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Votes
            </h2>
        </template>

        <div v-for="session in sessions?.data" :key="session.id">
            {{ session.title }}
        </div>
    </AuthenticatedLayout>
</template>
