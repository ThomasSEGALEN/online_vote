import { useForm } from "@inertiajs/vue3";

export default function groupForm(name = "", users: object = {}) {
    const form = useForm({
        name: name,
        users: users,
    });

    return form;
}
