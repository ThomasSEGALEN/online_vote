import { useForm } from "@inertiajs/vue3";

export default function groupForm(
    name = "",
    users: Array<number | string> = []
) {
    const form = useForm({
        name: name,
        users: users,
    });

    return form;
}
