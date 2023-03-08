<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
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

        <div class="p-12">
            <UpdatePasswordForm class="max-w-xl mb-12" />

            <DeleteUserForm class="max-w-xl" />
        </div>
    </AuthenticatedLayout>
</template>
