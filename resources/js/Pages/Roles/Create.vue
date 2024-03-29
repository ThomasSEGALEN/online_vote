<script setup lang="ts">
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import route from "ziggy-js";
import roleForm from "@/Composables/roleForm";
import { Permission } from "@/types/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BackIcon from "@/Components/BackIcon.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    permissions: {
        type: Array<Permission>,
        default: () => [],
    },
});

const nameInput = ref<HTMLInputElement>();

const form = roleForm({});

const submit = () => {
    form.post(route("roles.store"), {
        onError: () => {
            if (form.errors.name) {
                form.reset("name");
                nameInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <Head title="Rôles" />

    <AuthenticatedLayout>
        <template #header>
            <div class="inline-flex items-center">
                <Link
                    :href="route('roles.index')"
                    class="text-sm text-gray-700 underline"
                >
                    <BackIcon />
                </Link>

                <h2
                    class="ml-2 font-semibold text-xl text-gray-800 leading-tight"
                >
                    Création d'un rôle
                </h2>
            </div>
        </template>

        <div class="p-4 lg:p-6 max-w-screen-md mx-auto">
            <form @submit.prevent="submit">
                <div class="w-full flex flex-col">
                    <div class="flex flex-col w-full">
                        <div>
                            <InputLabel for="name" value="Nom" />

                            <TextInput
                                id="name"
                                ref="nameInput"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="name"
                                autofocus
                                required
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                        </div>
                    </div>

                    <div class="w-full mt-4">
                        <div>
                            <span
                                class="block font-medium text-md text-gray-700"
                            >
                                Permissions
                            </span>

                            <div
                                class="flex flex-col overflow-x-auto min-w-full mt-4 mb-6"
                            >
                                <table>
                                    <thead class="bg-white border-b">
                                        <tr class="border-b bg-gray-100">
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
                                                Lister
                                            </th>

                                            <th
                                                scope="col"
                                                class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                                            >
                                                Consulter
                                            </th>

                                            <th
                                                scope="col"
                                                class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                                            >
                                                Créer
                                            </th>

                                            <th
                                                scope="col"
                                                class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                                            >
                                                Modifier
                                            </th>

                                            <th
                                                scope="col"
                                                class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                                            >
                                                Supprimer
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr class="bg-white border-b">
                                            <td
                                                class="text-md text-gray-900 font-bold px-6 py-4"
                                            >
                                                Utilisateurs
                                            </td>

                                            <td
                                                v-for="index in 5"
                                                :key="index"
                                                class="text-md text-gray-900 font-semibold px-6 py-4"
                                            >
                                                <Checkbox
                                                    :id="`permissionInput-${index}`"
                                                    v-model="form.permissions"
                                                    :value="index"
                                                />
                                            </td>
                                        </tr>

                                        <tr class="border-b bg-gray-100">
                                            <td
                                                class="text-md text-gray-900 font-bold px-6 py-4"
                                            >
                                                Rôles
                                            </td>

                                            <td
                                                v-for="index in 5"
                                                :key="index"
                                                class="text-md text-gray-900 font-semibold px-6 py-4"
                                            >
                                                <Checkbox
                                                    :id="`permissionInput-${
                                                        index + 5
                                                    }`"
                                                    v-model="form.permissions"
                                                    :value="index + 5"
                                                />
                                            </td>
                                        </tr>

                                        <tr class="border-b bg-white">
                                            <td
                                                class="text-md text-gray-900 font-bold px-6 py-4"
                                            >
                                                Groupes
                                            </td>

                                            <td
                                                v-for="index in 5"
                                                :key="index"
                                                class="text-md text-gray-900 font-semibold px-6 py-4"
                                            >
                                                <Checkbox
                                                    :id="`permissionInput-${
                                                        index + 10
                                                    }`"
                                                    v-model="form.permissions"
                                                    :value="index + 10"
                                                />
                                            </td>
                                        </tr>

                                        <tr class="border-b bg-gray-100">
                                            <td
                                                class="text-md text-gray-900 font-bold px-6 py-4"
                                            >
                                                Séances
                                            </td>

                                            <td
                                                v-for="index in 5"
                                                :key="index"
                                                class="text-md text-gray-900 font-semibold px-6 py-4"
                                            >
                                                <Checkbox
                                                    :id="`permissionInput-${
                                                        index + 15
                                                    }`"
                                                    v-model="form.permissions"
                                                    :value="index + 15"
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <InputError
                                class="mt-2"
                                :message="form.errors.permissions"
                            />
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <PrimaryButton
                        :class="{
                            'opacity-25': form.processing,
                        }"
                        :disabled="form.processing"
                    >
                        Créer
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
