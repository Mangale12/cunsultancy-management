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
import { Save, ArrowLeft, XCircle, Users, Building, User, GraduationCap, Mail, Phone, Calendar, Globe, MapPin } from 'lucide-react';
import type { FormEvent } from 'react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Students',
        href: '/students',
    },
    {
        title: 'Create',
        href: '/students/create',
    },
];

type Branch = {
    id: number;
    name: string;
};

type Agent = {
    id: number;
    name: string;
    branch_id?: number;
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

type Course = {
    id: number;
    name: string;
};

export default function StudentCreate({
    branches,
    agents,
    countries,
    states,
    courses,
}: {
    branches: Branch[];
    agents: Agent[];
    countries: Country[];
    states: State[];
    courses: Course[];
}) {
    const form = useForm({
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        address: '',
        date_of_birth: '',
        country_id: '',
        state_id: '',
        branch_id: '',
        agent_id: '',
        course_id: '',
        status: 'active',
        image_path: '',
    });

    const [preview, setPreview] = useState<string | null>(null);

    const submit = (e: FormEvent) => {
        e.preventDefault();
        
        // Update branch_id to empty string if it's "select" before submitting
        if (form.data.branch_id === 'select') {
            form.setData('branch_id', '');
        }
        
        // Update agent_id to empty string if it's "select" before submitting
        if (form.data.agent_id === 'select') {
            form.setData('agent_id', '');
        }
        
        // Update course_id to empty string if it's "select" before submitting
        if (form.data.course_id === 'select') {
            form.setData('course_id', '');
        }
        
        // Update country_id to empty string if it's "select" before submitting
        if (form.data.country_id === 'select') {
            form.setData('country_id', '');
        }
        
        // Update state_id to empty string if it's "select" before submitting
        if (form.data.state_id === 'select') {
            form.setData('state_id', '');
        }

        form.post('/students');
    };

    // Filter agents based on selected branch
    const filteredAgents = form.data.branch_id && form.data.branch_id !== 'select'
        ? agents.filter(agent => agent.branch_id?.toString() === form.data.branch_id)
        : agents;

    // Filter states based on selected country
    const filteredStates = form.data.country_id && form.data.country_id !== 'select'
        ? states.filter(state => state.country_id.toString() === form.data.country_id)
        : states;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Student" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Create Student" />
                            <Button asChild>
                                <Link href="/students">
                                    <ArrowLeft className="h-4 w-4 mr-2" />
                                    Back
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={submit} className="space-y-6">
                            {/* Personal Information */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">Personal Information</h3>
                                <div className="grid gap-6 md:grid-cols-2">
                                    <div className="space-y-2">
                                        <Label htmlFor="first_name">First Name</Label>
                                        <Input
                                            id="first_name"
                                            value={form.data.first_name}
                                            onChange={(e) => form.setData('first_name', e.target.value)}
                                            required
                                            placeholder="Enter first name"
                                        />
                                        <InputError message={form.errors.first_name} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="last_name">Last Name</Label>
                                        <Input
                                            id="last_name"
                                            value={form.data.last_name}
                                            onChange={(e) => form.setData('last_name', e.target.value)}
                                            required
                                            placeholder="Enter last name"
                                        />
                                        <InputError message={form.errors.last_name} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="email">Email Address</Label>
                                        <Input
                                            id="email"
                                            type="email"
                                            value={form.data.email}
                                            onChange={(e) => form.setData('email', e.target.value)}
                                            required
                                            placeholder="student@example.com"
                                        />
                                        <InputError message={form.errors.email} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="phone">Phone Number</Label>
                                        <Input
                                            id="phone"
                                            type="tel"
                                            value={form.data.phone}
                                            onChange={(e) => form.setData('phone', e.target.value)}
                                            placeholder="+1 (555) 123-4567"
                                        />
                                        <InputError message={form.errors.phone} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="date_of_birth">Date of Birth</Label>
                                        <Input
                                            id="date_of_birth"
                                            type="date"
                                            value={form.data.date_of_birth}
                                            onChange={(e) => form.setData('date_of_birth', e.target.value)}
                                        />
                                        <InputError message={form.errors.date_of_birth} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="address">Address</Label>
                                        <Input
                                            id="address"
                                            value={form.data.address}
                                            onChange={(e) => form.setData('address', e.target.value)}
                                            placeholder="Enter full address"
                                        />
                                        <InputError message={form.errors.address} />
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

                            {/* Assignment */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">Assignment</h3>
                                <div className="grid gap-6 md:grid-cols-3">
                                    <div className="space-y-2">
                                        <Label htmlFor="branch_id">Branch</Label>
                                        <Select value={form.data.branch_id} onValueChange={(value) => {
                                            form.setData('branch_id', value);
                                            // Clear agent_id when branch is selected
                                            form.setData('agent_id', '');
                                        }}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a branch" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="select">Select a branch</SelectItem>
                                                {branches.map((branch) => (
                                                    <SelectItem key={branch.id} value={branch.id.toString()}>
                                                        {branch.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={form.errors.branch_id} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="agent_id">Agent</Label>
                                        <Select value={form.data.agent_id} onValueChange={(value) => form.setData('agent_id', value)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select an agent (optional)" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="select">No agent</SelectItem>
                                                {filteredAgents.map((agent) => (
                                                    <SelectItem key={agent.id} value={agent.id.toString()}>
                                                        {agent.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={form.errors.agent_id} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="course_id">Course</Label>
                                        <Select value={form.data.course_id} onValueChange={(value) => form.setData('course_id', value)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a course (optional)" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="select">No course</SelectItem>
                                                {courses.map((course) => (
                                                    <SelectItem key={course.id} value={course.id.toString()}>
                                                        {course.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={form.errors.course_id} />
                                    </div>
                                </div>
                            </div>

                            {/* Status */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">Status</h3>
                                <div className="grid gap-6 md:grid-cols-2">
                                    <div className="space-y-2">
                                        <Label htmlFor="status">Status</Label>
                                        <Select value={form.data.status} onValueChange={(value) => form.setData('status', value)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select status" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="active">Active</SelectItem>
                                                <SelectItem value="inactive">Inactive</SelectItem>
                                                <SelectItem value="graduated">Graduated</SelectItem>
                                                <SelectItem value="suspended">Suspended</SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <InputError message={form.errors.status} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="image_path">Profile Image URL</Label>
                                        <Input
                                            id="image_path"
                                            type="url"
                                            value={form.data.image_path}
                                            onChange={(e) => form.setData('image_path', e.target.value)}
                                            placeholder="https://example.com/student-photo.jpg"
                                        />
                                        <InputError message={form.errors.image_path} />
                                    </div>
                                </div>
                            </div>

                            <div className="flex justify-end gap-4">
                                <Button type="button" variant="outline" asChild>
                                    <Link href="/students">
                                        <XCircle className="h-4 w-4 mr-2" />
                                        Cancel
                                    </Link>
                                </Button>
                                <Button type="submit" disabled={form.processing}>
                                    <Save className="h-4 w-4 mr-2" />
                                    {form.processing ? 'Creating...' : 'Create Student'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
