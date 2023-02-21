<script setup lang="ts">
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const auth = usePage().props?.auth as any;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <p class="p-6 text-gray-700">
                        Vous êtes connecté en tant qu'<span
                            class="text-gray-800 font-bold"
                        >
                            {{ auth.role.name }} </span
                        >.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
