export type * from './auth';
export type * from './navigation';
export type * from './pagination';
export type * from './ui';

import type { Auth } from './auth';

export type SharedData = {
    name: string;
    auth: Auth;
    sidebarOpen: boolean;
    flash?: {
        success?: string | null;
        error?: string | null;
        info?: string | null;
        warning?: string | null;
    };
    [key: string]: unknown;
};
