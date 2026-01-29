import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Pencil, ArrowLeft, Globe } from 'lucide-react';

type Country = {
    id: number;
    name: string;
};

export default function CountryShow({ country }: { country: Country }) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Countries',
            href: '/countries',
        },
        {
            title: country.name,
            href: `/countries/${country.id}`,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={country.name} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="View Country" />
                            <Button asChild>
                                <Link href="/countries">
                                    <ArrowLeft className="h-4 w-4 mr-2" />
                                    Back
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent className="pt-6">
                        <div className="grid gap-6 md:grid-cols-2">
                            <div className="space-y-6">
                                <div className="flex justify-center">
                                    <div className="h-32 w-32 rounded-xl bg-muted border-2 flex items-center justify-center">
                                        <Globe className="h-16 w-16 text-muted-foreground" />
                                    </div>
                                </div>
                                
                                <div className="space-y-4">
                                    <div>
                                        <h3 className="text-lg font-semibold">Country Information</h3>
                                        <div className="mt-4">
                                            <div className="font-medium text-lg">{country.name}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div className="space-y-6">
                                <div className="space-y-4">
                                    <h3 className="text-lg font-semibold">Details</h3>
                                    <div className="grid gap-4">
                                        <div>
                                            <div className="text-sm text-muted-foreground">Country Name</div>
                                            <div className="font-medium text-lg">{country.name}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div className="flex justify-end">
                                    <Button asChild>
                                        <Link href={`/countries/${country.id}/edit`}>
                                            <Pencil className="h-4 w-4 mr-2" />
                                            Edit Country
                                        </Link>
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
