import Heading from '@/components/heading';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Agents',
        href: '/agents',
    },
];

export default function AgentsIndex() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Agents" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <Heading title="Agents" description="Manage agents" />
            </div>
        </AppLayout>
    );
}
