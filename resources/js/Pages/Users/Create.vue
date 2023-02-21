<script setup lang="ts">
import { onMounted, ref } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import route from "ziggy-js";
import userForm from "@/Composables/userForm";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BackIcon from "@/Components/BackIcon.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import RadioInput from "@/Components/RadioInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps(["civilities", "roles", "groups"]);

const lastNameInput = ref<HTMLInputElement>();
const firstNameInput = ref<HTMLInputElement>();
const emailInput = ref<HTMLInputElement>();
const passwordInput = ref<HTMLInputElement>();
const formStep = ref<number>(1);

const form = userForm();

onMounted(() => lastNameInput.value?.focus());

const nextStep = () => {
    form.post(route("users.prestore"), {
        onSuccess: () => {
            formStep.value++;
            form.permissions = (usePage().props?.data as any).permissions;
        },
        onError: () => {
            if (form.errors.password) {
                form.reset("password");
                passwordInput.value?.focus();
            }
            if (form.errors.email) {
                form.reset("email");
                emailInput.value?.focus();
            }
            if (form.errors.first_name) {
                form.reset("first_name");
                firstNameInput.value?.focus();
            }
            if (form.errors.last_name) {
                form.reset("last_name");
                lastNameInput.value?.focus();
            }
        },
    });
};

const previousStep = () => formStep.value--;

const submit = () => form.post(route("users.store"));
</script>

<template>
    <Head title="Utilisateurs" />

    <AuthenticatedLayout>
        <template #header>
            <div class="inline-flex items-center">
                <Link
                    :href="route('users.index')"
                    class="text-sm text-gray-700 dark:text-gray-500 underline"
                >
                    <BackIcon />
                </Link>
                <h2
                    class="ml-2 font-semibold text-xl text-gray-800 leading-tight"
                >
                    Création d'un utilisateur
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div v-if="formStep === 1">
                                <div class="w-full flex flex-col md:flex-row">
                                    <div class="flex flex-col w-full">
                                        <div>
                                            <span
                                                class="block font-medium text-md text-gray-700"
                                            >
                                                Civilité
                                            </span>

                                            <div class="mt-1 space-x-4">
                                                <div
                                                    class="inline-flex items-center space-x-1 ml-0.5"
                                                    v-for="civility in civilities"
                                                    :key="civility.id"
                                                >
                                                    <RadioInput
                                                        type="radio"
                                                        name="civility"
                                                        :id="`civility-${civility.id}`"
                                                        v-model="form.civility"
                                                        :value="civility.id"
                                                        :checked="
                                                            civility.id ===
                                                            form.civility
                                                        "
                                                    />

                                                    <InputLabel
                                                        :for="`civility-${civility.id}`"
                                                        :value="civility.name"
                                                    />
                                                </div>
                                            </div>

                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.civility"
                                            />
                                        </div>

                                        <div class="mt-4">
                                            <InputLabel
                                                for="last_name"
                                                value="Nom"
                                            />

                                            <TextInput
                                                id="last_name"
                                                ref="lastNameInput"
                                                type="text"
                                                class="mt-1 block w-full"
                                                v-model="form.last_name"
                                                autocomplete="familiy-name"
                                                required
                                            />

                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.last_name"
                                            />
                                        </div>

                                        <div class="mt-4">
                                            <InputLabel
                                                for="first_name"
                                                value="Prénom"
                                            />

                                            <TextInput
                                                id="first_name"
                                                ref="firstNameInput"
                                                type="text"
                                                class="mt-1 block w-full"
                                                v-model="form.first_name"
                                                autocomplete="given-name"
                                                required
                                            />

                                            <InputError
                                                class="mt-2"
                                                :message="
                                                    form.errors.first_name
                                                "
                                            />
                                        </div>

                                        <div class="mt-4">
                                            <InputLabel
                                                for="email"
                                                value="Adresse e-mail"
                                            />

                                            <TextInput
                                                id="email"
                                                ref="emailInput"
                                                type="email"
                                                class="mt-1 block w-full"
                                                v-model="form.email"
                                                autocomplete="email"
                                                required
                                            />

                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.email"
                                            />
                                        </div>

                                        <div class="mt-4">
                                            <InputLabel
                                                for="password"
                                                value="Mot de passe"
                                            />

                                            <TextInput
                                                id="password"
                                                ref="passwordInput"
                                                type="password"
                                                class="mt-1 block w-full"
                                                v-model="form.password"
                                                autocomplete="new-password"
                                                required
                                            />

                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.password"
                                            />
                                        </div>
                                    </div>

                                    <div
                                        class="w-full mt-4 md:w-2/3 md:ml-8 md:mt-0"
                                    >
                                        <div>
                                            <span
                                                class="block font-medium text-md text-gray-700"
                                            >
                                                Rôles
                                            </span>

                                            <div
                                                class="mt-1 max-h-48 overflow-y-auto overflow-x-hidden"
                                            >
                                                <div
                                                    class="flex items-center space-x-1 ml-0.5"
                                                    v-for="role in roles"
                                                    :key="role.id"
                                                >
                                                    <RadioInput
                                                        type="radio"
                                                        name="role"
                                                        :id="`role-${role.id}`"
                                                        v-model="form.role"
                                                        :value="role.id"
                                                        :checked="
                                                            role.id ===
                                                            form.role
                                                        "
                                                    />

                                                    <InputLabel
                                                        :for="`role-${role.id}`"
                                                        :value="role.name"
                                                    />
                                                </div>
                                            </div>

                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.role"
                                            />
                                        </div>

                                        <div class="mt-4">
                                            <span
                                                class="block font-medium text-md text-gray-700"
                                            >
                                                Groupes
                                            </span>

                                            <div
                                                class="mt-1 max-h-48 overflow-y-auto overflow-x-hidden"
                                            >
                                                <div
                                                    class="flex items-center space-x-1 ml-0.5"
                                                    v-for="group in groups"
                                                    :key="group.id"
                                                >
                                                    <Checkbox
                                                        name="groups"
                                                        :id="`group-${group.id}`"
                                                        v-model="form.groups"
                                                        :value="group.id"
                                                    />

                                                    <InputLabel
                                                        :for="`group-${group.id}`"
                                                        :value="group.name"
                                                    />
                                                </div>
                                            </div>

                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.groups"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="flex mt-4 justify-end">
                                    <SecondaryButton @click="nextStep">
                                        Suivant
                                    </SecondaryButton>
                                </div>
                            </div>

                            <div v-if="formStep === 2">
                                <span
                                    class="block font-medium text-md text-gray-700"
                                >
                                    Permissions
                                </span>
                                <div class="mt-1">
                                    <table class="min-w-full">
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
                                        <tbody v-for="index in 4" :key="index">
                                            <tr class="bg-white border-b">
                                                <td
                                                    v-if="index === 1"
                                                    class="text-md text-gray-900 font-bold px-6 py-4"
                                                >
                                                    Utilisateurs
                                                </td>
                                                <td
                                                    v-if="index === 2"
                                                    class="text-md text-gray-900 font-bold px-6 py-4"
                                                >
                                                    Rôles
                                                </td>
                                                <td
                                                    v-if="index === 3"
                                                    class="text-md text-gray-900 font-bold px-6 py-4"
                                                >
                                                    Groupes
                                                </td>
                                                <td
                                                    v-if="index === 4"
                                                    class="text-md text-gray-900 font-bold px-6 py-4"
                                                >
                                                    Séances
                                                </td>

                                                <td
                                                    v-for="i in index === 1
                                                        ? [1, 2, 3, 4, 5]
                                                        : index === 2
                                                        ? [6, 7, 8, 9, 10]
                                                        : index === 3
                                                        ? [11, 12, 13, 14, 15]
                                                        : [16, 17, 18, 19, 20]"
                                                    :key="i"
                                                    class="px-6 py-4"
                                                >
                                                    <Checkbox
                                                        :name="
                                                            index === 1
                                                                ? 'users'
                                                                : index === 2
                                                                ? 'roles'
                                                                : index === 3
                                                                ? 'groups'
                                                                : 'sessions'
                                                        "
                                                        :id="
                                                            index === 1
                                                                ? `user-${i}`
                                                                : index === 2
                                                                ? `role-${i}`
                                                                : index === 3
                                                                ? `group-${i}`
                                                                : `session-${i}`
                                                        "
                                                        v-model="
                                                            form.permissions
                                                        "
                                                        :value="i"
                                                        :checked="
                                                            form.permissions.includes(
                                                                i
                                                            )
                                                        "
                                                    />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="flex mt-4 justify-between">
                                    <SecondaryButton @click="previousStep">
                                        Précédent
                                    </SecondaryButton>

                                    <PrimaryButton
                                        :class="{
                                            'opacity-25': form.processing,
                                        }"
                                        :disabled="form.processing"
                                    >
                                        Créer
                                    </PrimaryButton>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
