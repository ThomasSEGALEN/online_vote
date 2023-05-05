import { useForm } from "@inertiajs/vue3";

export default function userForm({
    civility = 1,
    last_name = "",
    first_name = "",
    email = "",
    role = 2,
    groups = [],
}: {
    civility?: number;
    last_name?: string;
    first_name?: string;
    email?: string;
    role?: number;
    groups?: Array<number>;
}) {
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
