<script setup lang="ts">
import { ref, toRefs } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import route from "ziggy-js";
import sessionForm from "@/Composables/sessionForm";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BackIcon from "@/Components/BackIcon.vue";
import CaretDownIcon from "@/Components/CaretDownIcon.vue";
import CaretUpIcon from "@/Components/CaretUpIcon.vue";
import FileInput from "@/Components/FileInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Multiselect from "@vueform/multiselect";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import RadioInput from "@/Components/RadioInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    session: {
        type: Object,
        default: () => {
            return {};
        },
    },
    users: {
        type: Array<User>,
        default: () => [],
    },
    statuses: {
        type: Array<Status>,
        default: () => [],
    },
});

const titleInput = ref<HTMLInputElement>();
const usersInput = ref<HTMLInputElement>();
const showParticipants = ref<boolean>(false);

const { session, users } = toRefs(props);

const form = sessionForm(
    session.value.title,
    session.value.description,
    session.value.start_date,
    session.value.end_date,
    session.value.users
);

const submit = () => {
    form.transform((data) => ({ ...data, _method: "put" })).post(
        route("sessions.update", session.value.id),
        {
            onError: () => {
                if (form.errors.users) {
                    form.reset("users");
                    usersInput.value?.focus();
                }
                if (form.errors.title) {
                    form.reset("title");
                    titleInput.value?.focus();
                }
            },
        }
    );
};
</script>

<template>
    <Head title="Séances" />

    <AuthenticatedLayout>
        <template #header>
            <div class="inline-flex items-center">
                <Link
                    :href="route('sessions.index')"
                    class="text-sm text-gray-700 underline"
                >
                    <BackIcon />
                </Link>

                <h2
                    class="ml-2 font-semibold text-xl text-gray-800 leading-tight"
                >
                    Modification d'une séance
                </h2>
            </div>
        </template>

        <div class="p-4 md:p-6">
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <span class="block font-medium text-md text-gray-700">
                        Statut
                    </span>

                    <div class="mt-1 space-x-4">
                        <div
                            v-for="status in statuses"
                            :key="status.id"
                            class="inline-flex items-center space-x-1 ml-0.5"
                        >
                            <RadioInput
                                :id="`status-${status.id}`"
                                v-model="form.status"
                                name="status"
                                :value="status.id"
                                :checked="status.id === form.status"
                            />

                            <InputLabel
                                :for="`status-${status.id}`"
                                :value="status.name"
                            />
                        </div>
                    </div>

                    <InputError class="mt-2" :message="form.errors.status" />
                </div>

                <div class="w-full flex flex-col lg:flex-row">
                    <div class="flex flex-col w-full max-w-md">
                        <div>
                            <InputLabel for="title" value="Titre" />

                            <TextInput
                                id="title"
                                ref="titleInput"
                                v-model="form.title"
                                type="text"
                                class="mt-1 block w-full"
                                autofocus
                                required
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.title"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="description" value="Description" />

                            <TextareaInput
                                id="description"
                                ref="descriptionInput"
                                v-model="form.description"
                                class="mt-1 block w-full"
                            ></TextareaInput>

                            <InputError
                                class="mt-2"
                                :message="form.errors.description"
                            />
                        </div>

                        <div
                            class="mt-4 flex flex-col md:flex-row md:space-x-7"
                        >
                            <div>
                                <InputLabel
                                    for="start_date"
                                    value="Date de début"
                                />

                                <TextInput
                                    id="start_date"
                                    ref="startDateInput"
                                    v-model="form.start_date"
                                    type="datetime-local"
                                    class="mt-1 block w-full"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.start_date"
                                />
                            </div>

                            <div class="mt-4 md:mt-0">
                                <InputLabel
                                    for="end_date"
                                    value="Date de fin"
                                />

                                <TextInput
                                    id="end_date"
                                    ref="endDateInput"
                                    v-model="form.end_date"
                                    type="datetime-local"
                                    class="mt-1 block w-full"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.end_date"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="w-full mt-4 lg:ml-8 lg:mt-0 max-w-md">
                        <div>
                            <span
                                class="block font-medium text-md text-gray-700"
                            >
                                Utilisateurs
                            </span>

                            <div class="mt-1 max-w-md">
                                <Multiselect
                                    ref="usersInput"
                                    v-model="form.users"
                                    :groups="true"
                                    mode="multiple"
                                    :multiple-label="
                                        (values: string) => values.length > 1 ? 
                                            `${values.length} utilisateurs sélectionnés` : `${values.length} utilisateur sélectionné`
                                    "
                                    label="name"
                                    value-prop="id"
                                    :close-on-select="false"
                                    :hide-selected="false"
                                    :searchable="true"
                                    no-results-text="Aucun résultat"
                                    no-options-text="Aucune option"
                                    :options="users"
                                    :classes="{
                                        container:
                                            'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer shadow-sm border-2 border-gray-300 rounded-md bg-white text-base leading-snug outline-none',
                                        containerActive:
                                            'ring-2 ring-indigo-100 border-indigo-500 outline-none transition duration-150 ease-in-out',
                                        clear: 'pr-3.5 relative z-10 opacity-40 transition duration-300 flex-shrink-0 flex-grow-0 flex hover:opacity-100 rtl:pr-0 rtl:pl-3.5',
                                        group: 'p-0 m-0',
                                        groupLabel:
                                            'flex text-sm box-border items-center justify-start text-left py-1 px-3 font-semibold bg-gray-200 cursor-default leading-normal',
                                        groupLabelPointed:
                                            'bg-gray-300 text-gray-700',
                                        groupLabelSelected:
                                            'bg-indigo-500 text-white',
                                        groupLabelSelectedPointed:
                                            'bg-indigo-500 text-white opacity-90',
                                        option: 'flex items-center justify-start box-border text-left cursor-pointer text-base leading-snug py-2 px-3',
                                        optionPointed:
                                            'text-gray-800 bg-gray-100',
                                        optionSelected:
                                            'text-white bg-indigo-500',
                                        optionSelectedPointed:
                                            'text-white bg-indigo-500 opacity-90',
                                    }"
                                    required
                                />
                            </div>

                            <InputError
                                class="mt-2"
                                :message="form.errors.users"
                            />
                        </div>

                        <div class="mt-4">
                            <span
                                class="block font-medium text-md text-gray-700"
                            >
                                Documents
                            </span>

                            <FileInput
                                class="mt-1 block w-full"
                                type="file"
                                name="document"
                                multiple
                                @input="
                                    form.documents = (<HTMLInputElement>(
                                        $event.target
                                    )).files
                                "
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.documents"
                            />
                        </div>
                    </div>
                </div>

                <div class="w-full mt-4">
                    <div class="flex">
                        <span class="block font-medium text-md text-gray-700">
                            Liste des participants ({{ form.users.length }})
                        </span>

                        <button
                            class="ml-1"
                            type="button"
                            @click="showParticipants = !showParticipants"
                        >
                            <template v-if="showParticipants">
                                <CaretUpIcon />
                            </template>

                            <template v-else>
                                <CaretDownIcon />
                            </template>
                        </button>
                    </div>

                    <ul class="mt-1">
                        <li
                            v-for="(user, index) in [
                                ...new Set(
                                    users
                                        .flatMap((group) =>
                                            group.options
                                                .filter((user) =>
                                                    form.users.includes(user.id)
                                                )
                                                .map((user) => user.name)
                                        )
                                        .sort()
                                ),
                            ]"
                            v-show="showParticipants"
                            :key="index"
                        >
                            <span
                                class="block font-medium text-sm text-gray-700"
                            >
                                {{ user }}
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="mt-6">
                    <PrimaryButton
                        :class="{
                            'opacity-25': form.processing,
                        }"
                        :disabled="form.processing"
                    >
                        Modifier
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
