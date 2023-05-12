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
import FileInput from "@/Components/FileInput.vue";
import Modal from "@/Components/Modal.vue";
import Pagination from "@/Components/Pagination.vue";
import ResponsivePagination from "@/Components/ResponsivePagination.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import UpdateIcon from "@/Components/UpdateIcon.vue";

const props = defineProps({
    roles: {
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
const confirmingRoleDeletion = ref<boolean>(false);
const roleId = ref<number>();
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

const clickFile = () => fileInput.value?.click();

const importFile = (event: Event) => {
    const file = (<HTMLInputElement>event.target).files?.[0];

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

        <div class="p-4 md:p-6">
            <div class="flex flex-wrap flex-row items-center justify-between">
                <div
                    v-if="can.createRoles"
                    class="flex items-center space-x-2 mb-2"
                >
                    <Link
                        class="inline-flex px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                        :href="route('roles.create')"
                    >
                        Nouveau rôle
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
                        :href="route('roles.export')"
                        class="inline-flex items-center px-2 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        <FileExportIcon />
                    </a>
                </div>

                <TextInput
                    id="search"
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
                            v-for="role in roles.data as Array<Role>"
                            :key="role.id"
                            class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100"
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
                                    v-if="can.updateRoles"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                                    :href="route('roles.edit', role.id)"
                                >
                                    <UpdateIcon />
                                </Link>

                                <DangerButton
                                    v-if="can.deleteRoles"
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
                v-if="roles.total > roles.per_page"
                class="hidden md:flex"
                :to="roles.to"
                :from="roles.from"
                :total="roles.total"
                :links="roles.links"
            />

            <ResponsivePagination
                v-if="roles.total > roles.per_page"
                class="flex md:hidden"
                :to="roles.to"
                :from="roles.from"
                :total="roles.total"
                :links="roles.links"
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
