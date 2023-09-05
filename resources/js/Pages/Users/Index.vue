<script setup lang="ts">
import { computed, onMounted, ref, watch } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { Method } from "@inertiajs/core";
import route from "ziggy-js";
import { debounce } from "lodash";
import { User } from "@/types/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DangerButton from "@/Components/DangerButton.vue";
import DeleteIcon from "@/Components/DeleteIcon.vue";
import FileExportIcon from "@/Components/FileExportIcon.vue";
import FileImportIcon from "@/Components/FileImportIcon.vue";
import FileInput from "@/Components/FileInput.vue";
import Modal from "@/Components/Modal.vue";
import Pagination from "@/Components/Pagination.vue";
import ResponsivePagination from "@/Components/ResponsivePagination.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import UpdateIcon from "@/Components/UpdateIcon.vue";

const props = defineProps({
    users: {
        type: Object,
        default: () => {
            return {};
        },
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

const search = ref<string>(props.filters.search);
const confirmingUserDeletion = ref<boolean>(false);
const userId = ref<number>();
const showMessage = ref<boolean>(false);
const fileInput = ref<HTMLInputElement>();

const successMessage = computed(() => usePage().props.flash.success);
const errorMessage = computed(() => usePage().props.flash.error);

onMounted(() => {
    showMessage.value = true;
    setTimeout(() => (showMessage.value = false), 3000);
});

watch(
    search,
    debounce((value) => {
        router.get(
            route("users.index"),
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300)
);

const confirmUserDeletion = (id: number) => {
    confirmingUserDeletion.value = true;
    userId.value = id;
};

const deleteUser = () => {
    router.delete(`users/${userId.value}`, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => closeModal(),
    });
};

const closeModal = () => (confirmingUserDeletion.value = false);

const clickFile = () => fileInput.value?.click();

const importFile = (event: Event) => {
    const file = (<HTMLInputElement>event.target).files?.[0];

    router.visit(route("users.import"), {
        data: { usersFile: file },
        method: "post" as Method,
    });
};
</script>

<template>
    <Head title="Utilisateurs" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Utilisateurs
            </h2>
        </template>

        <div class="p-4 lg:p-6 lg:max-w-screen-xl mx-auto">
            <div class="flex flex-wrap flex-row items-center justify-between">
                <div
                    v-if="can.createUsers"
                    class="flex items-center space-x-2 mb-2"
                >
                    <Link
                        class="inline-flex px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                        :href="route('users.create')"
                    >
                        Nouvel utilisateur
                    </Link>

                    <button
                        class="inline-flex px-2 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                        @click="clickFile"
                    >
                        <FileImportIcon />

                        <FileInput
                            ref="fileInput"
                            type="file"
                            hidden
                            @change="importFile"
                        />
                    </button>

                    <a
                        :href="route('users.export')"
                        class="inline-flex items-center px-2 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        <FileExportIcon />
                    </a>
                </div>

                <TextInput
                    id="search"
                    v-model="search"
                    type="text"
                    placeholder="Recherche"
                />
            </div>

            <Transition
                enter-active-class="duration-300 ease-out"
                enter-from-class="transform opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="transform opacity-0"
            >
                <p
                    v-if="showMessage && errorMessage"
                    class="text-sm text-red-600 bg-red-100 py-2 px-4 rounded my-2"
                >
                    {{ errorMessage }}
                </p>

                <p
                    v-else-if="showMessage && successMessage"
                    class="text-sm text-green-600 bg-green-100 py-2 px-4 rounded my-2"
                >
                    {{ successMessage }}
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
                            v-for="user in users.data as Array<User>"
                            :key="user.id"
                            class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100"
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

                            <td class="flex space-x-5 px-6 py-4">
                                <Link
                                    v-if="can.updateUsers"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                                    :href="route('users.edit', user.id)"
                                >
                                    <UpdateIcon />
                                </Link>

                                <DangerButton
                                    v-if="can.deleteUsers"
                                    @click="confirmUserDeletion(user.id)"
                                >
                                    <DeleteIcon />
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination
                v-if="users.total > users.per_page"
                class="hidden lg:flex"
                :to="users.to"
                :from="users.from"
                :total="users.total"
                :links="users.links"
            />

            <ResponsivePagination
                v-if="users.total > users.per_page"
                class="flex lg:hidden"
                :to="users.to"
                :from="users.from"
                :total="users.total"
                :links="users.links"
            />

            <Modal :show="confirmingUserDeletion" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-800">
                        Êtes-vous sûr de vouloir supprimer cet utilisateur ?
                    </h2>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal">
                            Annuler
                        </SecondaryButton>

                        <DangerButton class="ml-4" @click="deleteUser">
                            Supprimer
                        </DangerButton>
                    </div>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
