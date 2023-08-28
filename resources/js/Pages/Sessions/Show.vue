<script setup lang="ts">
import { computed, onMounted, onUpdated, ref, toRefs } from "vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import route from "ziggy-js";
import {
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Legend,
    LinearScale,
    Title,
    Tooltip,
} from "chart.js";
import { Bar } from "vue-chartjs";
import { Permission, User, Vote, VoteAnswer, VoteResult } from "@/types/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BackIcon from "@/Components/BackIcon.vue";
import RadioInput from "@/Components/RadioInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import ResponsivePagination from "@/Components/ResponsivePagination.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DomToImage from "dom-to-image";

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
);

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
});

const { session } = toRefs(props);

const canvaDataUrl = ref<string>("");
const confirmingVote = ref<boolean>(false);
const showMessage = ref<boolean>(false);

const successMessage = computed(() => usePage().props.flash.success);
const errorMessage = computed(() => usePage().props.flash.error);

const { permissions } = usePage().props.auth;

const form = useForm({
    answers: session.value.votes.data.map((vote: Vote) => vote.answers[0].id),
});

onMounted(async () => {
    const node = document.getElementById("chart");

    if (node !== null) {
        DomToImage.toPng(node).then(function (dataUrl: string) {
            canvaDataUrl.value = dataUrl;
        });
    }
});

onMounted(() => {
    showMessage.value = true;
    setTimeout(() => (showMessage.value = false), 3000);
});

onUpdated(() => {
    showMessage.value = true;
    setTimeout(() => {
        showMessage.value = false;
    }, 3000);
});

const hasAccess = (vote: Vote): boolean =>
    permissions.some(
        (permission: Permission) => permission.name === "viewSessions"
    ) || vote.allowed;

const isExpired = (vote: Vote): boolean => {
    const now = Date.now();
    const start = new Date(vote.start_date).getTime();
    const end = new Date(vote.end_date).getTime();
    const voteInProgress =
        vote.start_date || vote.end_date ? start <= now && end >= now : true;
    const voteOpen = vote.status_id === 1 ? true : false;

    return voteInProgress && voteOpen && !vote.voted;
};

const filterAnswer = (vote: Vote, index: number) =>
    vote.answers.filter(
        (answer: VoteAnswer) => answer.id === form.answers[index]
    )[0].name;

const confirmVote = () => (confirmingVote.value = true);

const closeModal = () => (confirmingVote.value = false);

const submit = (voteId: number, answerId: number) => {
    router.post(route("votes.store"), {
        vote: voteId,
        answer: answerId,
    });
};

const chartOptions = {
    responsive: true,
    animation: {
        duration: 0,
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                stepSize: 1,
                precision: 0,
            },
        },
    },
};

const chartData = computed(() => {
    const answers = session.value.votes.data.flatMap(
        (vote: Vote) => vote.answers
    );
    const results = session.value.votes.data.flatMap(
        (vote: Vote) => vote.results
    );
    const labelsData = [
        ...new Set(
            results.map((result: VoteResult) =>
                new Date(result.date).toLocaleDateString()
            )
        ),
    ];
    const names = [
        ...new Set(answers.map((answer: VoteAnswer) => answer.name)),
    ];
    const dates = [
        ...new Set(results.map((result: VoteResult) => result.date)),
    ];
    let data: Array<Array<number>> = Array.from(Array(names.length), () => []);

    for (let index = 0; index < names.length; index++) {
        for (let i = 0; i < dates.length; i++) {
            const count = results
                .filter(
                    (result: VoteResult) =>
                        result.name === names[index] && result.date === dates[i]
                )
                .map((result: VoteResult) => result.count)[0];

            data[index].push(count ?? 0);
        }
    }
    const datasetsData = answers.map((answer: VoteAnswer, index: number) => {
        return {
            label: answer.name,
            backgroundColor: answer.color,
            data: data[index],
        };
    });

    return {
        labels: labelsData,
        datasets: datasetsData,
    };
});
</script>

<template>
    <Head title="Votes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="inline-flex items-center">
                <Link
                    :href="route('home')"
                    class="text-sm text-gray-700 underline"
                >
                    <BackIcon />
                </Link>

                <h2
                    class="ml-2 font-semibold text-xl text-gray-800 leading-tight"
                >
                    {{ session.title }}
                </h2>
            </div>
        </template>

        <div class="grid grid-cols-1 p-4 lg:p-6 lg:max-w-screen-xl mx-auto">
            <Transition
                enter-active-class="duration-300 ease-out"
                enter-from-class="transform opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="transform opacity-0"
            >
                <p
                    v-if="showMessage && errorMessage"
                    class="text-sm text-red-600 bg-red-100 py-2 px-4 rounded mb-4"
                >
                    {{ errorMessage }}
                </p>

                <p
                    v-else-if="showMessage && successMessage"
                    class="text-sm text-green-600 bg-green-100 py-2 px-4 rounded mb-4"
                >
                    {{ successMessage }}
                </p>
            </Transition>

            <div
                v-if="session.description || session.documents.length"
                class="flex flex-col justify-evenly space-y-4 bg-white p-6 rounded-lg shadow-lg mb-4"
            >
                <div v-if="session.description">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Description
                    </h2>

                    <span
                        class="block font-medium text-sm text-gray-700 break-words"
                    >
                        {{ session.description }}
                    </span>
                </div>

                <div v-if="session.documents.length">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Documents
                    </h2>

                    <template
                        v-for="document in session.documents"
                        :key="document.name"
                    >
                        <a
                            class="block font-medium text-sm text-gray-700"
                            :href="route('documents.download', document.id)"
                        >
                            {{ document.name }}
                        </a>
                    </template>
                </div>
            </div>

            <div
                v-for="(vote, voteIndex) in session.votes.data as Array<Vote>"
                :key="vote.id"
                class="flex flex-col justify-evenly space-y-4 bg-white p-6 rounded-lg shadow-lg mb-4"
            >
                <template v-if="hasAccess(vote)">
                    <div class="flex flex-col md:flex-row justify-between">
                        <h2 class="text-lg font-bold text-gray-800">
                            {{ vote.title }}
                        </h2>

                        <span
                            class="block font-medium text-md"
                            :class="
                                vote.status_id === 1
                                    ? 'text-green-600'
                                    : 'text-red-600'
                            "
                        >
                            {{ vote.status_id === 1 ? "Ouvert" : "Fermé" }}
                        </span>
                    </div>

                    <div>
                        <a
                            class="inline-flex px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:ring-offset-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-150 ease-in-out"
                            :href="
                                route('votes.export', {
                                    vote: vote.id,
                                    url: canvaDataUrl,
                                })
                            "
                        >
                            Export PDF
                        </a>
                    </div>

                    <div v-if="vote.description">
                        <h2 class="text-lg font-semibold text-gray-800">
                            Description
                        </h2>

                        <span
                            class="block font-medium text-sm text-gray-700 break-words"
                        >
                            {{ vote.description }}
                        </span>
                    </div>

                    <template v-if="isExpired(vote)">
                        <div>
                            <div class="flex flex-col">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    Choix
                                </h2>

                                <div class="mt-2">
                                    <div
                                        v-for="(answer, index) in vote.answers"
                                        :key="answer.id"
                                        class="flex items-center space-x-1"
                                    >
                                        <RadioInput
                                            :id="answer.id"
                                            v-model="form.answers[voteIndex]"
                                            :value="answer.id"
                                            :checked="index === 0"
                                        />

                                        <InputLabel
                                            :for="answer.id"
                                            :value="answer.name"
                                        />
                                    </div>

                                    <div class="mt-4">
                                        <PrimaryButton
                                            :class="{
                                                'opacity-25': form.processing,
                                            }"
                                            :disabled="form.processing"
                                            @click="confirmVote"
                                        >
                                            Voter
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </div>

                            <Modal :show="confirmingVote" @close="closeModal">
                                <div class="p-6">
                                    <h2
                                        class="text-lg font-medium text-gray-800"
                                    >
                                        Êtes-vous sûr de vouloir voter
                                        <b>{{
                                            filterAnswer(vote, voteIndex)
                                        }}</b>
                                        ?
                                    </h2>

                                    <h2
                                        class="text-lg font-medium text-gray-800"
                                    >
                                        Vous ne pourrez plus modifier votre
                                        vote.
                                    </h2>

                                    <div class="mt-6 flex justify-end">
                                        <SecondaryButton @click="closeModal">
                                            Annuler
                                        </SecondaryButton>

                                        <PrimaryButton
                                            class="ml-4"
                                            @click="
                                                submit(
                                                    vote.id,
                                                    form.answers[voteIndex]
                                                )
                                            "
                                        >
                                            Voter
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </Modal>
                        </div>
                    </template>
                </template>

                <template v-else>
                    <span class="block text-sm text-red-600">
                        Vous n'avez pas accès à ce vote
                    </span>
                </template>

                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        Résultats
                    </h2>

                    <Bar
                        id="chart"
                        class="bg-white"
                        :height="100"
                        :options="chartOptions"
                        :data="chartData"
                    />
                </div>
            </div>

            <Pagination
                v-if="session.votes.total > session.votes.per_page"
                class="hidden lg:flex"
                :to="session.votes.to"
                :from="session.votes.from"
                :total="session.votes.total"
                :links="session.votes.links"
            />

            <ResponsivePagination
                v-if="session.votes.total > session.votes.per_page"
                class="flex lg:hidden"
                :to="session.votes.to"
                :from="session.votes.from"
                :total="session.votes.total"
                :links="session.votes.links"
            />
        </div>
    </AuthenticatedLayout>
</template>
