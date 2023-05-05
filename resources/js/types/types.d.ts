declare interface User {
    id: number;
    last_name: string;
    first_name: string;
    email: string;
    password: string;
    remember_token: string;
    civility_id: number;
    role_id: number;
    created_at: Date;
    updated_at: Date;
    label: string;
    name: string;
    options: Array<{ id: number; name: string }>;
}

declare interface Role {
    id: number;
    name: string;
    created_at: Date;
    updated_at: Date;
}

declare interface Permission {
    id: number;
    name: string;
}

declare interface Group {
    id: number;
    name: string;
    created_at: Date;
    updated_at: Date;
}

declare interface Civility {
    id: number;
    label: string;
    name: string;
}

declare interface Status {
    id: number;
    name: string;
}

declare interface Session {
    id: number;
    title: string;
    description: string;
    start_date: Date;
    end_date: Date;
    status_id: number;
    created_at: Date;
    updated_at: Date;
    allowed: boolean;
}

declare interface Vote {
    id: number;
    title: string;
    description: string;
    start_date: Date;
    end_date: Date;
    users: Array<number>;
    status_id: number;
    type_id: number;
    created_at: Date;
    updated_at: Date;
    allowed: boolean;
}

declare interface VoteType {
    id: number;
    name: string;
}

declare interface Link {
    active: boolean;
    label: string;
    url: string;
}
