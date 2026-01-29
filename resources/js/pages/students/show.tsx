import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Pencil, ArrowLeft, Users, Building, User, GraduationCap, Mail, Phone, Calendar, Globe, MapPin, FileText, Plus } from 'lucide-react';

type Student = {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone?: string;
    address?: string;
    date_of_birth?: string;
    status: string;
    image_path?: string;
    created_at: string;
    updated_at: string;
    branch?: {
        id: number;
        name: string;
    };
    agent?: {
        id: number;
        name: string;
    };
    course?: {
        id: number;
        name: string;
    };
    country?: {
        id: number;
        name: string;
    };
    state?: {
        id: number;
        name: string;
    };
};

export default function StudentShow({ student }: { student: Student }) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Students',
            href: '/students',
        },
        {
            title: `${student.first_name} ${student.last_name}`,
            href: `/students/${student.id}`,
        },
    ];

    const getStatusBadge = (status: string) => {
        const statusConfig = {
            active: { label: 'Active', variant: 'default' as const },
            inactive: { label: 'Inactive', variant: 'secondary' as const },
            graduated: { label: 'Graduated', variant: 'outline' as const },
            suspended: { label: 'Suspended', variant: 'destructive' as const },
        };
        return statusConfig[status as keyof typeof statusConfig] || { label: status, variant: 'secondary' as const };
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${student.first_name} ${student.last_name}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="View Student" />
                            <div className="flex gap-2">
                                <Button variant="outline" asChild>
                                    <Link href={`/students/${student.id}/documents`}>
                                        <FileText className="h-4 w-4 mr-2" />
                                        Documents
                                    </Link>
                                </Button>
                                <Button variant="outline" asChild>
                                    <Link href={`/students/${student.id}/edit`}>
                                        <Pencil className="h-4 w-4 mr-2" />
                                        Edit
                                    </Link>
                                </Button>
                                <Button asChild>
                                    <Link href="/students">
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
                                    {student.image_path ? (
                                        <img
                                            src={student.image_path}
                                            alt={`${student.first_name} ${student.last_name}`}
                                            className="h-32 w-32 rounded-full object-cover"
                                        />
                                    ) : (
                                        <div className="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center">
                                            <Users className="h-16 w-16 text-gray-500" />
                                        </div>
                                    )}
                                </div>
                                
                                <div className="text-center">
                                    <h2 className="text-2xl font-bold">
                                        {student.first_name} {student.last_name}
                                    </h2>
                                    <Badge variant={getStatusBadge(student.status).variant} className="mt-2">
                                        {getStatusBadge(student.status).label}
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
                                                href={`mailto:${student.email}`}
                                                className="text-blue-600 hover:text-blue-800 underline"
                                            >
                                                {student.email}
                                            </a>
                                        </div>
                                        {student.phone && (
                                            <div className="flex items-center gap-3">
                                                <Phone className="h-4 w-4 text-muted-foreground" />
                                                <a
                                                    href={`tel:${student.phone}`}
                                                    className="text-blue-600 hover:text-blue-800 underline"
                                                >
                                                    {student.phone}
                                                </a>
                                            </div>
                                        )}
                                        {student.address && (
                                            <div className="flex items-center gap-3">
                                                <MapPin className="h-4 w-4 text-muted-foreground" />
                                                <span>{student.address}</span>
                                            </div>
                                        )}
                                        {student.date_of_birth && (
                                            <div className="flex items-center gap-3">
                                                <Calendar className="h-4 w-4 text-muted-foreground" />
                                                <span>
                                                    {new Date(student.date_of_birth).toLocaleDateString()}
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Assignment Information</h3>
                                    <div className="space-y-3">
                                        {student.branch && (
                                            <div className="flex items-center gap-3">
                                                <Building className="h-4 w-4 text-muted-foreground" />
                                                <Badge variant="outline">
                                                    {student.branch.name}
                                                </Badge>
                                            </div>
                                        )}
                                        {student.agent && (
                                            <div className="flex items-center gap-3">
                                                <User className="h-4 w-4 text-muted-foreground" />
                                                <Badge variant="outline">
                                                    {student.agent.name}
                                                </Badge>
                                            </div>
                                        )}
                                        {student.course && (
                                            <div className="flex items-center gap-3">
                                                <GraduationCap className="h-4 w-4 text-muted-foreground" />
                                                <Badge variant="outline">
                                                    {student.course.name}
                                                </Badge>
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Location Information</h3>
                                    <div className="space-y-3">
                                        {student.country && (
                                            <div className="flex items-center gap-3">
                                                <Globe className="h-4 w-4 text-muted-foreground" />
                                                <Badge variant="secondary">
                                                    {student.country.name}
                                                </Badge>
                                            </div>
                                        )}
                                        {student.state && (
                                            <div className="flex items-center gap-3">
                                                <MapPin className="h-4 w-4 text-muted-foreground" />
                                                <Badge variant="outline">
                                                    {student.state.name}
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
                                            <span className="text-muted-foreground">Student ID:</span>
                                            <span className="font-mono">#{student.id}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-muted-foreground">Created:</span>
                                            <span>{new Date(student.created_at).toLocaleDateString()}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-muted-foreground">Last Updated:</span>
                                            <span>{new Date(student.updated_at).toLocaleDateString()}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Quick Actions</h3>
                                    <div className="space-y-2">
                                        <Button className="w-full" variant="outline" asChild>
                                            <Link href={`/student-applications/create?student_id=${student.id}`}>
                                                <Plus className="h-4 w-4 mr-2" />
                                                Create Application
                                            </Link>
                                        </Button>
                                        <Button className="w-full" variant="outline" asChild>
                                            <Link href={`/students/${student.id}/edit`}>
                                                <Pencil className="h-4 w-4 mr-2" />
                                                Edit Student
                                            </Link>
                                        </Button>
                                        <Button className="w-full" variant="outline" asChild>
                                            <Link href="/courses/create">
                                                <GraduationCap className="h-4 w-4 mr-2" />
                                                Assign Course
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
