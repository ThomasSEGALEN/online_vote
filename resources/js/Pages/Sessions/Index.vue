<script setup lang="ts">
import { computed, onMounted, ref, watch } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import route from "ziggy-js";
import { throttle } from "lodash";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DangerButton from "@/Components/DangerButton.vue";
import DeleteIcon from "@/Components/DeleteIcon.vue";
import Modal from "@/Components/Modal.vue";
import Pagination from "@/Components/Pagination.vue";
import ResponsivePagination from "@/Components/ResponsivePagination.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import UpdateIcon from "@/Components/UpdateIcon.vue";

const props = defineProps({
    showCreateModal: Boolean,
    showViewModal: Boolean,
    showDeleteModal: Boolean,
    sessions: {
        type: Object,
        default: () => {
            return {};
        },
    },
    status: {
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

const search = ref<string>(props.filters?.search);
const confirmingSessionDeletion = ref<boolean>(false);
const sessionId = ref<number>();
const showMessage = ref<boolean>(false);

const successMessage = computed(() => (usePage().props.flash as any).success);
const errorMessage = computed(() => (usePage().props.flash as any).error);

onMounted(() => {
    showMessage.value = true;
    setTimeout(() => {
        showMessage.value = false;
    }, 3000);
});

watch(
    search,
    throttle((value) => {
        router.get(
            route("sessions.index"),
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 500)
);

const confirmSessionDeletion = (id: number) => {
    confirmingSessionDeletion.value = true;
    sessionId.value = id;
};

const deleteSession = () => {
    router.delete(`sessions/${sessionId.value}`, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => closeModal(),
    });
};

const closeModal = () => (confirmingSessionDeletion.value = false);
</script>

<template>
    <Head title="Séances" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Séances
            </h2>
        </template>

        <div class="p-4 md:p-6">
            <div class="flex flex-wrap flex-row items-center justify-between">
                <div
                    v-if="can?.createSessions"
                    class="flex items-center space-x-2 mb-2"
                >
                    <Link
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                        :href="route('sessions.create')"
                    >
                        Nouvelle séance
                    </Link>
                </div>

                <TextInput
                    v-model="search"
                    class="block mb-2"
                    type="text"
                    placeholder="Recherche"
                />
            </div>

            <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
            >
                <p
                    v-if="showMessage && successMessage"
                    class="text-sm text-green-600 bg-green-100 py-2 px-4 rounded my-2"
                >
                    {{ successMessage }}
                </p>
                <p
                    v-else-if="showMessage && errorMessage"
                    class="text-sm text-red-600 bg-red-100 py-2 px-4 rounded my-2"
                >
                    {{ errorMessage }}
                </p>
            </Transition>

            <div class="flex flex-col overflow-x-auto min-w-full mt-4 mb-6">
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
                                Titre
                            </th>
                            <th
                                scope="col"
                                class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                            >
                                Date de début
                            </th>
                            <th
                                scope="col"
                                class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                            >
                                Date de fin
                            </th>
                            <th
                                scope="col"
                                class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                            >
                                Statut
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
                            v-for="session in sessions?.data"
                            :key="session.id"
                            class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100"
                        >
                            <td
                                class="text-md text-gray-900 font-bold px-6 py-4"
                            >
                                {{ session.id }}
                            </td>
                            <td
                                class="text-md text-gray-900 font-semibold px-6 py-4"
                            >
                                {{ session.title }}
                            </td>
                            <td
                                class="text-md text-gray-900 font-semibold px-6 py-4"
                            >
                                {{ session.start_date }}
                            </td>
                            <td
                                class="text-md text-gray-900 font-semibold px-6 py-4"
                            >
                                {{ session.end_date }}
                            </td>
                            <td
                                class="text-md text-gray-900 font-semibold px-6 py-4"
                            >
                                {{
                                    status.find(
                                        (status: Status) =>
                                            status.id === session.status_id
                                    )?.name
                                }}
                            </td>
                            <td class="flex space-x-5 px-6 py-4">
                                <Link
                                    v-if="can?.updateSessions"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                                    :href="route('sessions.edit', session.id)"
                                >
                                    <UpdateIcon />
                                </Link>
                                <DangerButton
                                    v-if="can?.deleteSessions"
                                    @click="confirmSessionDeletion(session.id)"
                                >
                                    <DeleteIcon />
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination
                v-if="sessions?.total > sessions?.per_page"
                class="hidden md:flex"
                :to="sessions?.to"
                :from="sessions?.from"
                :total="sessions?.total"
                :links="sessions?.links"
            />
            <ResponsivePagination
                v-if="sessions?.total > sessions?.per_page"
                class="flex md:hidden"
                :to="sessions?.to"
                :from="sessions?.from"
                :total="sessions?.total"
                :links="sessions?.links"
            />

            <Modal :show="confirmingSessionDeletion" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-800">
                        Êtes-vous sûr de vouloir supprimer cet utilisateur ?
                    </h2>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal">
                            Annuler
                        </SecondaryButton>

                        <DangerButton class="ml-4" @click="deleteSession">
                            Supprimer
                        </DangerButton>
                    </div>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
