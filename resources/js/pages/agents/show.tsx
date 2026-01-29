import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Pencil, ArrowLeft, Users, Building, Mail, Phone, Calendar } from 'lucide-react';

type Agent = {
    id: number;
    name: string;
    email: string;
    phone?: string;
    code: string;
    image_path?: string;
    created_at: string;
    updated_at: string;
    branch?: {
        id: number;
        name: string;
    };
    parent_agent?: {
        id: number;
        name: string;
    };
};

export default function AgentShow({ agent }: { agent: Agent }) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Agents',
            href: '/agents',
        },
        {
            title: agent.name,
            href: `/agents/${agent.id}`,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={agent.name} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="View Agent" />
                            <div className="flex gap-2">
                                <Button variant="outline" asChild>
                                    <Link href={`/agents/${agent.id}/edit`}>
                                        <Pencil className="h-4 w-4 mr-2" />
                                        Edit
                                    </Link>
                                </Button>
                                <Button asChild>
                                    <Link href="/agents">
                                        <ArrowLeft className="h-4 w-4 mr-2" />
                                        Back
                                    </Link>
                                </Button>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent className="pt-6">
                        <div className="grid gap-6 md:grid-cols-2">
                            <div className="space-y-6">
                                <div className="flex justify-center">
                                    {agent.image_path ? (
                                        <img
                                            src={agent.image_path}
                                            alt={agent.name}
                                            className="h-32 w-32 rounded-full object-cover"
                                        />
                                    ) : (
                                        <div className="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center">
                                            <Users className="h-16 w-16 text-gray-500" />
                                        </div>
                                    )}
                                </div>
                                
                                <div className="text-center">
                                    <h2 className="text-2xl font-bold">{agent.name}</h2>
                                    <Badge variant="outline" className="mt-2">
                                        {agent.code}
                                    </Badge>
                                </div>
                            </div>

                            <div className="space-y-6">
                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Contact Information</h3>
                                    <div className="space-y-3">
                                        <div className="flex items-center gap-3">
                                            <Mail className="h-4 w-4 text-muted-foreground" />
                                            <a
                                                href={`mailto:${agent.email}`}
                                                className="text-blue-600 hover:text-blue-800 underline"
                                            >
                                                {agent.email}
                                            </a>
                                        </div>
                                        {agent.phone && (
                                            <div className="flex items-center gap-3">
                                                <Phone className="h-4 w-4 text-muted-foreground" />
                                                <a
                                                    href={`tel:${agent.phone}`}
                                                    className="text-blue-600 hover:text-blue-800 underline"
                                                >
                                                    {agent.phone}
                                                </a>
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Assignment Information</h3>
                                    <div className="space-y-3">
                                        {agent.branch && (
                                            <div className="flex items-center gap-3">
                                                <Building className="h-4 w-4 text-muted-foreground" />
                                                <Badge variant="secondary">
                                                    {agent.branch.name}
                                                </Badge>
                                            </div>
                                        )}
                                        {agent.parent_agent && (
                                            <div className="flex items-center gap-3">
                                                <Users className="h-4 w-4 text-muted-foreground" />
                                                <Badge variant="outline">
                                                    {agent.parent_agent.name}
                                                </Badge>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="mt-8 pt-6 border-t">
                            <div className="grid gap-6 md:grid-cols-2">
                                <div>
                                    <h3 className="text-lg font-semibold mb-4">System Information</h3>
                                    <div className="space-y-2 text-sm">
                                        <div className="flex justify-between">
                                            <span className="text-muted-foreground">Agent ID:</span>
                                            <span className="font-mono">#{agent.id}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-muted-foreground">Created:</span>
                                            <span>{new Date(agent.created_at).toLocaleDateString()}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-muted-foreground">Last Updated:</span>
                                            <span>{new Date(agent.updated_at).toLocaleDateString()}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Quick Actions</h3>
                                    <div className="space-y-2">
                                        <Button className="w-full" variant="outline" asChild>
                                            <Link href={`/agents/${agent.id}/edit`}>
                                                <Pencil className="h-4 w-4 mr-2" />
                                                Edit Agent
                                            </Link>
                                        </Button>
                                        <Button className="w-full" variant="outline" asChild>
                                            <Link href="/students/create">
                                                <Users className="h-4 w-4 mr-2" />
                                                Add Student
                                            </Link>
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
