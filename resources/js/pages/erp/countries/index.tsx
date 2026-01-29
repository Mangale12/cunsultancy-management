import { ConfirmDeleteDialog } from '@/components/confirm-delete-dialog';
import Heading from '@/components/heading';
import { Pagination } from '@/components/pagination';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
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
import { Plus, Search, Trash2, Eye, Pencil, Globe, X, XCircle } from 'lucide-react';
import { useState, useMemo, type FormEvent } from 'react';
import { useToast } from '@/hooks/use-toast';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Countries',
        href: '/countries',
    },
];

type CountryRow = {
    id: number;
    name: string;
};

export default function CountriesIndex({
    countries,
    filters,
}: {
    countries: LaravelPagination<CountryRow>;
    filters: {
        search?: string;
    };
}) {
    const [deleteOpen, setDeleteOpen] = useState(false);
    const [deleteId, setDeleteId] = useState<number | null>(null);
    const deleteForm = useForm({});
    const [searchTerm, setSearchTerm] = useState(filters.search ?? '');
    const { toast } = useToast();
    
    const rows = useMemo(() => {
        return countries.data;
    }, [countries.data]);
    
    const onSearch = (e: FormEvent) => {
        e.preventDefault();
        router.get('/countries', { search: searchTerm }, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    };
    
    const onClear = () => {
        setSearchTerm('');
        router.get('/countries', {}, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    };
    
    const onConfirmDelete = () => {
        if (!deleteId) return;
        deleteForm.delete(`/countries/${deleteId}`, {
            preserveScroll: true,
            onSuccess: () => {
                setDeleteOpen(false);
                setDeleteId(null);
            },
        });
    };
    
    const handleDelete = (id: number) => {
        setDeleteId(id);
        setDeleteOpen(true);
    };
    
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Countries" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Countries" />
                            <Button asChild>
                                <Link href="/countries/create">
                                    <Plus className="h-4 w-4 mr-2" />
                                    Create Country
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
                                        placeholder="Search countries..."
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                        className="pl-8"
                                    />
                                </div>
                                <Button type="submit" variant="outline">
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
                                        <TableHead className="text-right">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {rows.length === 0 ? (
                                        <TableRow>
                                            <TableCell colSpan={2}>
                                                <div className="flex flex-col items-center justify-center py-12">
                                                    <Globe className="h-12 w-12 text-muted-foreground" />
                                                    <h3 className="mt-2 text-sm font-semibold">No countries found</h3>
                                                    <p className="mt-1 text-sm text-muted-foreground">
                                                        Get started by creating your first country.
                                                    </p>
                                                    <Button className="mt-4" asChild>
                                                        <Link href="/countries/create">
                                                            <Plus className="h-4 w-4 mr-2" />
                                                            Create Country
                                                        </Link>
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ) : (
                                        rows.map((country) => (
                                            <TableRow key={country.id}>
                                                <TableCell className="font-medium">
                                                    <div className="flex items-center gap-3">
                                                        <div className="h-8 w-8 rounded-lg bg-muted border flex items-center justify-center">
                                                            <Globe className="h-4 w-4 text-muted-foreground" />
                                                        </div>
                                                        <div>
                                                            <div className="font-semibold">{country.name}</div>
                                                        </div>
                                                    </div>
                                                </TableCell>
                                                <TableCell className="text-right">
                                                    <div className="flex items-center justify-end gap-2">
                                                        <Button variant="ghost" size="sm" asChild>
                                                            <Link href={`/countries/${country.id}`}>
                                                                <Eye className="h-4 w-4" />
                                                                <span className="sr-only">View</span>
                                                            </Link>
                                                        </Button>
                                                        <Button variant="ghost" size="sm" asChild>
                                                            <Link href={`/countries/${country.id}/edit`}>
                                                                <Pencil className="h-4 w-4" />
                                                                <span className="sr-only">Edit</span>
                                                            </Link>
                                                        </Button>
                                                        <Button
                                                            variant="ghost"
                                                            size="sm"
                                                            onClick={() => handleDelete(country.id)}
                                                            className="text-destructive hover:text-destructive"
                                                        >
                                                            <Trash2 className="h-4 w-4" />
                                                            <span className="sr-only">Delete</span>
                                                        </Button>
                                                    </div>
                                                </TableCell>
                                            </TableRow>
                                        ))
                                    )}
                                </TableBody>
                            </Table>
                        </div>
                        
                        <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <div className="text-sm text-muted-foreground">
                                Showing {countries.from ?? 0} to {countries.to ?? 0} of{' '}
                                {countries.total}
                            </div>
                            <Pagination links={countries.links} />
                        </div>
                        
                        <ConfirmDeleteDialog
                            open={deleteOpen}
                            onOpenChange={setDeleteOpen}
                            onConfirm={onConfirmDelete}
                            title="Delete Country"
                            description="Are you sure you want to delete this country? This action cannot be undone."
                        />
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
