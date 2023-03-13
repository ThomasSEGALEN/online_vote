<script setup lang="ts">
import { onMounted, ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import route from "ziggy-js";
import roleForm from "@/Composables/roleForm";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BackIcon from "@/Components/BackIcon.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps(["civilities", "roles", "groups"]);

const nameInput = ref<HTMLInputElement>();

const form = roleForm();

onMounted(() => nameInput.value?.focus());

const submit = () => {
    form.post(route("users.store"), {
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
    <Head title="Rôles" />

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
                    Création d'un rôle
                </h2>
            </div>
        </template>

        <div class="p-12">
            <form @submit.prevent="submit">
                <div class="w-full flex flex-col md:flex-row">
                    <div class="flex flex-col w-full">
                        <div>
                            <InputLabel for="name" value="Nom" />

                            <TextInput
                                id="name"
                                ref="nameInput"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                autocomplete="familiy-name"
                                required
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                        </div>
                    </div>

                    <div class="w-full mt-4 md:w-2/3 md:ml-8 md:mt-0">
                        <div>
                            <span
                                class="block font-medium text-md text-gray-700"
                            >
                                Permissions
                            </span>

                            <InputError
                                class="mt-2"
                                :message="form.errors.permissions"
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
