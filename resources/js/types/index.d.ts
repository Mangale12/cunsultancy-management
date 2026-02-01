import { Page, PageProps as InertiaPageProps } from '@inertiajs/core';

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps {
        auth: {
            user: {
                id: number;
                name: string;
                email: string;
                email_verified_at: string | null;
                created_at: string;
                updated_at: string;
            } | null;
        };
        flash: {
            success?: string;
            error?: string;
            warning?: string;
            info?: string;
        };
    }
}

declare module '*.svg' {
    const content: React.FunctionComponent<React.SVGAttributes<SVGElement>>;
    export default content;
}

declare module '*.png';
declare module '*.jpg';
declare module '*.jpeg';
declare module '*.gif';
declare module '*.webp';

// Global type for route helper
declare function route(name: string, params?: any, absolute?: boolean): string;
