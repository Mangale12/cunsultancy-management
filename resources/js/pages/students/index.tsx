import { ConfirmDeleteDialog } from '@/components/confirm-delete-dialog';
import Heading from '@/components/heading';
import { Pagination } from '@/components/pagination';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
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
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem, LaravelPagination } from '@/types';
import { Head, Link, useForm, router } from '@inertiajs/react';
import { Plus, Search, Trash2, Eye, Pencil, Users, Building, User, GraduationCap, X, XCircle } from 'lucide-react';
import { useState, useMemo, type FormEvent } from 'react';
import { useToast } from '@/hooks/use-toast';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Students',
        href: '/students',
    },
];

type StudentRow = {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone?: string;
    status: string;
    image_path?: string;
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
};

type FilterData = {
    search?: string;
    branch?: string;
    agent?: string;
    course?: string;
    status?: string;
    per_page: number;
};

export default function StudentsIndex({
    students,
    branches,
    agents,
    courses,
    filters,
}: {
    students: LaravelPagination<StudentRow>;
    branches: Array<{ id: number; name: string }>;
    agents: Array<{ id: number; name: string; branch_id?: number }>;
    courses: Array<{ id: number; name: string }>;
    filters: FilterData;
}) {
    const [deleteOpen, setDeleteOpen] = useState(false);
    const [deleteId, setDeleteId] = useState<number | null>(null);
    const deleteForm = useForm({});
    const [searchTerm, setSearchTerm] = useState(filters.search ?? '');
    const [branchFilter, setBranchFilter] = useState(filters.branch ?? 'all');
    const [agentFilter, setAgentFilter] = useState(filters.agent ?? 'all');
    const [courseFilter, setCourseFilter] = useState(filters.course ?? 'all');
    const [statusFilter, setStatusFilter] = useState(filters.status ?? 'all');
    const [perPage, setPerPage] = useState(filters.per_page?.toString() || '10');
    const { toast } = useToast();
    
    const rows = useMemo(() => {
        return students.data;
    }, [students.data]);
    
    // Filter agents based on selected branch
    const filteredAgents = branchFilter === 'all' 
        ? agents 
        : agents.filter(agent => agent.branch_id?.toString() === branchFilter);
    
    const onSearch = (e: FormEvent) => {
        e.preventDefault();
        router.get('/students', { 
            search: searchTerm,
            branch: branchFilter === 'all' ? '' : branchFilter,
            agent: agentFilter === 'all' ? '' : agentFilter,
            course: courseFilter === 'all' ? '' : courseFilter,
            status: statusFilter === 'all' ? '' : statusFilter,
            per_page: perPage,
        }, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    };
    
    const onClear = () => {
        setSearchTerm('');
        setBranchFilter('all');
        setAgentFilter('all');
        setCourseFilter('all');
        setStatusFilter('all');
        setPerPage('10');
        router.get('/students', {}, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    };
    
    const onConfirmDelete = () => {
        if (!deleteId) return;
        deleteForm.delete(`/students/${deleteId}`, {
            preserveScroll: true,
            onSuccess: () => {
                setDeleteOpen(false);
                setDeleteId(null);
                toast({
                    title: "Success",
                    description: "Student deleted successfully.",
                });
            },
            onError: () => {
                toast({
                    title: "Error",
                    description: "Failed to delete student.",
                    variant: "destructive",
                });
            }
        });
    };

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
            <Head title="Students" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Students" />
                            <Button asChild>
                                <Link href="/students/create">
                                    <Plus className="h-4 w-4 mr-2" />
                                    Create Student
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        <form onSubmit={onSearch} className="space-y-4">
                            <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-6">
                                <div className="relative">
                                    <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                                    <Input
                                        placeholder="Search students..."
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                        className="pl-8"
                                    />
                                </div>
                                <Select value={branchFilter} onValueChange={setBranchFilter}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select branch" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All Branches</SelectItem>
                                        {branches.map((branch) => (
                                            <SelectItem key={branch.id} value={branch.id.toString()}>
                                                {branch.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <Select value={agentFilter} onValueChange={setAgentFilter}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select agent" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All Agents</SelectItem>
                                        {filteredAgents.map((agent) => (
                                            <SelectItem key={agent.id} value={agent.id.toString()}>
                                                {agent.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <Select value={courseFilter} onValueChange={setCourseFilter}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select course" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All Courses</SelectItem>
                                        {courses.map((course) => (
                                            <SelectItem key={course.id} value={course.id.toString()}>
                                                {course.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <Select value={statusFilter} onValueChange={setStatusFilter}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All Statuses</SelectItem>
                                        <SelectItem value="active">Active</SelectItem>
                                        <SelectItem value="inactive">Inactive</SelectItem>
                                        <SelectItem value="graduated">Graduated</SelectItem>
                                        <SelectItem value="suspended">Suspended</SelectItem>
                                    </SelectContent>
                                </Select>
                                <Select value={perPage} onValueChange={setPerPage}>
                                    <SelectTrigger>
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="10">10 per page</SelectItem>
                                        <SelectItem value="25">25 per page</SelectItem>
                                        <SelectItem value="50">50 per page</SelectItem>
                                        <SelectItem value="100">100 per page</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="flex gap-2">
                                <Button type="submit">
                                    <Search className="h-4 w-4 mr-2" />
                                    Search
                                </Button>
                                <Button type="button" variant="outline" onClick={onClear}>
                                    <XCircle className="h-4 w-4 mr-2" /> Clear
                                </Button>
                            </div>
                        </form>
                        
                        <div className="rounded-md border">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Email</TableHead>
                                        <TableHead>Phone</TableHead>
                                        <TableHead>Branch</TableHead>
                                        <TableHead>Agent</TableHead>
                                        <TableHead>Course</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead className="text-right">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {rows.length === 0 ? (
                                        <TableRow>
                                            <TableCell colSpan={8}>
                                                <div className="flex flex-col items-center justify-center py-12">
                                                    <Users className="h-12 w-12 text-muted-foreground" />
                                                    <h3 className="mt-2 text-sm font-semibold">No students found</h3>
                                                    <p className="mt-1 text-sm text-muted-foreground">
                                                        Get started by creating your first student.
                                                    </p>
                                                    <Button className="mt-4" asChild>
                                                        <Link href="/students/create">
                                                            <Plus className="h-4 w-4 mr-2" />
                                                            Create Student
                                                        </Link>
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ) : (
                                        rows.map((student) => (
                                            <TableRow key={student.id}>
                                                <TableCell>
                                                    <div className="flex items-center gap-3">
                                                        {student.image_path ? (
                                                            <img
                                                                src={student.image_path}
                                                                alt={`${student.first_name} ${student.last_name}`}
                                                                className="h-8 w-8 rounded-full object-cover"
                                                            />
                                                        ) : (
                                                            <div className="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                                <User className="h-4 w-4 text-gray-500" />
                                                            </div>
                                                        )}
                                                        <div className="font-medium">
                                                            {student.first_name} {student.last_name}
                                                        </div>
                                                    </div>
                                                </TableCell>
                                                <TableCell>
                                                    <a
                                                        href={`mailto:${student.email}`}
                                                        className="text-blue-600 hover:text-blue-800 underline"
                                                    >
                                                        {student.email}
                                                    </a>
                                                </TableCell>
                                                <TableCell>
                                                    {student.phone ? (
                                                        <a
                                                            href={`tel:${student.phone}`}
                                                            className="text-blue-600 hover:text-blue-800 underline"
                                                        >
                                                            {student.phone}
                                                        </a>
                                                    ) : (
                                                        <span className="text-muted-foreground">-</span>
                                                    )}
                                                </TableCell>
                                                <TableCell>
                                                    {student.branch ? (
                                                        <Badge variant="outline">
                                                            <Building className="mr-1 h-3 w-3" />
                                                            {student.branch.name}
                                                        </Badge>
                                                    ) : (
                                                        <span className="text-muted-foreground">-</span>
                                                    )}
                                                </TableCell>
                                                <TableCell>
                                                    {student.agent ? (
                                                        <Badge variant="outline">
                                                            <User className="mr-1 h-3 w-3" />
                                                            {student.agent.name}
                                                        </Badge>
                                                    ) : (
                                                        <span className="text-muted-foreground">-</span>
                                                    )}
                                                </TableCell>
                                                <TableCell>
                                                    {student.course ? (
                                                        <Badge variant="outline">
                                                            <GraduationCap className="mr-1 h-3 w-3" />
                                                            {student.course.name}
                                                        </Badge>
                                                    ) : (
                                                        <span className="text-muted-foreground">-</span>
                                                    )}
                                                </TableCell>
                                                <TableCell>
                                                    <Badge variant={getStatusBadge(student.status).variant}>
                                                        {getStatusBadge(student.status).label}
                                                    </Badge>
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
                                                                <Pencil className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button
                                                            size="sm"
                                                            variant="outline"
                                                            onClick={() => {
                                                                setDeleteId(student.id);
                                                                setDeleteOpen(true);
                                                            }}
                                                            className="text-red-600 hover:text-red-800"
                                                        >
                                                            <Trash2 className="h-4 w-4" />
                                                        </Button>
                                                    </div>
                                                </TableCell>
                                            </TableRow>
                                        ))
                                    )}
                                </TableBody>
                            </Table>
                        </div>
                        
                        {students.links && students.links.length > 3 && (
                            <Pagination links={students.links} />
                        )}
                    </CardContent>
                </Card>
                
                <ConfirmDeleteDialog
                    open={deleteOpen}
                    onOpenChange={setDeleteOpen}
                    onConfirm={onConfirmDelete}
                    title="Delete Student"
                    description="Are you sure you want to delete this student? This action cannot be undone."
                />
            </div>
        </AppLayout>
    );
}
