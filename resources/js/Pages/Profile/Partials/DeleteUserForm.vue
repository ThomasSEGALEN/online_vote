<script setup lang="ts">
import { nextTick, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import route from "ziggy-js";
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const confirmingUserDeletion = ref<boolean>(false);
const passwordInput = ref<HTMLInputElement>();

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};
</script>

<template>
    <div>
        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-800">
                    Suppression du compte
                </h2>

                <p class="mt-1 text-sm text-gray-700">
                    Une fois que votre compte est supprimé, toutes ses données
                    seront définitivement supprimées.
                </p>
            </header>

            <DangerButton @click="confirmUserDeletion">
                Désactiver
            </DangerButton>

            <Modal :show="confirmingUserDeletion" @close="closeModal">
                <form class="p-6">
                    <h2 class="text-lg font-medium text-gray-800">
                        Êtes-vous sûr de vouloir supprimer votre compte ?
                    </h2>

                    <p class="mt-1 text-sm text-gray-700">
                        Afin de supprimer votre compte, veuillez saisir votre
                        mot de passe.
                    </p>

                    <div class="mt-6">
                        <input hidden type="text" autocomplete="username" />

                        <InputLabel
                            for="password"
                            value="Mot de passe"
                            class="sr-only"
                        />

                        <TextInput
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-3/4"
                            placeholder="Mot de passe"
                            autocomplete="current-password"
                            autofocus
                            @keyup.enter="deleteUser"
                        />

                        <InputError
                            :message="form.errors.password"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal">
                            Annuler
                        </SecondaryButton>

                        <DangerButton
                            class="ml-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="deleteUser"
                        >
                            Supprimer
                        </DangerButton>
                    </div>
                </form>
            </Modal>
        </section>
    </div>
</template>
