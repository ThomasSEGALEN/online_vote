<script setup>
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import route from "ziggy-js";
import Checkbox from "../../../Components/Checkbox.vue";
import InputError from "../../../Components/InputError.vue";
import InputLabel from "../../../Components/InputLabel.vue";
import RadioInput from "../../../Components/RadioInput.vue";
import SecondaryButton from "../../../Components/SecondaryButton.vue";
import TextInput from "../../../Components/TextInput.vue";

const props = defineProps(["form", "civilities", "roles", "groups"]);

const lastNameInput = ref(null);
const firstNameInput = ref(null);
const emailInput = ref(null);
const passwordInput = ref(null);

onMounted(() => lastNameInput.value?.focus());

function nextPage() {
    const data = usePage().props?.data; //as any;
    props.form.permissions = data.permissions;
    props.form.post(route("users.prestore"), {
        onSuccess: () => {
            props.form.formStep++;
        },
        onError: () => {
            if (props.form.errors.password) {
                props.form.reset("password");
                passwordInput.value?.focus();
            }
            if (props.form.errors.email) {
                props.form.reset("email");
                emailInput.value?.focus();
            }
            if (props.form.errors.first_name) {
                firstNameInput.value?.focus();
            }
            if (props.form.errors.last_name) {
                lastNameInput.value?.focus();
            }
        },
    });
}
</script>

<template>
    <form @submit.prevent="nextPage">
        <div class="w-full flex flex-col md:flex-row">
            <div class="flex flex-col w-full">
                <div>
                    <span class="block font-medium text-md text-gray-700">
                        Civilité
                    </span>

                    <div class="mt-1 space-x-4">
                        <div
                            class="inline-flex items-center space-x-1 ml-0.5"
                            v-for="civility in props.civilities"
                            :key="civility.id"
                        >
                            <RadioInput
                                type="radio"
                                name="civility"
                                :id="`civility-${civility.id}`"
                                v-model="form.civility"
                                :value="civility.id"
                                :checked="civility.id === form.civility"
                            />

                            <InputLabel
                                :for="`civility-${civility.id}`"
                                :value="civility.name"
                            />
                        </div>
                    </div>

                    <InputError class="mt-2" :message="form.errors.civility" />
                </div>

                <div class="mt-4">
                    <InputLabel for="last_name" value="Nom" />

                    <TextInput
                        id="last_name"
                        ref="lastNameInput"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.last_name"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.last_name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="first_name" value="Prénom" />

                    <TextInput
                        id="first_name"
                        ref="firstNameInput"
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

                <div class="mt-4">
                    <InputLabel for="email" value="Adresse e-mail" />

                    <TextInput
                        id="email"
                        ref="emailInput"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Mot de passe" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
            </div>

            <div class="w-full mt-4 md:w-2/3 md:ml-8 md:mt-0">
                <div>
                    <span class="block font-medium text-md text-gray-700">
                        Rôles
                    </span>

                    <div
                        class="mt-1 max-h-48 overflow-y-auto overflow-x-hidden"
                    >
                        <div
                            class="flex items-center space-x-1 ml-0.5"
                            v-for="role in props.roles"
                            :key="role.id"
                        >
                            <RadioInput
                                type="radio"
                                name="role"
                                :id="`role-${role.id}`"
                                v-model="form.role"
                                :value="role.id"
                                :checked="role.id === form.role"
                            />

                            <InputLabel
                                :for="`role-${role.id}`"
                                :value="role.name"
                            />
                        </div>
                    </div>

                    <InputError class="mt-2" :message="form.errors.role" />
                </div>

                <div class="mt-4">
                    <span class="block font-medium text-md text-gray-700">
                        Groupes
                    </span>

                    <div
                        class="mt-1 max-h-48 overflow-y-auto overflow-x-hidden"
                    >
                        <div
                            class="flex items-center space-x-1 ml-0.5"
                            v-for="group in props.groups"
                            :key="group.id"
                        >
                            <Checkbox
                                name="groups"
                                :id="`group-${group.id}`"
                                v-model="form.groups"
                                :value="group.id"
                            />

                            <InputLabel
                                :for="`group-${group.id}`"
                                :value="group.name"
                            />
                        </div>
                    </div>

                    <InputError class="mt-2" :message="form.errors.groups" />
                </div>
            </div>
        </div>

        <div class="flex mt-4 justify-end">
            <SecondaryButton type="submit"> Suivant </SecondaryButton>
        </div>
    </form>
</template>
