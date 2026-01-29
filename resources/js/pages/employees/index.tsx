import { ConfirmDeleteDialog } from '@/components/confirm-delete-dialog';
import React, { useState } from 'react';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Search, Edit, Eye, Trash2, Users, Building, Mail, Phone, Calendar } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

interface Branch {
    id: number;
    name: string;
    code: string;
}

interface Employee {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone?: string;
    address?: string;
    hire_date?: string;
    salary?: number;
    position?: string;
    department?: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    branch?: Branch;
    branch_id?: number;
}

interface Props {
    employees: {
        data: Employee[];
        links: any[];
        total: number;
        current_page: number;
        last_page: number;
        per_page: number;
        from: number;
        to: number;
    };
    branches: Branch[];
    filters: {
        search?: string;
        branch?: string;
        per_page: number;
    };
}

export default function EmployeesIndex({ employees, branches, filters }: Props) {
    const [search, setSearch] = useState(filters.search || '');
    const [branchFilter, setBranchFilter] = useState(filters.branch || 'all');
    const [perPage, setPerPage] = useState(filters.per_page?.toString() || '10');

    const handleSearch = () => {
        router.get('/employees', {
            search,
            branch: branchFilter === 'all' ? '' : branchFilter,
            per_page: perPage,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const handleKeyPress = (e: React.KeyboardEvent) => {
        if (e.key === 'Enter') {
            handleSearch();
        }
    };

    const [deleteOpen, setDeleteOpen] = useState(false);
    const [deleteId, setDeleteId] = useState<number | null>(null);
    const deleteForm = useForm({});

    const handleDelete = (id: number, firstName: string, lastName: string) => {
        setDeleteId(id);
        setDeleteOpen(true);
    };

    const onConfirmDelete = () => {
        if (!deleteId) return;
        deleteForm.delete(`/employees/${deleteId}`, {
            preserveScroll: true,
            onSuccess: () => {
                setDeleteOpen(false);
                setDeleteId(null);
            },
            onError: (errors) => {
                console.error('Delete error:', errors);
            }
        });
    };

    return (
        <AppLayout breadcrumbs={[
            { title: 'Employees', href: '/employees' }
        ]}>
            <Head title="Employees" />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">Employees</h1>
                        <p className="text-muted-foreground">
                            Manage staff and personnel information
                        </p>
                    </div>
                    <Link href="/employees/create">
                        <Button>
                            <Users className="mr-2 h-4 w-4" />
                            Add Employee
                        </Button>
                    </Link>
                </div>

                {/* Filters */}
                <Card>
                    <CardHeader>
                        <CardTitle>Filters</CardTitle>
                        <CardDescription>
                            Search and filter employees
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="flex gap-4 items-end">
                            <div className="flex-1">
                                <label className="text-sm font-medium mb-2 block">Search</label>
                                <div className="relative">
                                    <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-4 w-4" />
                                    <Input
                                        placeholder="Search by name, email, or position..."
                                        value={search}
                                        onChange={(e) => setSearch(e.target.value)}
                                        onKeyPress={handleKeyPress}
                                        className="pl-10"
                                    />
                                </div>
                            </div>
                            <div>
                                <label className="text-sm font-medium mb-2 block">Branch</label>
                                <Select value={branchFilter} onValueChange={setBranchFilter}>
                                    <SelectTrigger className="w-48">
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
                            </div>
                            <div>
                                <label className="text-sm font-medium mb-2 block">Per Page</label>
                                <select
                                    value={perPage}
                                    onChange={(e) => setPerPage(e.target.value)}
                                    className="w-20 rounded-md border border-gray-300 px-3 py-2 text-sm"
                                >
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <Button onClick={handleSearch}>
                                <Search className="mr-2 h-4 w-4" />
                                Search
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                {/* Employees Table */}
                <Card>
                    <CardHeader>
                        <CardTitle>Employees ({employees?.total || 0})</CardTitle>
                        <CardDescription>
                            Manage staff and personnel information
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead>Position</TableHead>
                                    <TableHead>Branch</TableHead>
                                    <TableHead>Phone</TableHead>
                                    <TableHead>Hire Date</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {employees?.data?.map((employee) => (
                                    <TableRow key={employee.id}>
                                        <TableCell>
                                            <div>
                                                <div className="font-medium">{employee.first_name} {employee.last_name}</div>
                                                <div className="text-sm text-gray-500">{employee.department || 'No department'}</div>
                                            </div>
                                        </TableCell>
                                        <TableCell>
                                            <a
                                                href={`mailto:${employee.email}`}
                                                className="text-blue-600 hover:text-blue-800 underline"
                                            >
                                                {employee.email}
                                            </a>
                                        </TableCell>
                                        <TableCell>
                                            <Badge variant="outline" className="font-mono text-xs">
                                                {employee.position || 'Not assigned'}
                                            </Badge>
                                        </TableCell>
                                        <TableCell>
                                            {employee.branch ? (
                                                <Badge variant="secondary">
                                                    {employee.branch.name}
                                                </Badge>
                                            ) : (
                                                <span className="text-gray-400">No branch</span>
                                            )}
                                        </TableCell>
                                        <TableCell>
                                            {employee.phone ? (
                                                <a
                                                    href={`tel:${employee.phone}`}
                                                    className="text-blue-600 hover:text-blue-800 underline"
                                                >
                                                    {employee.phone}
                                                </a>
                                            ) : (
                                                <span className="text-gray-400">No phone</span>
                                            )}
                                        </TableCell>
                                        <TableCell>
                                            {employee.hire_date ? (
                                                <div className="flex items-center gap-1">
                                                    <Calendar className="h-3 w-3 text-gray-400" />
                                                    {new Date(employee.hire_date).toLocaleDateString()}
                                                </div>
                                            ) : (
                                                <span className="text-gray-400">No hire date</span>
                                            )}
                                        </TableCell>
                                        <TableCell>
                                            <Badge
                                                variant={employee.is_active ? 'default' : 'secondary'}
                                                className={employee.is_active ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-800 border-gray-200'}
                                            >
                                                {employee.is_active ? 'Active' : 'Inactive'}
                                            </Badge>
                                        </TableCell>
                                        <TableCell>
                                            <div className="flex gap-2">
                                                <Link href={`/employees/${employee.id}`} className="inline-flex">
                                                    <Button size="sm" variant="outline">
                                                        <Eye className="mr-2 h-4 w-4" />
                                                        View
                                                    </Button>
                                                </Link>
                                                <Link href={`/employees/${employee.id}/edit`} className="inline-flex">
                                                    <Button size="sm" variant="outline">
                                                        <Edit className="mr-2 h-4 w-4" />
                                                        Edit
                                                    </Button>
                                                </Link>
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                    onClick={() => handleDelete(employee.id, employee.first_name, employee.last_name)}
                                                    className="text-red-600 hover:text-red-800"
                                                >
                                                    <Trash2 className="h-4 w-4" />
                                                </Button>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>

                        {/* Pagination */}
                        {employees?.links && employees.links.length > 3 && (
                            <div className="flex justify-center mt-6">
                                <div className="flex gap-2">
                                    {employees.links.map((link, index) => (
                                        <Link
                                            key={index}
                                            href={link.url || '#'}
                                            className={`px-3 py-2 rounded-md text-sm ${
                                                link.active
                                                    ? 'bg-blue-600 text-white'
                                                    : link.url
                                                    ? 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                                    : 'bg-gray-50 text-gray-400 cursor-not-allowed'
                                            }`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ))}
                                </div>
                            </div>
                        )}
                    </CardContent>
                </Card>
                
                <ConfirmDeleteDialog
                    open={deleteOpen}
                    onOpenChange={setDeleteOpen}
                    onConfirm={onConfirmDelete}
                    title="Delete Employee"
                    description="Are you sure you want to delete this employee? This action cannot be undone."
                />
            </div>
        </AppLayout>
    );
}
