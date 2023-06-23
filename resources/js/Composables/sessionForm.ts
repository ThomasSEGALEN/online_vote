import { useForm } from "@inertiajs/vue3";

export default function sessionForm({
    title = "",
    description = "",
    start_date = "",
    end_date = "",
    status = 1,
    users = [],
    label_sets = [],
    documents = null,
    amount = 1,
    votes = {
        title: [],
        description: [],
        start_date: [],
        end_date: [],
        status: [],
        type: [],
        label_sets: [],
        answers: [],
        users: [],
    },
}: {
    title?: string;
    description?: string;
    start_date?: string;
    end_date?: string;
    status?: number;
    users?: Array<number>;
    label_sets?: Array<number>;
    documents?: FileList | null;
    amount?: number;
    votes?: {
        title: Array<string>;
        description: Array<string>;
        start_date: Array<string>;
        end_date: Array<string>;
        status: Array<number>;
        type: Array<number>;
        label_sets: Array<Array<number>>;
        answers: Array<Array<{ name: string; color: string }>>;
        users: Array<Array<number>>;
    };
}) {
    const form = useForm({
        title: title,
        description: description,
        start_date: start_date,
        end_date: end_date,
        status: status,
        users: users,
        label_sets: label_sets,
        documents: documents,
        amount: amount,
        votes: votes,
    });

    return form;
}
