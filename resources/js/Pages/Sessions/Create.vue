<script setup lang="ts">
import { ref, toRefs } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import route from "ziggy-js";
import sessionForm from "@/Composables/sessionForm";
import { LabelSet, Status, User, VoteType } from "@/types/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BackIcon from "@/Components/BackIcon.vue";
import CaretDownIcon from "@/Components/CaretDownIcon.vue";
import CaretUpIcon from "@/Components/CaretUpIcon.vue";
import ColorInput from "@/Components/ColorInput.vue";
import FileInput from "@/Components/FileInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import MinusIcon from "@/Components/MinusIcon.vue";
import Multiselect from "@vueform/multiselect";
import NumberInput from "@/Components/NumberInput.vue";
import PlusIcon from "@/Components/PlusIcon.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import RadioInput from "@/Components/RadioInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import TextInput from "@/Components/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    users: {
        type: Array<User>,
        default: () => [],
    },
    groupedUsers: {
        type: Array<User>,
        default: () => [],
    },
    statuses: {
        type: Array<Status>,
        default: () => [],
    },
    voteTypes: {
        type: Array<VoteType>,
        default: () => [],
    },
    labelSets: {
        type: Array<LabelSet>,
        default: () => [],
    },
});

const formStep = ref<number>(1);
const amountRef = ref<number>(0);
const amountInput = ref<HTMLInputElement>();
const titleInput = ref<HTMLInputElement>();
const usersInput = ref<HTMLInputElement>();
const labelSetsInput = ref<HTMLInputElement>();
const showParticipants = ref<boolean>(false);
const currentVote = ref<number>(0);

const { groupedUsers, users } = toRefs(props);

const form = sessionForm({});

const getSessionUsers = (): Array<string> => [
    ...new Set(
        groupedUsers.value
            .flatMap((group) =>
                group.options
                    .filter((user) => form.users.includes(user.id))
                    .map((user) => user.name),
            )
            .sort(),
    ),
];

const getVoteUsers = (index: number): Array<string> => [
    ...new Set(
        groupedUsers.value
            .flatMap((group) =>
                group.options
                    .filter((user) => form.votes.users[index].includes(user.id))
                    .map((user) => user.name),
            )
            .sort(),
    ),
];

const addAnswer = (voteIndex: number) =>
    form.votes.answers[voteIndex].push({ name: "", color: "" });

const removeAnswer = (voteIndex: number, index: number) => {
    if (form.votes.answers[voteIndex].length === 1) return;

    form.votes.answers[voteIndex].splice(index, 1);
};

const nextStep = () =>
    form.post(route("sessions.prestore"), {
        onError: () => {
            if (form.errors.users) {
                form.reset("users");
                usersInput.value?.focus();
            }
            if (form.errors.label_sets) {
                form.reset("label_sets");
                labelSetsInput.value?.focus();
            }
            if (form.errors.title) {
                form.reset("title");
                titleInput.value?.focus();
            }
            if (form.errors.amount) {
                form.reset("amount");
                amountInput.value?.focus();
            }
        },
        onSuccess: () => {
            if (form.amount > amountRef.value) {
                for (
                    let index = amountRef.value;
                    index < form.amount;
                    index++
                ) {
                    form.votes.title.push(`${form.title} - ${index + 1}`);
                    form.votes.description.push("");
                    form.votes.users.push(
                        users.value
                            .filter((user) => form.users.includes(user.id))
                            .map((u) => u.id),
                    );
                    form.votes.start_date.push(form.start_date);
                    form.votes.end_date.push(form.end_date);
                    form.votes.status.push(form.status);
                    form.votes.type.push(1);
                    form.votes.label_sets.push(form.label_sets);
                    form.votes.answers.push([{ name: "", color: "" }]);
                }
            } else {
                const start = amountRef.value - 1;
                const deleteCount = amountRef.value - form.amount;

                form.votes.title.splice(start, deleteCount);
                form.votes.description.splice(start, deleteCount);
                form.votes.users.splice(start, deleteCount);
                form.votes.start_date.splice(start, deleteCount);
                form.votes.end_date.splice(start, deleteCount);
                form.votes.status.splice(start, deleteCount);
                form.votes.type.splice(start, deleteCount);
                form.votes.label_sets.splice(start, deleteCount);
                form.votes.answers.splice(start, deleteCount);
            }

            amountRef.value = form.amount;
            formStep.value++;
        },
    });

const previousStep = () => formStep.value--;

const submit = () => form.post(route("sessions.store"));
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
                    Création d'une séance
                </h2>
            </div>
        </template>

        <div class="p-4 lg:p-6 max-w-md lg:max-w-screen-xl mx-auto">
            <form @submit.prevent="submit">
                <div v-if="formStep === 1">
                    <div class="mt-4 flex flex-col lg:flex-row lg:space-x-8">
                        <div class="w-full">
                            <div
                                class="flex flex-col lg:flex-row lg:justify-between"
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

                                <div class="mt-4 lg:mt-0">
                                    <InputLabel
                                        for="amount"
                                        value="Nombre de votes"
                                    />

                                    <NumberInput
                                        id="amount"
                                        ref="amountInput"
                                        v-model.number="form.amount"
                                        :min="1"
                                        :max="20"
                                        class="mt-1 block w-full"
                                        required
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.amount"
                                    />
                                </div>
                            </div>

                            <div class="mt-4">
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
                                    v-model="form.description"
                                    class="mt-1 block w-full"
                                ></TextareaInput>

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.description"
                                />
                            </div>

                            <div
                                class="mt-4 flex flex-col lg:flex-row lg:space-x-4"
                            >
                                <div class="w-full">
                                    <InputLabel
                                        for="startDate"
                                        value="Date de début"
                                    />

                                    <TextInput
                                        id="startDate"
                                        v-model="form.start_date"
                                        type="datetime-local"
                                        class="mt-1 block w-full"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.start_date"
                                    />
                                </div>

                                <div class="mt-4 lg:mt-0 w-full">
                                    <InputLabel
                                        for="endDate"
                                        value="Date de fin"
                                    />

                                    <TextInput
                                        id="endDate"
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

                        <div class="w-full mt-4 lg:mt-0">
                            <div>
                                <InputLabel
                                    for="labelSets"
                                    value="Jeux d'étiquettes"
                                />

                                <div class="mt-1">
                                    <Multiselect
                                        id="labelSets"
                                        ref="labelSetsInput"
                                        v-model="form.label_sets"
                                        mode="tags"
                                        label="name"
                                        value-prop="id"
                                        :close-on-select="false"
                                        :searchable="true"
                                        no-results-text="Aucun résultat"
                                        no-options-text="Aucune option"
                                        :options="labelSets"
                                        :classes="{
                                            container:
                                                'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer shadow-sm border-2 border-gray-300 rounded-md bg-white text-base leading-snug outline-none',
                                            containerActive:
                                                'ring-2 ring-indigo-100 border-indigo-500 outline-none transition duration-150 ease-in-out',
                                            tag: 'bg-indigo-100 text-indigo-600 text-sm font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
                                            tagsSearch:
                                                'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full text-gray-700',
                                            clear: 'pr-3.5 relative z-10 opacity-40 transition duration-300 flex-shrink-0 flex-grow-0 flex hover:opacity-100 rtl:pr-0 rtl:pl-3.5',
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
                                    :message="form.errors.label_sets"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="users" value="Utilisateurs" />

                                <div class="mt-1">
                                    <Multiselect
                                        id="users"
                                        ref="usersInput"
                                        v-model="form.users"
                                        :groups="true"
                                        mode="multiple"
                                        :multiple-label="
                                            (values: string) =>
                                                values.length > 1
                                                    ? `${values.length} utilisateurs sélectionnés`
                                                    : `${values.length} utilisateur sélectionné`
                                        "
                                        label="name"
                                        value-prop="id"
                                        :close-on-select="false"
                                        :hide-selected="false"
                                        :searchable="true"
                                        no-results-text="Aucun résultat"
                                        no-options-text="Aucune option"
                                        :options="groupedUsers"
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

                                <ul class="mt-1 max-h-60 overflow-y-auto">
                                    <li
                                        v-for="(
                                            user, index
                                        ) in getSessionUsers()"
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
                        v-for="(vote, voteIndex) in form.amount"
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
                            <div
                                class="inline-flex items-center text-left space-x-2"
                            >
                                <div class="mr-1">
                                    <template v-if="currentVote === voteIndex">
                                        <CaretUpIcon />
                                    </template>

                                    <template v-else>
                                        <CaretDownIcon />
                                    </template>
                                </div>

                                <span class="inline-block font-medium text-md">
                                    {{ form.title }} :
                                    {{ form.votes.title[voteIndex] }}
                                </span>
                            </div>
                        </button>

                        <div v-if="currentVote === voteIndex">
                            <div
                                class="my-4 w-full flex flex-col lg:flex-row lg:space-x-8 lg:justify-between"
                            >
                                <div class="flex flex-col w-full">
                                    <div
                                        class="flex flex-col sm:flex-row sm:space-x-8 lg:h-20"
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
                                                            form.votes.status[
                                                                voteIndex
                                                            ]
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
                                                :message="
                                                    form.errors[
                                                        `votes.status.${voteIndex}` as keyof object
                                                    ]
                                                "
                                            />
                                        </div>

                                        <div>
                                            <span
                                                class="block font-medium text-md text-gray-700"
                                            >
                                                Scrutin
                                            </span>

                                            <div class="mt-1 space-x-4">
                                                <div
                                                    v-for="voteType in voteTypes"
                                                    :key="voteType.id"
                                                    class="inline-flex items-center space-x-1 ml-0.5"
                                                >
                                                    <RadioInput
                                                        :id="`voteType-${voteType.id}-${voteIndex}`"
                                                        v-model="
                                                            form.votes.type[
                                                                voteIndex
                                                            ]
                                                        "
                                                        :value="voteType.id"
                                                    />

                                                    <InputLabel
                                                        :for="`voteType-${voteType.id}-${voteIndex}`"
                                                        :value="voteType.name"
                                                    />
                                                </div>
                                            </div>

                                            <InputError
                                                class="mt-2"
                                                :message="
                                                    form.errors[
                                                        `votes.types.${voteIndex}` as keyof object
                                                    ]
                                                "
                                            />
                                        </div>
                                    </div>

                                    <div class="mt-4 lg:mt-2">
                                        <InputLabel
                                            :for="`voteTitle-${voteIndex}`"
                                            value="Titre"
                                        />

                                        <TextInput
                                            :id="`voteTitle-${voteIndex}`"
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
                                            :for="`voteDescription-${voteIndex}`"
                                            value="Description"
                                        />

                                        <TextareaInput
                                            :id="`voteDescription-${voteIndex}`"
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
                                        class="mt-4 flex flex-col lg:flex-row lg:space-x-4"
                                    >
                                        <div class="w-full">
                                            <InputLabel
                                                :for="`voteStartDate-${voteIndex}`"
                                                value="Date de début"
                                            />

                                            <TextInput
                                                :id="`voteStartDate-${voteIndex}`"
                                                v-model="
                                                    form.votes.start_date[
                                                        voteIndex
                                                    ]
                                                "
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

                                        <div class="mt-4 lg:mt-0 w-full">
                                            <InputLabel
                                                :for="`voteEndDate-${voteIndex}`"
                                                value="Date de fin"
                                            />

                                            <TextInput
                                                :id="`voteEndDate-${voteIndex}`"
                                                v-model="
                                                    form.votes.end_date[
                                                        voteIndex
                                                    ]
                                                "
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

                                    <div class="mt-4">
                                        <span
                                            class="block font-medium text-md text-gray-700"
                                        >
                                            Réponses
                                        </span>

                                        <div class="space-y-2">
                                            <template
                                                v-for="(answer, index) in form
                                                    .votes.answers[voteIndex]
                                                    .length"
                                                :key="index"
                                            >
                                                <div
                                                    class="flex flex-row space-x-1 items-center"
                                                >
                                                    <TextInput
                                                        :id="`voteAnswers-${voteIndex}-${index}`"
                                                        v-model="
                                                            form.votes.answers[
                                                                voteIndex
                                                            ][index].name
                                                        "
                                                        type="text"
                                                        class="mt-1 block w-full"
                                                    />

                                                    <ColorInput
                                                        v-model="
                                                            form.votes.answers[
                                                                voteIndex
                                                            ][index].color
                                                        "
                                                        class="h-10"
                                                    />

                                                    <SecondaryButton
                                                        @click="
                                                            addAnswer(voteIndex)
                                                        "
                                                    >
                                                        <PlusIcon />
                                                    </SecondaryButton>

                                                    <SecondaryButton
                                                        @click="
                                                            removeAnswer(
                                                                voteIndex,
                                                                index,
                                                            )
                                                        "
                                                    >
                                                        <MinusIcon />
                                                    </SecondaryButton>
                                                </div>

                                                <InputError
                                                    class="mt-2"
                                                    :message="
                                                        form.errors[
                                                            `votes.answers.${voteIndex}.${index}.name` as keyof object
                                                        ]
                                                    "
                                                />
                                            </template>

                                            <InputError
                                                class="mt-2"
                                                :message="
                                                    form.errors[
                                                        `votes.answers.${voteIndex}` as keyof object
                                                    ]
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full mt-4 lg:mt-0">
                                    <div>
                                        <InputLabel
                                            :for="`voteLabelSets-${voteIndex}`"
                                            value="Jeux d'étiquettes"
                                        />

                                        <div class="mt-1">
                                            <Multiselect
                                                :id="`voteLabelSets-${voteIndex}`"
                                                v-model="
                                                    form.votes.label_sets[
                                                        voteIndex
                                                    ]
                                                "
                                                mode="tags"
                                                label="name"
                                                value-prop="id"
                                                :close-on-select="false"
                                                :searchable="true"
                                                no-results-text="Aucun résultat"
                                                no-options-text="Aucune option"
                                                :options="labelSets"
                                                :classes="{
                                                    container:
                                                        'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer shadow-sm border-2 border-gray-300 rounded-md bg-white text-base leading-snug outline-none',
                                                    containerActive:
                                                        'ring-2 ring-indigo-100 border-indigo-500 outline-none transition duration-150 ease-in-out',
                                                    tag: 'bg-indigo-100 text-indigo-600 text-sm font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
                                                    tagsSearch:
                                                        'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full text-gray-700',
                                                    clear: 'pr-3.5 relative z-10 opacity-40 transition duration-300 flex-shrink-0 flex-grow-0 flex hover:opacity-100 rtl:pr-0 rtl:pl-3.5',
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
                                                    `votes.label_sets.${voteIndex}` as keyof object
                                                ]
                                            "
                                        />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel
                                            :for="`voteUsers-${voteIndex}`"
                                            value="Utilisateurs"
                                        />

                                        <div class="mt-1">
                                            <Multiselect
                                                :id="`voteUsers-${voteIndex}`"
                                                v-model="
                                                    form.votes.users[voteIndex]
                                                "
                                                :groups="true"
                                                mode="multiple"
                                                :multiple-label="
                                                    (values: string) =>
                                                        values.length > 1
                                                            ? `${values.length} utilisateurs sélectionnés`
                                                            : `${values.length} utilisateur sélectionné`
                                                "
                                                label="name"
                                                value-prop="id"
                                                :close-on-select="false"
                                                :hide-selected="false"
                                                :searchable="true"
                                                no-results-text="Aucun résultat"
                                                no-options-text="Aucune option"
                                                :options="groupedUsers"
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
                                                ) in getVoteUsers(voteIndex)"
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
                    class="mt-6 flex"
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
                        Créer
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
