import { useForm } from "@inertiajs/vue3";

export default function groupForm({
    name = "",
    users = [],
}: {
    name?: string;
    users?: Array<number>;
}) {
    const form = useForm({
        name: name,
        users: users,
    });

    return form;
}
