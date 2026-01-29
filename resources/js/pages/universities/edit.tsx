import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { Save, ArrowLeft, XCircle, GraduationCap, Globe, MapPin } from 'lucide-react';
import type { FormEvent } from 'react';

type University = {
    id: number;
    name: string;
    code: string;
    image_path?: string;
    country_id?: number;
    state_id?: number;
};

type Country = {
    id: number;
    name: string;
};

type State = {
    id: number;
    name: string;
    country_id: number;
};

export default function UniversityEdit({
    university,
    countries,
    states,
}: {
    university: University;
    countries: Country[];
    states: State[];
}) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Universities',
            href: '/universities',
        },
        {
            title: university.name,
            href: `/universities/${university.id}`,
        },
        {
            title: 'Edit',
            href: `/universities/${university.id}/edit`,
        },
    ];

    const form = useForm({
        name: university.name,
        code: university.code,
        country_id: university.country_id?.toString() || '',
        state_id: university.state_id?.toString() || '',
        image_path: university.image_path || '',
    });

    const submit = (e: FormEvent) => {
        e.preventDefault();
        
        // Update country_id to empty string if it's "select" before submitting
        if (form.data.country_id === 'select') {
            form.setData('country_id', '');
        }
        
        // Update state_id to empty string if it's "select" before submitting
        if (form.data.state_id === 'select') {
            form.setData('state_id', '');
        }

        form.put(`/universities/${university.id}`);
    };

    // Filter states based on selected country
    const filteredStates = form.data.country_id && form.data.country_id !== 'select'
        ? states.filter(state => state.country_id.toString() === form.data.country_id)
        : states;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit ${university.name}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Edit University" />
                            <Button asChild>
                                <Link href="/universities">
                                    <ArrowLeft className="h-4 w-4 mr-2" />
                                    Back
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={submit} className="space-y-6">
                            {/* Basic Information */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">Basic Information</h3>
                                <div className="grid gap-6 md:grid-cols-2">
                                    <div className="space-y-2">
                                        <Label htmlFor="name">University Name</Label>
                                        <Input
                                            id="name"
                                            value={form.data.name}
                                            onChange={(e) => form.setData('name', e.target.value)}
                                            required
                                            placeholder="Enter university name"
                                        />
                                        <InputError message={form.errors.name} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="code">University Code</Label>
                                        <Input
                                            id="code"
                                            value={form.data.code}
                                            onChange={(e) => form.setData('code', e.target.value)}
                                            required
                                            placeholder="Enter university code"
                                        />
                                        <InputError message={form.errors.code} />
                                    </div>

                                    <div className="space-y-2 md:col-span-2">
                                        <Label htmlFor="image_path">University Logo URL</Label>
                                        <Input
                                            id="image_path"
                                            type="url"
                                            value={form.data.image_path}
                                            onChange={(e) => form.setData('image_path', e.target.value)}
                                            placeholder="https://example.com/university-logo.jpg"
                                        />
                                        <InputError message={form.errors.image_path} />
                                    </div>
                                </div>
                            </div>

                            {/* Location Information */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">Location Information</h3>
                                <div className="grid gap-6 md:grid-cols-2">
                                    <div className="space-y-2">
                                        <Label htmlFor="country_id">Country</Label>
                                        <Select value={form.data.country_id} onValueChange={(value) => form.setData('country_id', value)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a country" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="select">Select a country</SelectItem>
                                                {countries.map((country) => (
                                                    <SelectItem key={country.id} value={country.id.toString()}>
                                                        {country.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={form.errors.country_id} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="state_id">State</Label>
                                        <Select value={form.data.state_id} onValueChange={(value) => form.setData('state_id', value)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a state" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="select">Select a state</SelectItem>
                                                {filteredStates.map((state) => (
                                                    <SelectItem key={state.id} value={state.id.toString()}>
                                                        {state.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={form.errors.state_id} />
                                    </div>
                                </div>
                            </div>

                            <div className="flex justify-end gap-4">
                                <Button type="button" variant="outline" asChild>
                                    <Link href="/universities">
                                        <XCircle className="h-4 w-4 mr-2" />
                                        Cancel
                                    </Link>
                                </Button>
                                <Button type="submit" disabled={form.processing}>
                                    <Save className="h-4 w-4 mr-2" />
                                    {form.processing ? 'Updating...' : 'Update University'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
