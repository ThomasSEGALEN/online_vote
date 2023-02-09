<script setup>
import { usePage } from "@inertiajs/vue3";
import route from "ziggy-js";
import Checkbox from "../../Components/Checkbox.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";

const props = defineProps(["form", "permissions"]);

const data = usePage().props?.data; //as any;
props.form.permissions = data.permissions;

function previousPage() {
    props.form.formStep--;
}
</script>

<template>
    <form @submit.prevent="submit">
        <span class="block font-medium text-md text-gray-700">
            Permissions
        </span>
        <div class="mt-1">
            <table class="min-w-full">
                <thead class="bg-white border-b">
                    <tr class="border-b bg-gray-100">
                        <th
                            scope="col"
                            class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                        >
                            Nom
                        </th>
                        <th
                            scope="col"
                            class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                        >
                            Lister
                        </th>
                        <th
                            scope="col"
                            class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                        >
                            Consulter
                        </th>
                        <th
                            scope="col"
                            class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                        >
                            Créer
                        </th>
                        <th
                            scope="col"
                            class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                        >
                            Modifier
                        </th>
                        <th
                            scope="col"
                            class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                        >
                            Supprimer
                        </th>
                    </tr>
                </thead>
                <tbody v-for="index in 4" :key="index">
                    <tr class="bg-white border-b">
                        <td
                            v-if="index === 1"
                            class="text-md text-gray-900 font-bold px-6 py-4"
                        >
                            Utilisateurs
                        </td>
                        <td
                            v-if="index === 2"
                            class="text-md text-gray-900 font-bold px-6 py-4"
                        >
                            Rôles
                        </td>
                        <td
                            v-if="index === 3"
                            class="text-md text-gray-900 font-bold px-6 py-4"
                        >
                            Groupes
                        </td>
                        <td
                            v-if="index === 4"
                            class="text-md text-gray-900 font-bold px-6 py-4"
                        >
                            Séances
                        </td>

                        <td
                            v-for="i in index === 1
                                ? [1, 2, 3, 4, 5]
                                : index === 2
                                ? [6, 7, 8, 9, 10]
                                : index === 3
                                ? [11, 12, 13, 14, 15]
                                : [16, 17, 18, 19, 20]"
                            :key="i"
                            class="px-6 py-4"
                        >
                            <Checkbox
                                :name="
                                    index === 1
                                        ? 'users'
                                        : index === 2
                                        ? 'roles'
                                        : index === 3
                                        ? 'groups'
                                        : 'sessions'
                                "
                                :id="
                                    index === 1
                                        ? `user-${i}`
                                        : index === 2
                                        ? `role-${i}`
                                        : index === 3
                                        ? `group-${i}`
                                        : `session-${i}`
                                "
                                v-model="props.form.permissions"
                                :value="i"
                                :checked="props.form.permissions.includes(i)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex mt-4 justify-between">
            <SecondaryButton @click="previousPage()">
                Précédent
            </SecondaryButton>

            <PrimaryButton
                :class="{
                    'opacity-25': props.form.processing,
                }"
                :disabled="props.form.processing"
            >
                Créer
            </PrimaryButton>
        </div>
    </form>
</template>
