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
    roles: Object,
    filters: Object,
    can: Object,
});

const search = ref<string>(props.filters?.search);
const confirmingRoleDeletion = ref<boolean>(false);
const roleId = ref<number>();
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
            route("roles.index"),
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 500)
);

const confirmRoleDeletion = (id: number) => {
    confirmingRoleDeletion.value = true;
    roleId.value = id;
};

const deleteRole = () => {
    router.delete(`roles/${roleId.value}`, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => closeModal(),
    });
};

const closeModal = () => (confirmingRoleDeletion.value = false);

const clickFile = () => {
    fileInput.value?.click();
};

const importFile = (event: Event) => {
    const file = (<HTMLInputElement>event.target).files![0];

    router.visit(route("roles.import"), {
        data: { rolesFile: file },
        method: "post" as Method,
    });
};
</script>

<template>
    <Head title="Rôles" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Rôles
            </h2>
        </template>

        <div class="p-12">
            <div class="flex flex-wrap flex-row items-center justify-between">
                <div class="flex items-center space-x-2 mb-2">
                    <Link
                        v-if="can?.createRoles"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                        :href="route('roles.create')"
                    >
                        Nouveau rôle
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
                        :href="route('roles.export')"
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
                                Nom
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
                            v-for="role in roles?.data"
                            :key="role.id"
                        >
                            <td
                                class="text-md text-gray-900 font-bold px-6 py-4"
                            >
                                {{ role.id }}
                            </td>
                            <td
                                class="text-md text-gray-900 font-semibold px-6 py-4"
                            >
                                {{ role.name }}
                            </td>
                            <td class="flex space-x-5 px-6 py-4">
                                <Link
                                    v-if="can?.updateRoles"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                                    :href="route('roles.edit', role.id)"
                                >
                                    <UpdateIcon />
                                </Link>
                                <DangerButton
                                    v-if="can?.deleteRoles"
                                    @click="confirmRoleDeletion(role.id)"
                                >
                                    <DeleteIcon />
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination
                v-if="roles?.total > roles?.per_page"
                :to="roles?.to"
                :from="roles?.from"
                :total="roles?.total"
                :links="roles?.links"
            />

            <Modal :show="confirmingRoleDeletion" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-800">
                        Êtes-vous sûr de vouloir supprimer ce rôle ?
                    </h2>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal">
                            Annuler
                        </SecondaryButton>

                        <DangerButton class="ml-4" @click="deleteRole">
                            Supprimer
                        </DangerButton>
                    </div>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
