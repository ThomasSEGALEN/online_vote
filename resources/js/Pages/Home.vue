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

const { permissions } = usePage().props.auth;

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

        <div
            v-for="session in sessions.data as Array<Session>"
            :key="session.id"
        >
            <template v-if="hasAccess(session)">
                <Link :href="route('sessions.show', session.id)">
                    {{ session.title }}
                </Link>
                <br />
                <b>Session data:</b> {{ session }}
                <br />
                <b>hasAccess check:</b> {{ hasAccess(session) }}
            </template>
        </div>
    </AuthenticatedLayout>
</template>
