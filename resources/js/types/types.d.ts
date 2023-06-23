export interface User {
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

export interface Role {
    id: number;
    name: string;
    created_at: Date;
    updated_at: Date;
}

export interface Permission {
    id: number;
    name: string;
}

export interface Group {
    id: number;
    name: string;
    created_at: Date;
    updated_at: Date;
}

export interface Civility {
    id: number;
    label: string;
    name: string;
}

export interface Status {
    id: number;
    name: string;
}

export interface Session {
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

export interface Vote {
    id: number;
    title: string;
    description: string;
    start_date: Date;
    end_date: Date;
    users: Array<number>;
    answers: Array<VoteAnswer>;
    results: Array<VoteResult>;
    label_sets: Array<LabelSet>;
    status_id: number;
    type_id: number;
    created_at: Date;
    updated_at: Date;
    allowed: boolean;
    voted: boolean;
}

export interface VoteType {
    id: number;
    name: string;
}

export interface VoteAnswer {
    id: number;
    name: string;
    color: string;
    vote_id?: number;
    label_set_id?: number;
}

export interface VoteResult {
    answer_id: number;
    name: string;
    color: string;
    date: Date;
    count: number;
}

export interface LabelSet {
    id: number;
    name: string;
    answers: Array<Answer>;
}

export interface Answer {
    id: number;
    name: string;
    color: string;
    label_set_id: number;
}

export interface ILink {
    active: boolean;
    label: string;
    url: string;
}
