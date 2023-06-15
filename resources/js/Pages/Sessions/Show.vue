<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import route from "ziggy-js";
import { User } from "@/types/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BackIcon from "@/Components/BackIcon.vue";

defineProps({
    session: {
        type: Object,
        default: () => {
            return {};
        },
    },
    users: {
        type: Array<User>,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Votes" />
    <AuthenticatedLayout>
        <template #header>
            <div class="inline-flex items-center">
                <Link
                    :href="route('home')"
                    class="text-sm text-gray-700 underline"
                >
                    <BackIcon />
                </Link>

                <h2
                    class="ml-2 font-semibold text-xl text-gray-800 leading-tight"
                >
                    {{ session.title }}
                </h2>
            </div>
        </template>

        <div v-if="session.documents.length">
            <span>Documents:</span>

            <div v-for="document in session.documents" :key="document.name">
                <a :href="route('documents.download', document.id)">
                    {{ document.name }}
                </a>
            </div>
        </div>

        <div v-if="session.users.length">
            <span>Usernames :</span>
            <li
                v-for="user in users
                    .filter((user: User) => session.users.includes(user.id))"
                :key="user.id"
            >
                {{ user.name }}
            </li>
        </div>
    </AuthenticatedLayout>
</template>
