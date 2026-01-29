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
import { Save, ArrowLeft, XCircle, Users, Building, Mail, Phone } from 'lucide-react';
import type { FormEvent } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Agents',
        href: '/agents',
    },
    {
        title: 'Create',
        href: '/agents/create',
    },
];

type Branch = {
    id: number;
    name: string;
};

type Agent = {
    id: number;
    name: string;
};

export default function AgentCreate({
    branches,
    parentAgents,
}: {
    branches: Branch[];
    parentAgents: Agent[];
}) {
    const form = useForm({
        name: '',
        email: '',
        phone: '',
        code: '',
        branch_id: '',
        parent_agent_id: '',
        image_path: '',
    });

    const submit = (e: FormEvent) => {
        e.preventDefault();
        
        // Update branch_id to empty string if it's "select" before submitting
        if (form.data.branch_id === 'select') {
            form.setData('branch_id', '');
        }
        
        // Update parent_agent_id to empty string if it's "select" before submitting
        if (form.data.parent_agent_id === 'select') {
            form.setData('parent_agent_id', '');
        }

        form.post('/agents');
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Agent" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Create Agent" />
                            <Button asChild>
                                <Link href="/agents">
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
                                        <Label htmlFor="name">Name</Label>
                                        <Input
                                            id="name"
                                            value={form.data.name}
                                            onChange={(e) => form.setData('name', e.target.value)}
                                            required
                                            placeholder="Enter agent name"
                                        />
                                        <InputError message={form.errors.name} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="email">Email Address</Label>
                                        <Input
                                            id="email"
                                            type="email"
                                            value={form.data.email}
                                            onChange={(e) => form.setData('email', e.target.value)}
                                            required
                                            placeholder="agent@example.com"
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
                                        <Label htmlFor="code">Agent Code</Label>
                                        <Input
                                            id="code"
                                            value={form.data.code}
                                            onChange={(e) => form.setData('code', e.target.value)}
                                            required
                                            placeholder="Enter agent code"
                                        />
                                        <InputError message={form.errors.code} />
                                    </div>

                                    <div className="space-y-2 md:col-span-2">
                                        <Label htmlFor="image_path">Profile Image URL</Label>
                                        <Input
                                            id="image_path"
                                            type="url"
                                            value={form.data.image_path}
                                            onChange={(e) => form.setData('image_path', e.target.value)}
                                            placeholder="https://example.com/agent-photo.jpg"
                                        />
                                        <InputError message={form.errors.image_path} />
                                    </div>
                                </div>
                            </div>

                            {/* Assignment */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">Assignment</h3>
                                <div className="grid gap-6 md:grid-cols-2">
                                    <div className="space-y-2">
                                        <Label htmlFor="branch_id">Branch</Label>
                                        <Select value={form.data.branch_id} onValueChange={(value) => form.setData('branch_id', value)}>
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
                                        <Label htmlFor="parent_agent_id">Parent Agent</Label>
                                        <Select value={form.data.parent_agent_id} onValueChange={(value) => form.setData('parent_agent_id', value)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a parent agent (optional)" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="select">No parent agent</SelectItem>
                                                {parentAgents.map((agent) => (
                                                    <SelectItem key={agent.id} value={agent.id.toString()}>
                                                        {agent.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={form.errors.parent_agent_id} />
                                    </div>
                                </div>
                            </div>

                            <div className="flex justify-end gap-4">
                                <Button type="button" variant="outline" asChild>
                                    <Link href="/agents">
                                        <XCircle className="h-4 w-4 mr-2" />
                                        Cancel
                                    </Link>
                                </Button>
                                <Button type="submit" disabled={form.processing}>
                                    <Save className="h-4 w-4 mr-2" />
                                    {form.processing ? 'Creating...' : 'Create Agent'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
