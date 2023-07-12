<script setup lang="ts">
import { computed, nextTick, onMounted, onUpdated, ref, watch } from "vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import route from "ziggy-js";
import { debounce } from "lodash";
import { LabelSet, Session, Status } from "@/types/types";
import AnswerIcon from "@/Components/AnswerIcon.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CaretUpIcon from "@/Components/CaretUpIcon.vue";
import CaretDownIcon from "@/Components/CaretDownIcon.vue";
import Checkbox from "@/Components/Checkbox.vue";
import ColorInput from "@/Components/ColorInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import DeleteIcon from "@/Components/DeleteIcon.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import NumberInput from "@/Components/NumberInput.vue";
import Pagination from "@/Components/Pagination.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ResponsivePagination from "@/Components/ResponsivePagination.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import UpdateIcon from "@/Components/UpdateIcon.vue";

const props = defineProps({
    sessions: {
        type: Object,
        default: () => {
            return {};
        },
    },
    labelSets: {
        type: Array<LabelSet>,
        default: () => [],
    },
    statuses: {
        type: Array<Status>,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => {
            return {};
        },
    },
    can: {
        type: Object,
        default: () => {
            return {};
        },
    },
});

const search = ref<string>(props.filters.search);
const confirmingAnswerAction = ref<boolean>(false);
const confirmingAnswerCreation = ref<boolean>(false);
const confirmingAnswerDeletion = ref<boolean>(false);
const labelCreationInput = ref<HTMLInputElement>();
const confirmingSessionDeletion = ref<boolean>(false);
const sessionId = ref<number>();
const showMessage = ref<boolean>(false);
const currentAnswer = ref<number>(0);

const successMessage = computed(() => usePage().props.flash.success);
const errorMessage = computed(() => usePage().props.flash.error);

const form = useForm({
    label: "",
    amount: 1,
    names: [],
    colors: [],
    labelSets: [],
});

onMounted(() => {
    showMessage.value = true;
    setTimeout(() => {
        showMessage.value = false;
    }, 3000);
});

onUpdated(() => {
    showMessage.value = true;
    setTimeout(() => {
        showMessage.value = false;
    }, 3000);
});

watch(
    search,
    debounce((value) => {
        router.get(
            route("sessions.index"),
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300)
);

const confirmAnswerAction = () => (confirmingAnswerAction.value = true);
const confirmAnswerCreation = () => {
    closeAnswerActionModal();
    confirmingAnswerCreation.value = true;
    nextTick(() => labelCreationInput.value?.focus());
};
const confirmAnswerDeletion = () => {
    closeAnswerActionModal();
    confirmingAnswerDeletion.value = true;
};

const confirmSessionDeletion = (id: number) => {
    confirmingSessionDeletion.value = true;
    sessionId.value = id;
};

const createAnswer = () =>
    form.post(route("labelSets.store"), {
        onSuccess: () => closeAnswerCreationModal(),
        onFinish: () => form.reset(),
    });
const deleteAnswer = () => {
    form.labelSets.map((id: number) =>
        form.delete(route("labelSets.destroy", id))
    );
    form.reset();
    closeAnswerDeletionModal();
};
const deleteSession = () => {
    router.delete(`sessions/${sessionId.value}`, {
        onSuccess: () => closeModal(),
    });
};

const closeAnswerActionModal = () => (confirmingAnswerAction.value = false);
const closeAnswerCreationModal = () => {
    form.reset();
    confirmingAnswerCreation.value = false;
};
const closeAnswerDeletionModal = () => (confirmingAnswerDeletion.value = false);

const closeModal = () => (confirmingSessionDeletion.value = false);
</script>

<template>
    <Head title="Séances" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Séances
            </h2>
        </template>

        <Modal :show="confirmingAnswerAction" @close="closeAnswerActionModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800">
                    Jeu d'étiquettes
                </h2>

                <div
                    class="mt-6 grid gap-4 grid-cols-1 xs:flex xs:flex-row xs:justify-evenly place-items-center"
                >
                    <button
                        class="w-32 h-32 bg-gray-100 border rounded-xl"
                        @click="confirmAnswerCreation"
                    >
                        Créer
                    </button>

                    <button
                        class="w-32 h-32 bg-gray-100 border rounded-xl"
                        @click="confirmAnswerDeletion"
                    >
                        Supprimer
                    </button>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeAnswerActionModal">
                        Annuler
                    </SecondaryButton>
                </div>
            </div>
        </Modal>

        <Modal
            :show="confirmingAnswerCreation"
            @close="closeAnswerCreationModal"
        >
            <form class="p-6 mx-20" @submit.prevent="createAnswer">
                <h2 class="text-lg font-medium text-gray-800">
                    Création d'un jeu d'étiquettes
                </h2>

                <div class="mt-6 flex flex-col lg:flex-row justify-between">
                    <div>
                        <InputLabel for="label" value="Label" />

                        <TextInput
                            id="label"
                            ref="labelCreationInput"
                            v-model="form.label"
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.label" />
                    </div>

                    <div class="mt-4 lg:mt-0">
                        <InputLabel for="amount" value="Nombre de réponses" />

                        <NumberInput
                            id="amount"
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

                <div
                    v-for="(amount, index) in form.amount"
                    :key="index"
                    class="mt-4"
                >
                    <InputLabel
                        :for="`answer-${index}`"
                        :value="`Réponse ${index + 1}`"
                    />

                    <div class="flex items-center justify-between space-x-4">
                        <TextInput
                            :id="`answer-${index}`"
                            v-model="form.names[index]"
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />

                        <ColorInput v-model="form.colors[index]" />
                    </div>

                    <InputError
                        class="mt-2"
                        :message="form.errors[`colors.${index}` as keyof object]"
                    />
                </div>

                <div
                    class="mt-6 inline-flex lg:flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-4 justify-end"
                >
                    <SecondaryButton @click="closeAnswerCreationModal">
                        Annuler
                    </SecondaryButton>

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
        </Modal>

        <Modal
            :show="confirmingAnswerDeletion"
            @close="closeAnswerDeletionModal"
        >
            <form class="p-6 mx-20" @submit.prevent="deleteAnswer">
                <h2 class="text-lg font-medium text-gray-800">
                    Suppression d'un jeu d'étiquettes
                </h2>

                <div class="mt-6 flex flex-col lg:flex-row justify-between">
                    <div v-if="labelSets.length">
                        <div
                            v-for="(labelSet, index) in labelSets"
                            :key="index"
                        >
                            <div class="flex flex-row items-center">
                                <Checkbox
                                    :id="`labelSets-${index}`"
                                    v-model="form.labelSets"
                                    :value="labelSet.id"
                                    class="mr-1"
                                />

                                <button
                                    type="button"
                                    @click="
                                        currentAnswer !== index
                                            ? (currentAnswer = index)
                                            : (currentAnswer = -1)
                                    "
                                >
                                    <div class="inline-flex items-center">
                                        <span
                                            class="block font-medium text-md text-gray-700"
                                        >
                                            {{ labelSet.name }}
                                        </span>

                                        <div class="ml-1">
                                            <template
                                                v-if="currentAnswer === index"
                                            >
                                                <CaretUpIcon />
                                            </template>

                                            <template v-else>
                                                <CaretDownIcon />
                                            </template>
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <div v-show="currentAnswer === index">
                                <div>
                                    <span
                                        class="block font-medium text-md text-gray-700"
                                    >
                                        Réponses :
                                    </span>

                                    <ul class="max-h-36 overflow-y-auto">
                                        <li
                                            v-for="(
                                                answer, answerIndex
                                            ) in labelSet.answers"
                                            :key="answerIndex"
                                        >
                                            <span
                                                class="block font-medium text-sm text-gray-700"
                                            >
                                                {{ answer.name }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <span v-else class="block font-medium text-md text-gray-700"
                        >Aucun jeu d'étiquettes enregistré</span
                    >
                </div>

                <div
                    class="mt-6 inline-flex lg:flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-4 justify-end"
                >
                    <SecondaryButton @click="closeAnswerDeletionModal">
                        Annuler
                    </SecondaryButton>

                    <PrimaryButton
                        v-if="labelSets.length"
                        :class="{
                            'opacity-25': form.processing,
                        }"
                        :disabled="form.processing"
                    >
                        Supprimer
                    </PrimaryButton>
                </div>
            </form>
        </Modal>

        <div class="p-4 lg:p-6">
            <div class="flex flex-wrap flex-row justify-between">
                <div
                    v-if="can.createSessions"
                    class="flex items-center space-x-2 mb-2"
                >
                    <Link
                        class="inline-flex px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                        :href="route('sessions.create')"
                    >
                        Nouvelle séance
                    </Link>

                    <button
                        class="inline-flex px-2 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                        @click="confirmAnswerAction"
                    >
                        <AnswerIcon />
                    </button>
                </div>

                <TextInput
                    id="search"
                    v-model="search"
                    type="text"
                    placeholder="Recherche"
                />
            </div>

            <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
            >
                <p
                    v-if="showMessage && successMessage"
                    class="text-sm text-green-600 bg-green-100 py-2 px-4 rounded my-2"
                >
                    {{ successMessage }}
                </p>

                <p
                    v-else-if="showMessage && errorMessage"
                    class="text-sm text-red-600 bg-red-100 py-2 px-4 rounded my-2"
                >
                    {{ errorMessage }}
                </p>
            </Transition>

            <div class="flex flex-col overflow-x-auto min-w-full mt-4 mb-6">
                <table class="min-w-full">
                    <thead class="border-b">
                        <tr>
                            <th
                                scope="col"
                                class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                            >
                                #
                            </th>

                            <th
                                scope="col"
                                class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                            >
                                Titre
                            </th>

                            <th
                                scope="col"
                                class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                            >
                                Date de début
                            </th>

                            <th
                                scope="col"
                                class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                            >
                                Date de fin
                            </th>

                            <th
                                scope="col"
                                class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                            >
                                Statut
                            </th>

                            <th
                                scope="col"
                                class="text-md font-bold text-gray-900 px-6 py-4 text-left"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="session in sessions.data as Array<Session>"
                            :key="session.id"
                            class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100"
                        >
                            <td
                                class="text-md text-gray-900 font-bold px-6 py-4"
                            >
                                {{ session.id }}
                            </td>

                            <td
                                class="text-md text-gray-900 font-semibold px-6 py-4"
                            >
                                {{ session.title }}
                            </td>

                            <td
                                class="text-md text-gray-900 font-semibold px-6 py-4"
                            >
                                {{ session.start_date }}
                            </td>

                            <td
                                class="text-md text-gray-900 font-semibold px-6 py-4"
                            >
                                {{ session.end_date }}
                            </td>

                            <td
                                class="text-md text-gray-900 font-semibold px-6 py-4"
                            >
                                {{
                                    statuses.find(
                                        (status: Status) =>
                                            status.id === session.status_id
                                    )!.name
                                }}
                            </td>

                            <td class="flex space-x-5 px-6 py-4">
                                <Link
                                    v-if="can.updateSessions"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                                    :href="route('sessions.edit', session.id)"
                                >
                                    <UpdateIcon />
                                </Link>

                                <DangerButton
                                    v-if="can.deleteSessions"
                                    @click="confirmSessionDeletion(session.id)"
                                >
                                    <DeleteIcon />
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination
                v-if="sessions.total > sessions.per_page"
                class="hidden lg:flex"
                :to="sessions.to"
                :from="sessions.from"
                :total="sessions.total"
                :links="sessions.links"
            />

            <ResponsivePagination
                v-if="sessions.total > sessions.per_page"
                class="flex lg:hidden"
                :to="sessions.to"
                :from="sessions.from"
                :total="sessions.total"
                :links="sessions.links"
            />

            <Modal :show="confirmingSessionDeletion" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-800">
                        Êtes-vous sûr de vouloir supprimer cet utilisateur ?
                    </h2>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal">
                            Annuler
                        </SecondaryButton>

                        <DangerButton class="ml-4" @click="deleteSession">
                            Supprimer
                        </DangerButton>
                    </div>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
