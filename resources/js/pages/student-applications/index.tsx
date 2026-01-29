import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { 
    Search, 
    Filter, 
    Plus, 
    Eye, 
    Edit, 
    Trash2, 
    Calendar,
    DollarSign,
    Globe,
    GraduationCap,
    User,
    AlertCircle,
    CheckCircle,
    Clock,
    XCircle
} from 'lucide-react';

interface StudentApplication {
    id: number;
    student: {
        id: number;
        first_name: string;
        last_name: string;
        email: string;
    };
    university: {
        id: number;
        name: string;
    };
    course: {
        id: number;
        name: string;
    };
    application_status: string;
    visa_status: string;
    pre_departure_status: string;
    application_date?: string;
    submission_deadline?: string;
    tuition_fee?: number;
    scholarship_amount?: number;
    created_at: string;
}

interface University {
    id: number;
    name: string;
}

interface Props {
    applications: {
        data: StudentApplication[];
        links: any[];
        meta: any;
    };
    universities: University[];
    filters: {
        search?: string;
        status?: string;
        visa_status?: string;
        university?: string;
        per_page?: number;
    };
}

const applicationStatuses = [
    { value: 'draft', label: 'Draft', variant: 'secondary' as const },
    { value: 'submitted', label: 'Submitted', variant: 'default' as const },
    { value: 'under_review', label: 'Under Review', variant: 'secondary' as const },
    { value: 'admitted', label: 'Admitted', variant: 'default' as const },
    { value: 'rejected', label: 'Rejected', variant: 'destructive' as const },
    { value: 'enrolled', label: 'Enrolled', variant: 'default' as const },
    { value: 'withdrawn', label: 'Withdrawn', variant: 'outline' as const },
    { value: 'deferred', label: 'Deferred', variant: 'secondary' as const },
];

const visaStatuses = [
    { value: 'not_started', label: 'Not Started', variant: 'secondary' as const },
    { value: 'documents_collected', label: 'Documents Collected', variant: 'default' as const },
    { value: 'application_submitted', label: 'Application Submitted', variant: 'default' as const },
    { value: 'interview_scheduled', label: 'Interview Scheduled', variant: 'secondary' as const },
    { value: 'interview_completed', label: 'Interview Completed', variant: 'default' as const },
    { value: 'approved', label: 'Approved', variant: 'default' as const },
    { value: 'rejected', label: 'Rejected', variant: 'destructive' as const },
    { value: 'issued', label: 'Issued', variant: 'default' as const },
];

export default function StudentApplicationsIndex({ applications, universities, filters }: Props) {
    const [searchTerm, setSearchTerm] = useState(filters.search || '');
    const [selectedStatus, setSelectedStatus] = useState(filters.status || '');
    const [selectedVisaStatus, setSelectedVisaStatus] = useState(filters.visa_status || '');
    const [selectedUniversity, setSelectedUniversity] = useState(filters.university || '');
    const [perPage, setPerPage] = useState(filters.per_page || 10);

    const handleSearch = () => {
        router.get('/student-applications', {
            search: searchTerm,
            status: selectedStatus,
            visa_status: selectedVisaStatus,
            university: selectedUniversity,
            per_page: perPage,
        }, { preserveState: true });
    };

    const handleDelete = (id: number) => {
        if (confirm('Are you sure you want to delete this application?')) {
            router.delete(`/student-applications/${id}`);
        }
    };

    const getStatusBadge = (status: string, statuses: any[]) => {
        const statusObj = statuses.find(s => s.value === status);
        return statusObj || { label: status, variant: 'secondary' as const };
    };

    const getNetFee = (tuition?: number, scholarship?: number) => {
        if (!tuition) return 0;
        return tuition - (scholarship || 0);
    };

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Student Applications', href: '/student-applications' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Student Applications" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold">Student Applications</h1>
                        <p className="text-muted-foreground">
                            Manage student applications for abroad studies
                        </p>
                    </div>
                    <Button asChild>
                        <Link href="/student-applications/create">
                            <Plus className="h-4 w-4 mr-2" />
                            New Application
                        </Link>
                    </Button>
                </div>

                {/* Filters */}
                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <Filter className="h-5 w-5" />
                            Filters
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-6">
                            <div className="space-y-2">
                                <Label htmlFor="search">Search Student</Label>
                                <div className="relative">
                                    <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <Input
                                        id="search"
                                        placeholder="Search by name..."
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                        className="pl-10"
                                    />
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="status">Application Status</Label>
                                <Select value={selectedStatus} onValueChange={setSelectedStatus}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="All Statuses" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="">All Statuses</SelectItem>
                                        {applicationStatuses.map((status) => (
                                            <SelectItem key={status.value} value={status.value}>
                                                {status.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="visa_status">Visa Status</Label>
                                <Select value={selectedVisaStatus} onValueChange={setSelectedVisaStatus}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="All Visa Statuses" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="">All Visa Statuses</SelectItem>
                                        {visaStatuses.map((status) => (
                                            <SelectItem key={status.value} value={status.value}>
                                                {status.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="university">University</Label>
                                <Select value={selectedUniversity} onValueChange={setSelectedUniversity}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="All Universities" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="">All Universities</SelectItem>
                                        {universities.map((university) => (
                                            <SelectItem key={university.id} value={university.id.toString()}>
                                                {university.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="per_page">Per Page</Label>
                                <Select value={perPage.toString()} onValueChange={(value) => setPerPage(parseInt(value))}>
                                    <SelectTrigger>
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="10">10</SelectItem>
                                        <SelectItem value="25">25</SelectItem>
                                        <SelectItem value="50">50</SelectItem>
                                        <SelectItem value="100">100</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="flex items-end">
                                <Button onClick={handleSearch} className="w-full">
                                    <Search className="h-4 w-4 mr-2" />
                                    Search
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Applications Table */}
                <Card>
                    <CardHeader>
                        <CardTitle>Applications ({applications.meta.total})</CardTitle>
                        <CardDescription>
                            Showing {applications.meta.from} to {applications.meta.to} of {applications.meta.total} applications
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b">
                                        <th className="text-left p-4">Student</th>
                                        <th className="text-left p-4">University</th>
                                        <th className="text-left p-4">Course</th>
                                        <th className="text-left p-4">Application Status</th>
                                        <th className="text-left p-4">Visa Status</th>
                                        <th className="text-left p-4">Net Fee</th>
                                        <th className="text-left p-4">Created</th>
                                        <th className="text-left p-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {applications.data.map((application) => {
                                        const appStatus = getStatusBadge(application.application_status, applicationStatuses);
                                        const visaStatus = getStatusBadge(application.visa_status, visaStatuses);
                                        const netFee = getNetFee(application.tuition_fee, application.scholarship_amount);

                                        return (
                                            <tr key={application.id} className="border-b hover:bg-muted/50">
                                                <td className="p-4">
                                                    <div className="flex items-center gap-2">
                                                        <User className="h-4 w-4 text-muted-foreground" />
                                                        <div>
                                                            <div className="font-medium">
                                                                {application.student.first_name} {application.student.last_name}
                                                            </div>
                                                            <div className="text-sm text-muted-foreground">
                                                                {application.student.email}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td className="p-4">
                                                    <div className="flex items-center gap-2">
                                                        <Globe className="h-4 w-4 text-muted-foreground" />
                                                        {application.university.name}
                                                    </div>
                                                </td>
                                                <td className="p-4">
                                                    <div className="flex items-center gap-2">
                                                        <GraduationCap className="h-4 w-4 text-muted-foreground" />
                                                        {application.course.name}
                                                    </div>
                                                </td>
                                                <td className="p-4">
                                                    <Badge variant={appStatus.variant}>
                                                        {appStatus.label}
                                                    </Badge>
                                                </td>
                                                <td className="p-4">
                                                    <Badge variant={visaStatus.variant}>
                                                        {visaStatus.label}
                                                    </Badge>
                                                </td>
                                                <td className="p-4">
                                                    <div className="flex items-center gap-2">
                                                        <DollarSign className="h-4 w-4 text-muted-foreground" />
                                                        ${netFee.toLocaleString()}
                                                    </div>
                                                </td>
                                                <td className="p-4">
                                                    <div className="flex items-center gap-2">
                                                        <Calendar className="h-4 w-4 text-muted-foreground" />
                                                        {new Date(application.created_at).toLocaleDateString()}
                                                    </div>
                                                </td>
                                                <td className="p-4">
                                                    <div className="flex items-center gap-2">
                                                        <Button variant="ghost" size="sm" asChild>
                                                            <Link href={`/student-applications/${application.id}`}>
                                                                <Eye className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button variant="ghost" size="sm" asChild>
                                                            <Link href={`/student-applications/${application.id}/edit`}>
                                                                <Edit className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button 
                                                            variant="ghost" 
                                                            size="sm"
                                                            onClick={() => handleDelete(application.id)}
                                                        >
                                                            <Trash2 className="h-4 w-4" />
                                                        </Button>
                                                    </div>
                                                </td>
                                            </tr>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>

                        {applications.data.length === 0 && (
                            <div className="text-center py-8">
                                <AlertCircle className="h-12 w-12 text-muted-foreground mx-auto mb-4" />
                                <h3 className="text-lg font-semibold mb-2">No applications found</h3>
                                <p className="text-muted-foreground mb-4">
                                    Get started by creating a new student application.
                                </p>
                                <Button asChild>
                                    <Link href="/student-applications/create">
                                        <Plus className="h-4 w-4 mr-2" />
                                        New Application
                                    </Link>
                                </Button>
                            </div>
                        )}

                        {/* Pagination */}
                        {applications.links.length > 3 && (
                            <div className="flex items-center justify-between mt-4">
                                <div className="text-sm text-muted-foreground">
                                    Showing {applications.meta.from} to {applications.meta.to} of {applications.meta.total} results
                                </div>
                                <div className="flex items-center gap-2">
                                    {applications.links.map((link, index) => (
                                        <Button
                                            key={index}
                                            variant={link.active ? 'default' : 'outline'}
                                            size="sm"
                                            disabled={!link.url}
                                            asChild={!!link.url}
                                        >
                                            {link.url ? (
                                                <Link href={link.url}>
                                                    <span dangerouslySetInnerHTML={{ __html: link.label }} />
                                                </Link>
                                            ) : (
                                                <span dangerouslySetInnerHTML={{ __html: link.label }} />
                                            )}
                                        </Button>
                                    ))}
                                </div>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
