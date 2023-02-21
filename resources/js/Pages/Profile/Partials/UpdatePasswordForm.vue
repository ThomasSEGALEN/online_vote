<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import route from "ziggy-js";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const passwordInput = ref<HTMLInputElement>();
const currentPasswordInput = ref<HTMLInputElement>();

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

onMounted(() => currentPasswordInput.value?.focus());

const updatePassword = () => {
    form.put(route("password.update"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <div>
        <section>
            <header>
                <h2 class="text-lg font-medium text-gray-800">
                    Modification du mot de passe
                </h2>

                <p class="mt-1 text-sm text-gray-700">
                    Assurez-vous que votre compte utilise un mot de passe long
                    et aléatoire pour rester sécurisé.
                </p>
            </header>

            <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
                <div>
                    <input hidden type="text" autocomplete="username" />

                    <InputLabel
                        for="current_password"
                        value="Mot de passe actuel"
                    />

                    <TextInput
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="current-password"
                    />

                    <InputError
                        :message="form.errors.current_password"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel for="password" value="Nouveau mot de passe" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="new-password"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div>
                    <InputLabel
                        for="password_confirmation"
                        value="Confirmation nouveau mot de passe"
                    />

                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="new-password"
                    />

                    <InputError
                        :message="form.errors.password_confirmation"
                        class="mt-2"
                    />
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
