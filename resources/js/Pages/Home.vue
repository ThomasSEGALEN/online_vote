<script setup lang="ts">
import { ref, watch } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import route from "ziggy-js";
import { throttle } from "lodash";
import { Permission, Session, Status } from "@/types/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import RadioInput from "@/Components/RadioInput.vue";

const props = defineProps({
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
    filters: {
        type: Object,
        default: () => {
            return {};
        },
    },
});

const status = ref<number>(props.filters.status);

watch(
    status,
    throttle((value) => {
        router.get(
            route("home"),
            { status: value },
            { preserveState: true, replace: true }
        );
    }, 500)
);

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

        <div class="p-4 lg:p-6">
            <div>
                <span class="block font-medium text-md text-gray-700">
                    Filtre
                </span>

                <div class="mt-1 space-x-4">
                    <div class="inline-flex items-center space-x-1">
                        <RadioInput
                            id="status-all"
                            v-model.number="status"
                            :value="0"
                        />

                        <InputLabel for="status-all" value="Tous" />
                    </div>

                    <div class="inline-flex items-center space-x-1">
                        <RadioInput
                            id="status-open"
                            v-model.number="status"
                            :value="1"
                        />

                        <InputLabel for="status-open" value="Ouvert" />
                    </div>

                    <div class="inline-flex items-center space-x-1">
                        <RadioInput
                            id="status-closed"
                            v-model.number="status"
                            :value="2"
                        />

                        <InputLabel for="status-closed" value="Fermé" />
                    </div>
                </div>
            </div>

            <!-- {{ (sessions.data as Array<Session>).filter((data) => data.status_id === 2) }} -->
            <div class="flex flex-col space-y-6 mt-4">
                <template
                    v-for="session in sessions.data as Array<Session>"
                    :key="session.id"
                >
                    <Link :href="route('sessions.show', session.id)">
                        <div
                            v-show="hasAccess(session)"
                            class="flex flex-col bg-white p-6 rounded-lg shadow-lg"
                        >
                            <div class="flex flex-wrap justify-between">
                                <h2
                                    class="text-2xl font-bold mb-2 text-gray-800"
                                >
                                    {{ session.title }}
                                </h2>
                                <span
                                    class="text-md mb-2 text-gray-800"
                                    :class="
                                        session.status_id === 1
                                            ? 'text-green-500'
                                            : 'text-red-500'
                                    "
                                >
                                    {{
                                        session.status_id === 1
                                            ? "Ouvert"
                                            : "Fermé"
                                    }}
                                </span>
                            </div>

                            <p class="text-gray-700 break-all">
                                {{
                                    session.description.length > 100
                                        ? session.description.substring(
                                              0,
                                              100
                                          ) + "..."
                                        : session.description
                                }}
                            </p>
                        </div>
                    </Link>
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
