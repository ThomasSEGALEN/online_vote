<script setup lang="ts">
import { Head, Link, usePage } from "@inertiajs/vue3";
import route from "ziggy-js";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    sessions: {
        type: Object,
        default: () => {
            return {};
        },
    },
    statuses: {
        type: Array<Status>,
        default: () => [],
    },
});

const { permissions } = usePage().props.auth as any;

const hasAccess = (session: Session): boolean =>
    permissions.some(
        (permission: Permission) => permission.name === "viewSessions"
    ) || session.allowed;
</script>

<template>
    <Head title="Votes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Votes
            </h2>
        </template>

        {{ statuses }}

        <div v-for="session in sessions?.data" :key="session.id">
            <template v-if="hasAccess(session)">
                <Link :href="route('sessions.show', session.id)">
                    {{ session.title }}
                </Link>
                {{ session }}
                <br />
                {{ hasAccess(session) }}
            </template>
        </div>
    </AuthenticatedLayout>
</template>
