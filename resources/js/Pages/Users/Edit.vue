<script setup lang="ts">
import { ref, toRefs } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import route from "ziggy-js";
import userForm from "@/Composables/userForm";
import { Civility, Group, Role } from "@/types/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BackIcon from "@/Components/BackIcon.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Multiselect from "@vueform/multiselect";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import RadioInput from "@/Components/RadioInput.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    user: {
        type: Object,
        default: () => {
            return {};
        },
    },
    civilities: {
        type: Array<Civility>,
        default: () => [],
    },
    roles: {
        type: Array<Role>,
        default: () => [],
    },
    groups: {
        type: Array<Group>,
        default: () => [],
    },
});

const lastNameInput = ref<HTMLInputElement>();
const firstNameInput = ref<HTMLInputElement>();
const emailInput = ref<HTMLInputElement>();
const passwordInput = ref<HTMLInputElement>();

const { user, groups } = toRefs(props);

const form = userForm({
    civility: user.value.civility_id,
    last_name: user.value.last_name,
    first_name: user.value.first_name,
    email: user.value.email,
    role: user.value.role_id,
    groups: groups.value
        .filter((group: Group) => user.value.groups.includes(group.id))
        .map((g) => g.id),
});

const submit = () => {
    form.put(route("users.update", user.value.id), {
        onError: () => {
            if (form.errors.password) passwordInput.value?.focus();
            if (form.errors.email) emailInput.value?.focus();
            if (form.errors.first_name) firstNameInput.value?.focus();
            if (form.errors.last_name) lastNameInput.value?.focus();
        },
    });
};
</script>

<template>
    <Head title="Utilisateurs" />

    <AuthenticatedLayout>
        <template #header>
            <div class="inline-flex items-center">
                <Link
                    :href="route('users.index')"
                    class="text-sm text-gray-700 underline"
                >
                    <BackIcon />
                </Link>
                <h2
                    class="ml-2 font-semibold text-xl text-gray-800 leading-tight"
                >
                    Modification d'un utilisateur
                </h2>
            </div>
        </template>

        <div class="p-4 lg:p-6 max-w-5xl">
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <span class="block font-medium text-md text-gray-700">
                        Civilité
                    </span>

                    <div class="mt-1 space-x-4">
                        <div
                            v-for="civility in civilities"
                            :key="civility.id"
                            class="inline-flex items-center space-x-1 ml-0.5"
                        >
                            <RadioInput
                                :id="`civility-${civility.id}`"
                                v-model="form.civility"
                                :value="civility.id"
                            />

                            <InputLabel
                                :for="`civility-${civility.id}`"
                                :value="civility.name"
                            />
                        </div>
                    </div>

                    <InputError class="mt-2" :message="form.errors.civility" />
                </div>

                <div
                    class="w-full flex flex-col lg:flex-row lg:space-x-8 lg:justify-between"
                >
                    <div class="flex flex-col w-full max-w-md">
                        <div>
                            <InputLabel for="last_name" value="Nom" />

                            <TextInput
                                id="last_name"
                                ref="lastNameInput"
                                v-model="form.last_name"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="familiy-name"
                                autofocus
                                required
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.last_name"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="first_name" value="Prénom" />

                            <TextInput
                                id="first_name"
                                ref="firstNameInput"
                                v-model="form.first_name"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="given-name"
                                required
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.first_name"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="email" value="Adresse e-mail" />

                            <TextInput
                                id="email"
                                ref="emailInput"
                                v-model="form.email"
                                type="email"
                                class="mt-1 block w-full"
                                autocomplete="email"
                                required
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password" value="Mot de passe" />

                            <TextInput
                                id="password"
                                ref="passwordInput"
                                v-model="form.password"
                                type="password"
                                class="mt-1 block w-full"
                                autocomplete="new-password"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.password"
                            />
                        </div>
                    </div>

                    <div class="w-full mt-4 lg:ml-8 lg:mt-0 max-w-md">
                        <div>
                            <InputLabel for="role" value="Rôles" />

                            <div class="mt-1">
                                <Multiselect
                                    id="role"
                                    v-model="form.role"
                                    mode="single"
                                    label="name"
                                    value-prop="id"
                                    :close-on-select="false"
                                    :searchable="true"
                                    no-results-text="Aucun résultat"
                                    no-options-text="Aucune option"
                                    :options="roles"
                                    :classes="{
                                        container:
                                            'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer shadow-sm border-2 border-gray-300 rounded-md bg-white text-base leading-snug outline-none',
                                        containerActive:
                                            'ring-2 ring-indigo-100 border-indigo-500 outline-none transition duration-150 ease-in-out',
                                        tag: 'bg-indigo-100 text-indigo-600 text-sm font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
                                        tagsSearch:
                                            'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full text-gray-700',
                                        clear: 'pr-3.5 relative z-10 opacity-40 transition duration-300 flex-shrink-0 flex-grow-0 flex hover:opacity-100 rtl:pr-0 rtl:pl-3.5',
                                        option: 'flex items-center justify-start box-border text-left cursor-pointer text-base leading-snug py-2 px-3',
                                        optionPointed:
                                            'text-gray-800 bg-gray-100',
                                        optionSelected:
                                            'text-white bg-indigo-500',
                                        optionSelectedPointed:
                                            'text-white bg-indigo-500 opacity-90',
                                    }"
                                />
                            </div>

                            <InputError
                                class="mt-2"
                                :message="form.errors.role"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="groups" value="Groupes" />

                            <div class="mt-1">
                                <Multiselect
                                    id="groups"
                                    v-model="form.groups"
                                    mode="tags"
                                    label="name"
                                    value-prop="id"
                                    :close-on-select="false"
                                    :searchable="true"
                                    no-results-text="Aucun résultat"
                                    no-options-text="Aucune option"
                                    :options="groups"
                                    :classes="{
                                        container:
                                            'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer shadow-sm border-2 border-gray-300 rounded-md bg-white text-base leading-snug outline-none',
                                        containerActive:
                                            'ring-2 ring-indigo-100 border-indigo-500 outline-none transition duration-150 ease-in-out',
                                        tag: 'bg-indigo-100 text-indigo-600 text-sm font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
                                        tagsSearch:
                                            'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full text-gray-700',
                                        clear: 'pr-3.5 relative z-10 opacity-40 transition duration-300 flex-shrink-0 flex-grow-0 flex hover:opacity-100 rtl:pr-0 rtl:pl-3.5',
                                        option: 'flex items-center justify-start box-border text-left cursor-pointer text-base leading-snug py-2 px-3',
                                        optionPointed:
                                            'text-gray-800 bg-gray-100',
                                        optionSelected:
                                            'text-white bg-indigo-500',
                                        optionSelectedPointed:
                                            'text-white bg-indigo-500 opacity-90',
                                    }"
                                />
                            </div>

                            <InputError
                                class="mt-2"
                                :message="form.errors.groups"
                            />
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex lg:justify-end">
                    <PrimaryButton
                        :class="{
                            'opacity-25': form.processing,
                        }"
                        :disabled="form.processing"
                    >
                        Modifier
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
