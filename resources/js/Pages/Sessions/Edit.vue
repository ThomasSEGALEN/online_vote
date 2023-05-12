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
import SecondaryButton from "@/Components/SecondaryButton.vue";
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
    vote_types: {
        type: Array<VoteType>,
        default: () => [],
    },
});

const formStep = ref<number>(1);
const titleInput = ref<HTMLInputElement>();
const usersInput = ref<HTMLInputElement>();
const showParticipants = ref<boolean>(false);
const currentVote = ref<number>(0);

const { session, users } = toRefs(props);

const form = sessionForm({
    title: session.value.title,
    description: session.value.description,
    start_date: session.value.start_date,
    end_date: session.value.end_date,
    status: session.value.status_id,
    users: session.value.users,
    votes: {
        title: session.value.votes.map((vote: Vote) => vote.title),
        description: session.value.votes.map((vote: Vote) => vote.description),
        start_date: session.value.votes.map((vote: Vote) => vote.start_date),
        end_date: session.value.votes.map((vote: Vote) => vote.end_date),
        status: session.value.votes.map((vote: Vote) => vote.status_id),
        type: session.value.votes.map((vote: Vote) => vote.type_id),
        users: session.value.votes.map((vote: Vote) => vote.users),
    },
});

const hasError = (index: number): boolean =>
    form.errors[`votes.title.${index}` as keyof object] ||
    form.errors[`votes.users.${index}` as keyof object];

const getSessionUsers = (users: Array<User>): Array<string> => [
    ...new Set(
        users
            .flatMap((group) =>
                group.options
                    .filter((user) => form.users.includes(user.id))
                    .map((user) => user.name)
            )
            .sort()
    ),
];

const getVoteUsers = (users: Array<User>, index: number): Array<string> => [
    ...new Set(
        users
            .flatMap((group) =>
                group.options
                    .filter((user) => form.votes.users[index].includes(user.id))
                    .map((user) => user.name)
            )
            .sort()
    ),
];

const nextStep = () =>
    form
        .transform((data) => ({ ...data, _method: "put" }))
        .post(route("sessions.preupdate", session.value.id), {
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
            onSuccess: () => formStep.value++,
        });

const previousStep = () => formStep.value--;

const submit = () => {
    form.transform((data) => ({ ...data, _method: "put" })).post(
        route("sessions.update", session.value.id)
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

        <div class="p-4 md:p-6 max-w-5xl">
            <form @submit.prevent="submit">
                <div v-if="formStep === 1">
                    <div>
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
                                    :value="status.id"
                                />

                                <InputLabel
                                    :for="`status-${status.id}`"
                                    :value="status.name"
                                />
                            </div>
                        </div>

                        <InputError
                            class="mt-2"
                            :message="form.errors.status"
                        />
                    </div>

                    <div
                        class="mt-4 w-full flex flex-col lg:flex-row lg:space-x-8 lg:justify-between"
                    >
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
                                <InputLabel
                                    for="description"
                                    value="Description"
                                />

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

                            <div class="mt-4">
                                <span
                                    class="block font-medium text-md text-gray-700"
                                >
                                    Documents
                                </span>

                                <FileInput
                                    class="mt-1 block w-full"
                                    type="file"
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

                        <div class="w-full mt-4 lg:mt-0 max-w-md">
                            <div>
                                <InputLabel for="users" value="Utilisateurs" />

                                <div class="mt-1 max-w-md">
                                    <Multiselect
                                        id="users"
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

                            <div class="mt-4 w-fit">
                                <button
                                    type="button"
                                    @click="
                                        showParticipants = !showParticipants
                                    "
                                >
                                    <div class="inline-flex items-center">
                                        <span
                                            class="block font-medium text-md text-gray-700"
                                        >
                                            Liste des participants ({{
                                                form.users.length
                                            }})
                                        </span>

                                        <div class="ml-1">
                                            <template v-if="showParticipants">
                                                <CaretUpIcon />
                                            </template>

                                            <template v-else>
                                                <CaretDownIcon />
                                            </template>
                                        </div>
                                    </div>
                                </button>

                                <ul class="mt-1 max-h-48 overflow-y-auto">
                                    <li
                                        v-for="(user, index) in getSessionUsers(
                                            users
                                        )"
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
                        </div>
                    </div>
                </div>

                <div v-if="formStep === 2">
                    <p
                        v-if="form.hasErrors"
                        class="text-sm text-red-600 bg-red-100 py-2 px-4 rounded my-2"
                    >
                        Veuillez vérifier la saisie des votes
                    </p>

                    <div
                        v-for="(vote, voteIndex) in session.votes as Array<Vote>"
                        :key="voteIndex"
                    >
                        <button
                            type="button"
                            @click="
                                currentVote !== voteIndex
                                    ? (currentVote = voteIndex)
                                    : (currentVote = -1)
                            "
                        >
                            <div class="inline-flex items-center">
                                <div class="mr-1">
                                    <template v-if="currentVote === voteIndex">
                                        <CaretUpIcon />
                                    </template>

                                    <template v-else>
                                        <CaretDownIcon />
                                    </template>
                                </div>

                                <span
                                    class="block font-medium text-md"
                                    :class="
                                        hasError(voteIndex)
                                            ? 'text-red-600'
                                            : 'text-gray-700'
                                    "
                                >
                                    {{ vote.title }}
                                </span>
                            </div>
                        </button>

                        <div v-show="currentVote === voteIndex">
                            <div
                                class="flex flex-col md:flex-row max-w-md justify-between"
                            >
                                <div>
                                    <span
                                        class="block font-medium text-md text-gray-700"
                                    >
                                        Statut
                                    </span>

                                    <div class="mt-1 space-x-4">
                                        <div
                                            v-for="status in statuses"
                                            :key="status.id"
                                            class="inline-flex items-center space-x-1 ml-0.5"
                                        >
                                            <RadioInput
                                                :id="`status-${status.id}-${voteIndex}`"
                                                v-model="
                                                    form.votes.status[voteIndex]
                                                "
                                                :value="status.id"
                                            />

                                            <InputLabel
                                                :for="`status-${status.id}-${voteIndex}`"
                                                :value="status.name"
                                            />
                                        </div>
                                    </div>

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors[
                                                    `votes.status.${voteIndex}` as keyof object
                                                ]"
                                    />
                                </div>

                                <div class="mt-4 md:mt-0">
                                    <span
                                        class="block font-medium text-md text-gray-700"
                                    >
                                        Scrutin
                                    </span>

                                    <div class="mt-1 space-x-4">
                                        <div
                                            v-for="vote_type in vote_types"
                                            :key="vote_type.id"
                                            class="inline-flex items-center space-x-1 ml-0.5"
                                        >
                                            <RadioInput
                                                :id="`vote_type-${vote_type.id}-${voteIndex}`"
                                                v-model="
                                                    form.votes.type[voteIndex]
                                                "
                                                :value="vote_type.id"
                                            />

                                            <InputLabel
                                                :for="`vote_type-${vote_type.id}-${voteIndex}`"
                                                :value="vote_type.name"
                                            />
                                        </div>
                                    </div>

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors[
                                                    `votes.types.${voteIndex}` as keyof object
                                                ]"
                                    />
                                </div>
                            </div>

                            <div
                                class="my-4 w-full flex flex-col lg:flex-row lg:space-x-8 lg:justify-between"
                            >
                                <div class="flex flex-col w-full max-w-md">
                                    <div>
                                        <InputLabel
                                            :for="`vote_title-${voteIndex}`"
                                            value="Titre"
                                        />

                                        <TextInput
                                            :id="`vote_title-${voteIndex}`"
                                            ref="titleInput"
                                            v-model="
                                                form.votes.title[voteIndex]
                                            "
                                            type="text"
                                            class="mt-1 block w-full"
                                            autofocus
                                        />

                                        <InputError
                                            class="mt-2"
                                            :message="
                                                form.errors[
                                                    `votes.title.${voteIndex}` as keyof object
                                                ]
                                            "
                                        />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel
                                            :for="`vote_description-${voteIndex}`"
                                            value="Description"
                                        />

                                        <TextareaInput
                                            :id="`vote_description-${voteIndex}`"
                                            ref="descriptionInput"
                                            v-model="
                                                form.votes.description[
                                                    voteIndex
                                                ]
                                            "
                                            class="mt-1 block w-full"
                                        ></TextareaInput>

                                        <InputError
                                            class="mt-2"
                                            :message="
                                                form.errors[
                                                    `votes.description.${voteIndex}` as keyof object
                                                ]
                                            "
                                        />
                                    </div>

                                    <div
                                        class="mt-4 flex flex-col md:flex-row md:space-x-7"
                                    >
                                        <div>
                                            <InputLabel
                                                :for="`vote_start_date-${voteIndex}`"
                                                value="Date de début"
                                            />

                                            <TextInput
                                                :id="`vote_start_date-${voteIndex}`"
                                                ref="startDateInput"
                                                v-model="form.start_date"
                                                type="datetime-local"
                                                class="mt-1 block w-full"
                                            />

                                            <InputError
                                                class="mt-2"
                                                :message="
                                                form.errors[
                                                    `votes.start_date.${voteIndex}` as keyof object
                                                ]
                                            "
                                            />
                                        </div>

                                        <div class="mt-4 md:mt-0">
                                            <InputLabel
                                                :for="`vote_end_date-${voteIndex}`"
                                                value="Date de fin"
                                            />

                                            <TextInput
                                                :id="`vote_end_date-${voteIndex}`"
                                                ref="endDateInput"
                                                v-model="form.end_date"
                                                type="datetime-local"
                                                class="mt-1 block w-full"
                                            />

                                            <InputError
                                                class="mt-2"
                                                :message="
                                                form.errors[
                                                    `votes.end_date.${voteIndex}` as keyof object
                                                ]
                                            "
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full mt-4 lg:mt-0 max-w-md">
                                    <div>
                                        <InputLabel
                                            :for="`vote_users-${voteIndex}`"
                                            value="Utilisateurs"
                                        />

                                        <div class="mt-1 max-w-md">
                                            <Multiselect
                                                :id="`vote_users-${voteIndex}`"
                                                ref="usersInput"
                                                v-model="
                                                    form.votes.users[voteIndex]
                                                "
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
                                            />
                                        </div>

                                        <InputError
                                            class="mt-2"
                                            :message="
                                                form.errors[
                                                    `votes.users.${voteIndex}` as keyof object
                                                ]
                                            "
                                        />
                                    </div>

                                    <div class="mt-4 w-fit">
                                        <button
                                            type="button"
                                            @click="
                                                showParticipants =
                                                    !showParticipants
                                            "
                                        >
                                            <div
                                                class="inline-flex items-center"
                                            >
                                                <span
                                                    class="block font-medium text-md text-gray-700"
                                                >
                                                    Liste des participants ({{
                                                        form.votes.users[
                                                            voteIndex
                                                        ].length
                                                    }})
                                                </span>

                                                <div class="ml-1">
                                                    <template
                                                        v-if="showParticipants"
                                                    >
                                                        <CaretUpIcon />
                                                    </template>

                                                    <template v-else>
                                                        <CaretDownIcon />
                                                    </template>
                                                </div>
                                            </div>
                                        </button>

                                        <ul
                                            class="mt-1 max-h-36 overflow-y-auto"
                                        >
                                            <li
                                                v-for="(
                                                    user, index
                                                ) in getVoteUsers(
                                                    users,
                                                    voteIndex
                                                )"
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="flex items-center mt-8 max-w-md lg:max-w-full"
                    :class="formStep === 1 ? 'justify-end' : 'justify-between'"
                >
                    <SecondaryButton v-if="formStep === 1" @click="nextStep">
                        Suivant
                    </SecondaryButton>
                    <SecondaryButton
                        v-if="formStep === 2"
                        @click="previousStep"
                    >
                        Précédent
                    </SecondaryButton>
                    <PrimaryButton
                        v-if="formStep === 2"
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
