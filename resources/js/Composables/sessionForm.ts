import { useForm } from "@inertiajs/vue3";

export default function sessionForm(
    title = "",
    description = "",
    start_date = "",
    end_date = "",
    users: Array<number> = []
) {
    const form = useForm({
        title: title,
        description: description,
        start_date: start_date,
        end_date: end_date,
        users: users,
    });

    return form;
}