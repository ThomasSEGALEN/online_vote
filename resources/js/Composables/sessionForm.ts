import { useForm } from "@inertiajs/vue3";

export default function sessionForm(
    title = "",
    description = "",
    start_date = "",
    end_date = "",
    users: Array<number> = [],
    documents: FileList | null = null,
    status = 1
) {
    const form = useForm({
        title: title,
        description: description,
        start_date: start_date,
        end_date: end_date,
        users: users,
        documents: documents,
        status: status,
    });

    return form;
}
