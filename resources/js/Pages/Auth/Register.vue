<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";
import route from "ziggy-js";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import RadioInput from "@/Components/RadioInput.vue";
import TextInput from "@/Components/TextInput.vue";

const form = useForm({
    civility: 1,
    last_name: "",
    first_name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Inscription" />

        <h1 class="text-center block font-bold text-2xl text-gray-700">
            S'inscrire
        </h1>

        <form @submit.prevent="submit">
            <div class="mt-8">
                <span class="block font-medium text-md text-gray-700">
                    Civilité
                </span>

                <div class="mt-1 space-x-4">
                    <div class="inline-flex items-center space-x-1">
                        <RadioInput
                            id="civility-man"
                            v-model="form.civility"
                            name="civility"
                            :value="1"
                            checked
                        />

                        <InputLabel for="civility-man" value="Monsieur" />
                    </div>

                    <div class="inline-flex items-center space-x-1">
                        <RadioInput
                            id="civility-woman"
                            v-model="form.civility"
                            name="civility"
                            :value="2"
                        />

                        <InputLabel for="civility-woman" value="Madame" />
                    </div>
                </div>

                <InputError class="mt-2" :message="form.errors.civility" />
            </div>

            <div class="mt-4 flex flex-col sm:flex-row sm:space-x-8">
                <div class="w-full sm:w-1/2">
                    <InputLabel for="last_name" value="Nom" />

                    <TextInput
                        id="last_name"
                        v-model="form.last_name"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="family-name"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.last_name" />
                </div>

                <div class="w-full mt-4 sm:w-1/2 sm:mt-0">
                    <InputLabel for="first_name" value="Prénom" />

                    <TextInput
                        id="first_name"
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
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Adresse e-mail" />

                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    autocomplete="email"
                    required
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Mot de passe" />

                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    required
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirmation mot de passe"
                />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    required
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-800 focus:text-gray-800 focus:outline-none"
                >
                    Déjà inscrit ?
                </Link>

                <PrimaryButton
                    class="ml-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Inscription
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
