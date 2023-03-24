<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";
import route from "ziggy-js";
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    canResetPassword: Boolean,
    status: {
        type: String,
        default: () => "",
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Connexion" />

        <h1 class="text-center block font-bold text-2xl text-gray-700">
            Se connecter
        </h1>

        <div v-if="status" class="mt-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div class="mt-8">
                <InputLabel for="email" value="Adresse e-mail" />

                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    autocomplete="email"
                    autofocus
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
                    autocomplete="current-password"
                    required
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="inline-flex items-center mt-4 space-x-1">
                <Checkbox
                    id="remember"
                    v-model="form.remember"
                    name="remember"
                />

                <InputLabel for="remember" value="Se souvenir de moi" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-800 focus:text-gray-800 focus:outline-none"
                >
                    Mot de passe oublié ?
                </Link>

                <PrimaryButton
                    class="ml-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Connexion
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
