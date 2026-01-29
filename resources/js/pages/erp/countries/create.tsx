import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { Save, ArrowLeft, XCircle } from 'lucide-react';
import type { FormEvent } from 'react';
import { useEffect, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Countries',
        href: '/countries',
    },
    {
        title: 'Create',
        href: '/countries/create',
    },
];

export default function CountryCreate() {
    const form = useForm({
        name: '',
    });

    const submit = (e: FormEvent) => {
        e.preventDefault();

        form.post('/countries', {
            forceFormData: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Country" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Create Country" />
                            <Button asChild>
                                <Link href="/countries">
                                    <ArrowLeft className="h-4 w-4 mr-2" />
                                    Back
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={submit} className="space-y-6">
                            <div className="space-y-2">
                                <Label htmlFor="name">Name</Label>
                                <Input
                                    id="name"
                                    value={form.data.name}
                                    onChange={(e) => form.setData('name', e.target.value)}
                                    required
                                    placeholder="Enter country name"
                                />
                                <InputError message={form.errors.name} />
                            </div>

                            <div className="flex justify-end gap-2">
                                <Button variant="outline" type="button" asChild>
                                    <Link href="/countries"><XCircle className="h-4 w-4 mr-2" /> Cancel</Link>
                                </Button>
                                <Button type="submit" disabled={form.processing}>
                                    {form.processing ? (
                                        <>
                                            <Spinner className="h-4 w-4 mr-2" />
                                            Creating...
                                        </>
                                    ) : (
                                        <>
                                            <Save className="h-4 w-4 mr-2" />
                                            Create Country
                                        </>
                                    )}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
