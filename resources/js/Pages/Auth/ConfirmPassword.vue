<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import route from "ziggy-js";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const form = useForm({
    password: "",
});

const submit = () => {
    form.post(route("password.confirm"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirmation" />

        <h1 class="text-center block font-bold text-2xl text-gray-700">
            Confirmation
        </h1>

        <div class="mt-8 mb-4 text-sm text-gray-700">
            Veuillez confirmer votre mot de passe avant de continuer.
        </div>

        <form @submit.prevent="submit">
            <div>
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

            <div class="flex justify-end mt-4">
                <PrimaryButton
                    class="ml-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Confirmer
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
