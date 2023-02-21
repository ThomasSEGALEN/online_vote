<script setup lang="ts">
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import route from "ziggy-js";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const { user } = usePage().props?.auth as any;

const lastNameInput = ref<HTMLInputElement>();

const form = useForm({
    last_name: user.last_name,
    first_name: user.first_name,
    email: user.email,
});

onMounted(() => lastNameInput.value?.focus());
</script>

<template>
    <div>
        <section>
            <header>
                <h2 class="text-lg font-medium text-gray-800">
                    Informations du profil
                </h2>

                <p class="mt-1 text-sm text-gray-700">
                    Mettez à jour les informations du profil et l'adresse e-mail
                    de votre compte.
                </p>
            </header>

            <form
                @submit.prevent="form.patch(route('profile.update'))"
                class="mt-6 space-y-6"
            >
                <div class="mt-4 flex flex-col sm:flex-row sm:space-x-8">
                    <div class="w-full sm:w-1/2">
                        <InputLabel for="last_name" value="Nom" />

                        <TextInput
                            id="last_name"
                            ref="lastNameInput"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.last_name"
                            required
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.last_name"
                        />
                    </div>

                    <div class="w-full mt-4 sm:w-1/2 sm:mt-0">
                        <InputLabel for="first_name" value="Prénom" />

                        <TextInput
                            id="first_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.first_name"
                            required
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.first_name"
                        />
                    </div>
                </div>

                <div>
                    <InputLabel for="email" value="Adresse e-mail" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div
                    v-if="
                        props.mustVerifyEmail && user.email_verified_at === null
                    "
                >
                    <p class="text-sm mt-2 text-gray-800">
                        Votre adresse e-mail n'est pas vérifiée.
                        <Link
                            :href="route('verification.send')"
                            :method="('post' as Method)"
                            as="button"
                            class="underline text-sm text-gray-600 hover:text-gray-800 focus:text-gray-800 focus:outline-none"
                        >
                            Cliquez ici pour renvoyer l'e-mail de vérification.
                        </Link>
                    </p>

                    <div
                        v-show="props.status === 'verification-link-sent'"
                        class="mt-2 font-medium text-sm text-green-600"
                    >
                        Un nouveau lien de vérification vous a été envoyé
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton :disabled="form.processing">
                        Enregistrer
                    </PrimaryButton>

                    <Transition
                        enter-from-class="opacity-0"
                        leave-to-class="opacity-0"
                        class="transition ease-in-out"
                    >
                        <p
                            v-if="form.recentlySuccessful"
                            class="text-sm text-gray-700"
                        >
                            Enregistré
                        </p>
                    </Transition>
                </div>
            </form>
        </section>
    </div>
</template>
