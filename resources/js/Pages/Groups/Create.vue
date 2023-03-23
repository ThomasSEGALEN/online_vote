<script setup lang="ts">
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import route from "ziggy-js";
import groupForm from "@/Composables/groupForm";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BackIcon from "@/Components/BackIcon.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Multiselect from "@vueform/multiselect";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    users: {
        type: Object,
        default: () => {
            return {};
        },
    },
});

const nameInput = ref<HTMLInputElement>();

const form = groupForm();

const submit = () => {
    form.post(route("groups.store"), {
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
    <Head title="Groupes" />

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
                    Création d'un groupe
                </h2>
            </div>
        </template>

        <div class="p-4 md:p-6">
            <form @submit.prevent="submit">
                <div class="w-full flex flex-col">
                    <div class="flex flex-col w-full max-w-md">
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
                                Utilisateurs
                            </span>

                            <div class="mt-1 max-w-md">
                                <Multiselect
                                    v-model="form.users"
                                    mode="tags"
                                    label="name"
                                    value-prop="id"
                                    :close-on-select="false"
                                    :searchable="true"
                                    no-results-text="Aucun résultat"
                                    no-options-text="Aucune option"
                                    :object="true"
                                    :options="users"
                                    :classes="{
                                        container:
                                            'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer shadow-sm border-2 border-gray-300 rounded-md bg-white text-base leading-snug outline-none',
                                        containerActive:
                                            'ring-2 ring-indigo-100 border-indigo-500 outline-none transition duration-150 ease-in-out',
                                        tag: 'bg-indigo-100 text-indigo-600 text-sm font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
                                        tagsSearch:
                                            'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full text-gray-700',
                                        clear: 'pr-3.5 relative z-10 opacity-40 transition duration-300 flex-shrink-0 flex-grow-0 flex hover:opacity-100 rtl:pr-0 rtl:pl-3.5',
                                    }"
                                />
                            </div>

                            <InputError
                                class="mt-2"
                                :message="form.errors.users"
                            />
                        </div>
                    </div>
                </div>

                <div class="mt-6">
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

<style src="@vueform/multiselect/themes/default.css"></style>