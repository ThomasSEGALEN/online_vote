<script setup lang="ts">
import { toRefs } from "vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import route from "ziggy-js";
import { Permission, User, Vote } from "@/types/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BackIcon from "@/Components/BackIcon.vue";
import RadioInput from "@/Components/RadioInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

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

const form = useForm({
    answers: session.value.votes.map((vote: Vote) => vote.answers[0].id),
});

const { permissions } = usePage().props.auth;

const hasAccess = (vote: Vote): boolean =>
    permissions.some(
        (permission: Permission) => permission.name === "viewSessions"
    ) || vote.allowed;

const submit = (voteId: number, answerId: number) => {
    router.post(route("votes.vote"), {
        vote: voteId,
        answer: answerId,
    });
};
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

        <!-- {{ session.votes.map((vote: Vote) => hasAccess(vote)) }} -->

        <!-- <div v-if="session.documents.length">
            <span>Documents:</span>

            <div v-for="document in session.documents" :key="document.name">
                <a :href="route('documents.download', document.id)">
                    {{ document.name }}
                </a>
            </div>
        </div> -->

        <!-- <div v-if="session.users.length">
            <span>Usernames :</span>

            <li
                v-for="user in users
                    .filter((user: User) => session.users.includes(user.id))"
                :key="user.id"
            >
                {{ user.name }}
            </li>
        </div> -->

        <div v-for="(vote, voteIndex) in session.votes" :key="vote.id">
            <form @submit.prevent="submit(vote.id, form.answers[voteIndex])">
                {{ vote.title }}

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

                    <InputLabel :for="answer.id" :value="answer.name" />
                </div>

                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Voter
                </PrimaryButton>

                <div>
                    {{ vote.id }}
                    {{ form.answers[voteIndex] }}
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
