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
import { Search, Plus, Edit, Eye, Trash2, Building } from 'lucide-react';
import { useState } from 'react';

interface Branch {
    id: number;
    name: string;
    code: string;
    address: string | null;
    phone: string | null;
    email: string | null;
    manager_name: string | null;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

interface Props {
    branches: {
        data: Branch[];
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
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Branches',
        href: '/branches',
    },
];

export default function BranchesIndex({ branches, filters }: Props) {
    const { data, setData } = useForm({
        search: filters.search || '',
    });
    
    const [deleteOpen, setDeleteOpen] = useState(false);
    const [deleteId, setDeleteId] = useState<number | null>(null);
    const deleteForm = useForm({});
    
    const onSearch = (e: React.FormEvent) => {
        e.preventDefault();
        const params = new URLSearchParams();
        if (data.search) params.append('search', data.search);
        
        window.location.href = `/branches?${params.toString()}`;
    };
    
    const onClear = () => {
        setData('search', '');
    };
    
    const onConfirmDelete = () => {
        if (!deleteId) return;
        deleteForm.delete(`/branches/${deleteId}`, {
            preserveScroll: true,
            onSuccess: () => {
                setDeleteOpen(false);
                setDeleteId(null);
            },
        });
    };
    
    const handleDelete = (branchId: number, branchName: string) => {
        setDeleteId(branchId);
        setDeleteOpen(true);
    };
    
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Branches" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Branches" />
                            <Button asChild>
                                <Link href="/branches/create">
                                    <Plus className="h-4 w-4 mr-2" />
                                    Create Branch
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        <form onSubmit={onSearch} className="flex flex-col gap-3 md:flex-row md:items-center">
                            <div className="flex flex-1 items-center gap-2">
                                <div className="relative flex-1">
                                    <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                                    <Input
                                        placeholder="Search branches..."
                                        value={data.search}
                                        onChange={(e) => setData('search', e.target.value)}
                                        className="pl-8"
                                    />
                                </div>
                                <Button type="submit" variant="outline">
                                    <Search className="h-4 w-4 mr-2" />
                                    Search
                                </Button>
                            </div>
                        </form>
                        
                        {branches.data.length === 0 ? (
                            <div className="flex flex-col items-center justify-center py-12">
                                <Building className="h-12 w-12 text-muted-foreground" />
                                <h3 className="mt-2 text-sm font-semibold">No branches found</h3>
                                <p className="mt-1 text-sm text-muted-foreground">
                                    Get started by creating your first branch.
                                </p>
                                <Button className="mt-4" asChild>
                                    <Link href="/branches/create">
                                        <Plus className="h-4 w-4 mr-2" />
                                        Create Branch
                                    </Link>
                                </Button>
                            </div>
                        ) : (
                            <div className="rounded-md border">
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>Name</TableHead>
                                            <TableHead>Code</TableHead>
                                            <TableHead>Manager</TableHead>
                                            <TableHead>Status</TableHead>
                                            <TableHead className="text-right">Actions</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {branches.data.map((branch) => (
                                            <TableRow key={branch.id}>
                                                <TableCell className="font-medium">
                                                    <div>
                                                        <div className="font-semibold">{branch.name}</div>
                                                        <div className="flex gap-2 mt-1">
                                                            <Badge variant="secondary" className="text-xs">
                                                                {branch.code}
                                                            </Badge>
                                                        </div>
                                                    </div>
                                                </TableCell>
                                                <TableCell>{branch.code}</TableCell>
                                                <TableCell>{branch.manager_name ?? '-'}</TableCell>
                                                <TableCell>
                                                    <Badge variant={branch.is_active ? 'default' : 'secondary'}>
                                                        {branch.is_active ? 'Active' : 'Inactive'}
                                                    </Badge>
                                                </TableCell>
                                                <TableCell className="text-right">
                                                    <div className="flex justify-end gap-2">
                                                        <Button size="sm" variant="outline" asChild>
                                                            <Link href={`/branches/${branch.id}`}>
                                                                <Eye className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button size="sm" variant="outline" asChild>
                                                            <Link href={`/branches/${branch.id}/edit`}>
                                                                <Edit className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button
                                                            size="sm"
                                                            variant="outline"
                                                            onClick={() => handleDelete(branch.id, branch.name)}
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
                        
                        <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <div className="text-sm text-muted-foreground">
                                Showing {branches.from ?? 0} to {branches.to ?? 0} of{' '}
                                {branches.total}
                            </div>
                            {/* Pagination component would go here */}
                        </div>
                    </CardContent>
                </Card>
                
                <ConfirmDeleteDialog
                    open={deleteOpen}
                    onOpenChange={setDeleteOpen}
                    onConfirm={onConfirmDelete}
                    title="Delete Branch"
                    description="Are you sure you want to delete this branch? This action cannot be undone."
                />
            </div>
        </AppLayout>
    );
}
