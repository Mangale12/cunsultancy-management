import Heading from '@/components/heading';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Universities',
        href: '/universities',
    },
];

export default function UniversitiesIndex() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Universities" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <Heading title="Universities" description="Manage universities" />
            </div>
        </AppLayout>
    );
}
