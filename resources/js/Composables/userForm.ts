import { useForm } from "@inertiajs/vue3";

export default function userForm(
    civility = 1,
    last_name = "",
    first_name = "",
    email = "",
    role = 2,
    groups: Array<number | string> = []
) {
    const form = useForm({
        civility: civility,
        last_name: last_name,
        first_name: first_name,
        email: email,
        password: "",
        role: role,
        groups: groups,
    });

    return form;
}
