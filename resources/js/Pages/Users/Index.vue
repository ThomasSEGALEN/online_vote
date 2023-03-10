<script setup lang="ts">
import { computed, onMounted, ref, watch } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { Method } from "@inertiajs/core";
import route from "ziggy-js";
import { throttle } from "lodash";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DangerButton from "@/Components/DangerButton.vue";
import DeleteIcon from "@/Components/DeleteIcon.vue";
import FileExportIcon from "@/Components/FileExportIcon.vue";
import FileImportIcon from "@/Components/FileImportIcon.vue";
import Modal from "@/Components/Modal.vue";
import Pagination from "@/Components/Pagination.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import UpdateIcon from "@/Components/UpdateIcon.vue";

const props = defineProps({
    showCreateModal: Boolean,
    showViewModal: Boolean,
    showDeleteModal: Boolean,
    users: Object,
    filters: Object,
    can: Object,
});

const search = ref<string>(props.filters?.search);
const confirmingUserDeletion = ref<boolean>(false);
const userId = ref<number>();
const showMessage = ref<boolean>(false);
const fileInput = ref<HTMLInputElement>();

const successMessage = computed(() => (usePage().props?.flash as any).success);
const errorMessage = computed(() => (usePage().props?.flash as any).error);

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
            route("users.index"),
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 500)
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

const clickFile = () => {
    fileInput.value?.click();
};

const importFile = (event: Event) => {
    const file = (<HTMLInputElement>event.target).files![0];

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

        <div class="p-12">
            <div class="flex flex-wrap flex-row items-center justify-between">
                <div class="flex items-center space-x-2 mb-2">
                    <Link
                        v-if="can?.createUsers"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                        :href="route('users.create')"
                    >
                        Nouvel utilisateur
                    </Link>

                    <button
                        class="inline-flex items-center px-2 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                        @click="clickFile"
                    >
                        <FileImportIcon />
                        <input
                            type="file"
                            ref="fileInput"
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
                    class="block mb-2"
                    type="text"
                    placeholder="Recherche"
                    v-model="search"
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

            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full mt-4 mb-6">
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
                                        <td class="flex space-x-5 px-6 py-4">
                                            <Link
                                                v-if="can?.updateUsers"
                                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                                                :href="
                                                    route('users.edit', user.id)
                                                "
                                            >
                                                <UpdateIcon />
                                            </Link>
                                            <DangerButton
                                                v-if="can?.deleteUsers"
                                                @click="
                                                    confirmUserDeletion(user.id)
                                                "
                                            >
                                                <DeleteIcon />
                                            </DangerButton>
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
