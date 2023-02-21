<script setup lang="ts">
import { onMounted, ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import route from "ziggy-js";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const emailInput = ref<HTMLInputElement>();

defineProps({
    status: String,
});

const form = useForm({
    email: "",
});

onMounted(() => emailInput.value?.focus());

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <GuestLayout>
        <Head title="Mot de passe oublié" />

        <h1 class="text-center block font-bold text-2xl text-gray-700">
            Mot de passe oublié
        </h1>

        <div class="mt-8 mb-4 text-sm text-gray-700">
            Vous avez oublié votre mot de passe ? Veuillez nous communiquer
            l'adresse e-mail associée et nous vous enverrons un lien de
            réinitialisation du mot de passe.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Adresse e-mail" />

                <TextInput
                    id="email"
                    ref="emailInput"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    autocomplete="email"
                    required
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Envoyer
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
