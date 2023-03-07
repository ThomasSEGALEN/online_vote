<script setup lang="ts">
import { computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import route from "ziggy-js";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(
    () => props.status === "verification-link-sent"
);
</script>

<template>
    <GuestLayout>
        <Head title="Vérification" />

        <h1 class="text-center block font-bold text-2xl text-gray-700">
            Vérification
        </h1>

        <div class="mt-8 mb-4 text-sm text-gray-700">
            Avant de commencer, pourriez-vous vérifier votre adresse e-mail en
            cliquant sur le lien que nous venons de vous envoyer ? Si vous
            n'avez pas reçu l'e-mail, nous pouvons vous en envoyer un autre.
        </div>

        <div
            class="mb-4 font-medium text-sm text-green-600"
            v-if="verificationLinkSent"
        >
            Un nouveau lien de vérification vous a été envoyé
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Envoyer
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="underline text-sm text-gray-600 hover:text-gray-900 focus:text-gray-900 focus:outline-none"
                >
                    Déconnexion
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
