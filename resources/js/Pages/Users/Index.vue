<script setup lang="ts">
import { ref, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import route from "ziggy-js";
import { throttle } from "lodash";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import Pagination from "../../Components/Pagination.vue";
import TextInput from "../../Components/TextInput.vue";

const props = defineProps({
    showCreateModal: Boolean,
    showViewModal: Boolean,
    showDeleteModal: Boolean,
    users: Object,
    filters: Object,
    can: Object,
});

const search = ref<string>(props.filters?.search);

watch(
    search,
    throttle((value) => {
        router.get(
            route("users.index"),
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 500)
);
</script>

<template>
    <Head title="Utilisateurs" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Utilisateurs
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div
                            class="flex flex-wrap flex-row items-center"
                            :class="
                                can?.createUsers
                                    ? 'justify-between'
                                    : 'justify-end'
                            "
                        >
                            <Link
                                v-if="can?.createUsers"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                                :href="route('users.create1')"
                            >
                                Nouvel utilisateur
                            </Link>
                            <TextInput
                                class="block"
                                type="text"
                                placeholder="Recherche"
                                v-model="search"
                            />
                        </div>

                        <div class="flex flex-col">
                            <div class="overflow-x-auto">
                                <div class="py-2 inline-block min-w-full">
                                    <div class="overflow-hidden">
                                        <table class="min-w-full">
                                            <thead class="border-b">
                                                <tr>
                                                    <th
                                                        scope="col"
                                                        class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                                                    >
                                                        #
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                                                    >
                                                        Adresse e-mail
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                                                    >
                                                        Nom
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                                                    >
                                                        Prénom
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                                                    >
                                                        Actions
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100"
                                                    v-for="user in users?.data"
                                                    :key="user.id"
                                                >
                                                    <td
                                                        class="text-md text-gray-900 font-bold px-6 py-4"
                                                    >
                                                        {{ user.id }}
                                                    </td>
                                                    <td
                                                        class="text-md text-gray-900 font-semibold px-6 py-4"
                                                    >
                                                        {{ user.email }}
                                                    </td>
                                                    <td
                                                        class="text-md text-gray-900 font-semibold px-6 py-4"
                                                    >
                                                        {{ user.last_name }}
                                                    </td>
                                                    <td
                                                        class="text-md text-gray-900 font-semibold px-6 py-4"
                                                    >
                                                        {{ user.first_name }}
                                                    </td>
                                                    <td
                                                        class="flex space-x-5 px-6 py-4"
                                                    >
                                                        <button>
                                                            <svg
                                                                class="w-5 h-5"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 576 512"
                                                                fill="#111827"
                                                            >
                                                                <path
                                                                    d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM432 256c0 79.5-64.5 144-144 144s-144-64.5-144-144s64.5-144 144-144s144 64.5 144 144zM288 192c0 35.3-28.7 64-64 64c-11.5 0-22.3-3-31.6-8.4c-.2 2.8-.4 5.5-.4 8.4c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-2.8 0-5.6 .1-8.4 .4c5.3 9.3 8.4 20.1 8.4 31.6z"
                                                                />
                                                            </svg>
                                                        </button>
                                                        <button>
                                                            <svg
                                                                class="w-5 h-5"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 448 512"
                                                                fill="#111827"
                                                            >
                                                                <path
                                                                    d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"
                                                                />
                                                            </svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Pagination
                            v-if="users?.total > users?.per_page"
                            :to="users?.to"
                            :from="users?.from"
                            :total="users?.total"
                            :links="users?.links"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
