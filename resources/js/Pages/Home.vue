<script setup lang="ts">
import { ref, watch } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import route from "ziggy-js";
import { debounce } from "lodash";
import { Permission, Session, Status } from "@/types/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Pagination from "@/Components/Pagination.vue";
import RadioInput from "@/Components/RadioInput.vue";
import ResponsivePagination from "@/Components/ResponsivePagination.vue";
import TextInput from "@/Components/TextInput.vue";

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
    can: {
        type: Object,
        default: () => {
            return {};
        },
    },
});

const status = ref<number>(props.filters.status);
const search = ref<string>(props.filters.search);

const { permissions } = usePage().props.auth;

watch(
    [status, search],
    debounce(([statusValue, searchValue]) => {
        router.get(
            route("home"),
            { status: statusValue, search: searchValue },
            { preserveState: true, replace: true }
        );
    }, 300)
);

const hasAccess = (session: Session): boolean =>
    permissions.some(
        (permission: Permission) => permission.name === "viewSessions"
    ) || session.allowed;

const getDate = (timestamp: Date) => {
    if (!timestamp) return;

    const date = new Date(timestamp);
    let hours = date.getHours();
    let minutes = date.getMinutes().toString().padStart(2, "0");
    let finalDate = `${date.toLocaleDateString()} - ${hours}H${minutes}`;

    return finalDate;
};
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
            <div class="flex flex-wrap flex-row items-center justify-between">
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

                <TextInput
                    id="search"
                    v-model="search"
                    type="text"
                    placeholder="Recherche"
                />
            </div>

            <div class="mt-4">
                <div
                    v-for="session in sessions.data as Array<Session>"
                    :key="session.id"
                    class="mb-4"
                >
                    <template v-if="hasAccess(session)">
                        <Link :href="route('sessions.show', session.id)">
                            <div
                                class="flex flex-col justify-evenly bg-white p-6 rounded-lg shadow-lg"
                            >
                                <div class="flex flex-col justify-between">
                                    <div
                                        class="flex flex-col md:flex-row justify-between"
                                    >
                                        <h2
                                            class="text-2xl font-bold text-gray-800"
                                        >
                                            {{ session.title }}
                                        </h2>

                                        <span
                                            class="block font-medium text-md"
                                            :class="
                                                session.status_id === 1
                                                    ? 'text-green-600'
                                                    : 'text-red-600'
                                            "
                                        >
                                            {{
                                                session.status_id === 1
                                                    ? "Ouvert"
                                                    : "Fermé"
                                            }}
                                        </span>
                                    </div>

                                    <div class="flex flex-row justify-between">
                                        <p
                                            class="block font-medium text-md text-gray-700 break-all mt-4"
                                        >
                                            {{
                                                session.description?.length >
                                                200
                                                    ? session.description.substring(
                                                          0,
                                                          200
                                                      ) + "..."
                                                    : session.description
                                            }}
                                        </p>

                                        <div>
                                            <span
                                                class="block font-medium text-sm text-gray-700"
                                            >
                                                {{
                                                    getDate(session.start_date)
                                                }}
                                            </span>
                                            <span
                                                class="block font-medium text-sm text-gray-700"
                                            >
                                                {{ getDate(session.end_date) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </template>

                    <template v-else>
                        <div
                            class="flex flex-col justify-evenly bg-white p-6 rounded-lg shadow-lg"
                        >
                            <div class="flex flex-col justify-between">
                                <div
                                    class="flex flex-col md:flex-row justify-between"
                                >
                                    <h2
                                        class="text-2xl font-bold text-gray-800"
                                    >
                                        {{ session.title }}
                                    </h2>

                                    <span
                                        class="block font-medium text-md"
                                        :class="
                                            session.status_id === 1
                                                ? 'text-green-600'
                                                : 'text-red-600'
                                        "
                                    >
                                        {{
                                            session.status_id === 1
                                                ? "Ouvert"
                                                : "Fermé"
                                        }}
                                    </span>
                                </div>

                                <div class="flex flex-row justify-between">
                                    <p
                                        class="block font-medium text-md text-gray-700 break-all mt-4"
                                    >
                                        {{
                                            session.description?.length > 200
                                                ? session.description.substring(
                                                      0,
                                                      200
                                                  ) + "..."
                                                : session.description
                                        }}
                                    </p>

                                    <div>
                                        <span
                                            class="block font-medium text-sm text-gray-700"
                                        >
                                            {{ getDate(session.start_date) }}
                                        </span>
                                        <span
                                            class="block font-medium text-sm text-gray-700"
                                        >
                                            {{ getDate(session.end_date) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <Pagination
                v-if="sessions.total > sessions.per_page"
                class="hidden lg:flex"
                :to="sessions.to"
                :from="sessions.from"
                :total="sessions.total"
                :links="sessions.links"
            />

            <ResponsivePagination
                v-if="sessions.total > sessions.per_page"
                class="flex lg:hidden"
                :to="sessions.to"
                :from="sessions.from"
                :total="sessions.total"
                :links="sessions.links"
            />
        </div>
    </AuthenticatedLayout>
</template>
