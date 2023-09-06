<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";

defineProps({
    mustVerifyEmail: Boolean,
    status: {
        type: String,
        default: () => "",
    },
});
</script>

<template>
    <Head title="Profil" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Profil
            </h2>
        </template>

        <div class="p-4 lg:p-6 lg:max-w-screen-md mx-auto">
            <UpdatePasswordForm class="mb-12" />

            <DeleteUserForm />
        </div>
    </AuthenticatedLayout>
</template>
