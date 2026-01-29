import { ConfirmDeleteDialog } from '@/components/confirm-delete-dialog';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import Heading from '@/components/heading';
import { Search, Plus, Edit, Eye, Trash2, User, Filter } from 'lucide-react';
import { useState } from 'react';

interface Student {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone: string | null;
    address: string | null;
    date_of_birth: string;
    status: string;
    branch: {
        id: number;
        name: string;
    };
    agent: {
        id: number;
        name: string;
    } | null;
    course: {
        id: number;
        name: string;
    } | null;
    country: {
        id: number;
        name: string;
    } | null;
    state: {
        id: number;
        name: string;
    } | null;
    created_at: string;
    updated_at: string;
}

interface Props {
    students: {
        data: Student[];
        current_page: number;
        from: number | null;
        last_page: number;
        per_page: number;
        to: number | null;
        total: number;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
    filters: {
        search?: string;
        branch?: string;
        agent?: string;
        course?: string;
        status?: string;
    };
    branches: Array<{
        id: number;
        name: string;
    }>;
    agents: Array<{
        id: number;
        name: string;
    }>;
    courses: Array<{
        id: number;
        name: string;
    }>;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Students',
        href: '/students',
    },
];

const statusOptions = [
    { value: 'active', label: 'Active' },
    { value: 'inactive', label: 'Inactive' },
    { value: 'pending', label: 'Pending' },
    { value: 'graduated', label: 'Graduated' },
    { value: 'suspended', label: 'Suspended' },
];

export default function StudentsIndex({ students, filters, branches, agents, courses }: Props) {
    const { data, setData } = useForm({
        search: filters.search || '',
        branch: filters.branch || '',
        agent: filters.agent || '',
        course: filters.course || '',
        status: filters.status || '',
    });
    
    const [deleteOpen, setDeleteOpen] = useState(false);
    const [deleteId, setDeleteId] = useState<number | null>(null);
    const deleteForm = useForm({});
    
    const onSearch = (e: React.FormEvent) => {
        e.preventDefault();
        const params = new URLSearchParams();
        if (data.search) params.append('search', data.search);
        if (data.branch) params.append('branch', data.branch);
        if (data.agent) params.append('agent', data.agent);
        if (data.course) params.append('course', data.course);
        if (data.status) params.append('status', data.status);
        
        window.location.href = `/students?${params.toString()}`;
    };
    
    const onClear = () => {
        setData({
            search: '',
            branch: '',
            agent: '',
            course: '',
            status: '',
        });
    };
    
    const onConfirmDelete = () => {
        if (!deleteId) return;
        deleteForm.delete(`/students/${deleteId}`, {
            preserveScroll: true,
            onSuccess: () => {
                setDeleteOpen(false);
                setDeleteId(null);
            },
        });
    };
    
    const handleDelete = (studentId: number, studentName: string) => {
        setDeleteId(studentId);
        setDeleteOpen(true);
    };

    const getStatusBadge = (status: string) => {
        const statusConfig = {
            active: { variant: 'default', label: 'Active' },
            inactive: { variant: 'secondary', label: 'Inactive' },
            pending: { variant: 'outline', label: 'Pending' },
            graduated: { variant: 'default', label: 'Graduated' },
            suspended: { variant: 'destructive', label: 'Suspended' },
        };
        
        const config = statusConfig[status as keyof typeof statusConfig] || { variant: 'secondary', label: status };
        
        return (
            <Badge variant={config.variant as any}>
                {config.label}
            </Badge>
        );
    };
    
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Students" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Students" />
                            <Button asChild>
                                <Link href="/students/create">
                                    <Plus className="h-4 w-4 mr-2" />
                                    Add Student
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        {/* Search and Filters */}
                        <form onSubmit={onSearch} className="space-y-4">
                            <div className="flex flex-col gap-3 md:flex-row md:items-center">
                                <div className="relative flex-1">
                                    <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                                    <Input
                                        placeholder="Search students..."
                                        value={data.search}
                                        onChange={(e) => setData('search', e.target.value)}
                                        className="pl-8"
                                    />
                                </div>
                                <Button type="submit" variant="outline">
                                    <Search className="h-4 w-4 mr-2" />
                                    Search
                                </Button>
                                <Button type="button" variant="outline" onClick={onClear}>
                                    <Filter className="h-4 w-4 mr-2" />
                                    Clear
                                </Button>
                            </div>
                            
                            {/* Advanced Filters */}
                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div className="space-y-2">
                                    <Label htmlFor="branch">Branch</Label>
                                    <select
                                        id="branch"
                                        value={data.branch}
                                        onChange={(e) => setData('branch', e.target.value)}
                                        className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                    >
                                        <option value="">All Branches</option>
                                        {branches.map((branch) => (
                                            <option key={branch.id} value={branch.id}>
                                                {branch.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                
                                <div className="space-y-2">
                                    <Label htmlFor="agent">Agent</Label>
                                    <select
                                        id="agent"
                                        value={data.agent}
                                        onChange={(e) => setData('agent', e.target.value)}
                                        className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                    >
                                        <option value="">All Agents</option>
                                        {agents.map((agent) => (
                                            <option key={agent.id} value={agent.id}>
                                                {agent.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                
                                <div className="space-y-2">
                                    <Label htmlFor="course">Course</Label>
                                    <select
                                        id="course"
                                        value={data.course}
                                        onChange={(e) => setData('course', e.target.value)}
                                        className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                    >
                                        <option value="">All Courses</option>
                                        {courses.map((course) => (
                                            <option key={course.id} value={course.id}>
                                                {course.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                
                                <div className="space-y-2">
                                    <Label htmlFor="status">Status</Label>
                                    <select
                                        id="status"
                                        value={data.status}
                                        onChange={(e) => setData('status', e.target.value)}
                                        className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                    >
                                        <option value="">All Status</option>
                                        {statusOptions.map((option) => (
                                            <option key={option.value} value={option.value}>
                                                {option.label}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                            </div>
                        </form>
                        
                        {students.data.length === 0 ? (
                            <div className="flex flex-col items-center justify-center py-12">
                                <User className="h-12 w-12 text-muted-foreground" />
                                <h3 className="mt-2 text-sm font-semibold">No students found</h3>
                                <p className="mt-1 text-sm text-muted-foreground">
                                    Get started by adding your first student.
                                </p>
                                <Button className="mt-4" asChild>
                                    <Link href="/students/create">
                                        <Plus className="h-4 w-4 mr-2" />
                                        Add Student
                                    </Link>
                                </Button>
                            </div>
                        ) : (
                            <div className="rounded-md border">
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>Name</TableHead>
                                            <TableHead>Email</TableHead>
                                            <TableHead>Phone</TableHead>
                                            <TableHead>Branch</TableHead>
                                            <TableHead>Agent</TableHead>
                                            <TableHead>Status</TableHead>
                                            <TableHead className="text-right">Actions</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {students.data.map((student) => (
                                            <TableRow key={student.id}>
                                                <TableCell className="font-medium">
                                                    <div>
                                                        <div className="font-semibold">
                                                            {student.first_name} {student.last_name}
                                                        </div>
                                                        <div className="text-sm text-muted-foreground">
                                                            ID: #{student.id}
                                                        </div>
                                                    </div>
                                                </TableCell>
                                                <TableCell>
                                                    <div className="max-w-[200px] truncate">
                                                        {student.email}
                                                    </div>
                                                </TableCell>
                                                <TableCell>{student.phone ?? '-'}</TableCell>
                                                <TableCell>
                                                    <Badge variant="outline">
                                                        {student.branch.name}
                                                    </Badge>
                                                </TableCell>
                                                <TableCell>
                                                    {student.agent ? (
                                                        <Badge variant="secondary">
                                                            {student.agent.name}
                                                        </Badge>
                                                    ) : (
                                                        <span className="text-muted-foreground">-</span>
                                                    )}
                                                </TableCell>
                                                <TableCell>
                                                    {getStatusBadge(student.status)}
                                                </TableCell>
                                                <TableCell className="text-right">
                                                    <div className="flex justify-end gap-2">
                                                        <Button size="sm" variant="outline" asChild>
                                                            <Link href={`/students/${student.id}`}>
                                                                <Eye className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button size="sm" variant="outline" asChild>
                                                            <Link href={`/students/${student.id}/edit`}>
                                                                <Edit className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button
                                                            size="sm"
                                                            variant="outline"
                                                            onClick={() => handleDelete(student.id, `${student.first_name} ${student.last_name}`)}
                                                        >
                                                            <Trash2 className="h-4 w-4" />
                                                        </Button>
                                                    </div>
                                                </TableCell>
                                            </TableRow>
                                        ))}
                                    </TableBody>
                                </Table>
                            </div>
                        )}
                        
                        {/* Pagination */}
                        {students.data.length > 0 && (
                            <div className="flex items-center justify-between">
                                <div className="text-sm text-muted-foreground">
                                    Showing {students.from} to {students.to} of {students.total} results
                                </div>
                                <div className="flex items-center gap-2">
                                    {students.links.map((link, index) => (
                                        <Button
                                            key={index}
                                            variant={link.active ? 'default' : 'outline'}
                                            size="sm"
                                            disabled={!link.url}
                                            asChild={!!link.url && !link.active}
                                        >
                                            {link.url && !link.active ? (
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
            
            <ConfirmDeleteDialog
                open={deleteOpen}
                onOpenChange={setDeleteOpen}
                onConfirm={onConfirmDelete}
                title="Delete Student"
                description="Are you sure you want to delete this student? This action cannot be undone."
                isDeleting={deleteForm.processing}
            />
        </AppLayout>
    );
}
