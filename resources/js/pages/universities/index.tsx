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
import { Plus, Search, Trash2, Eye, Pencil, GraduationCap, Globe, MapPin, X, XCircle } from 'lucide-react';
import { useState, useMemo, type FormEvent } from 'react';
import { useToast } from '@/hooks/use-toast';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Universities',
        href: '/universities',
    },
];

type UniversityRow = {
    id: number;
    name: string;
    code: string;
    image_path?: string;
    country?: {
        id: number;
        name: string;
    };
    state?: {
        id: number;
        name: string;
    };
};

type FilterData = {
    search?: string;
    country?: string;
    state?: string;
    per_page: number;
};

export default function UniversitiesIndex({
    universities,
    countries,
    states,
    filters,
}: {
    universities: LaravelPagination<UniversityRow>;
    countries: Array<{ id: number; name: string }>;
    states: Array<{ id: number; name: string; country_id: number }>;
    filters: FilterData;
}) {
    const [deleteOpen, setDeleteOpen] = useState(false);
    const [deleteId, setDeleteId] = useState<number | null>(null);
    const deleteForm = useForm({});
    const [searchTerm, setSearchTerm] = useState(filters.search ?? '');
    const [countryFilter, setCountryFilter] = useState(filters.country ?? 'all');
    const [stateFilter, setStateFilter] = useState(filters.state ?? 'all');
    const [perPage, setPerPage] = useState(filters.per_page?.toString() || '10');
    const { toast } = useToast();
    
    const rows = useMemo(() => {
        return universities.data;
    }, [universities.data]);
    
    // Filter states based on selected country
    const filteredStates = countryFilter === 'all' 
        ? states 
        : states.filter(state => state.country_id.toString() === countryFilter);
    
    const onSearch = (e: FormEvent) => {
        e.preventDefault();
        router.get('/universities', { 
            search: searchTerm,
            country: countryFilter === 'all' ? '' : countryFilter,
            state: stateFilter === 'all' ? '' : stateFilter,
            per_page: perPage,
        }, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    };
    
    const onClear = () => {
        setSearchTerm('');
        setCountryFilter('all');
        setStateFilter('all');
        setPerPage('10');
        router.get('/universities', {}, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    };
    
    const onConfirmDelete = () => {
        if (!deleteId) return;
        deleteForm.delete(`/universities/${deleteId}`, {
            preserveScroll: true,
            onSuccess: () => {
                setDeleteOpen(false);
                setDeleteId(null);
                toast({
                    title: "Success",
                    description: "University deleted successfully.",
                });
            },
            onError: () => {
                toast({
                    title: "Error",
                    description: "Failed to delete university.",
                    variant: "destructive",
                });
            }
        });
    };
    
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Universities" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Universities" />
                            <Button asChild>
                                <Link href="/universities/create">
                                    <Plus className="h-4 w-4 mr-2" />
                                    Create University
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        <form onSubmit={onSearch} className="space-y-4">
                            <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div className="relative">
                                    <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                                    <Input
                                        placeholder="Search universities..."
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                        className="pl-8"
                                    />
                                </div>
                                <Select value={countryFilter} onValueChange={setCountryFilter}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select country" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All Countries</SelectItem>
                                        {countries.map((country) => (
                                            <SelectItem key={country.id} value={country.id.toString()}>
                                                {country.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <Select value={stateFilter} onValueChange={setStateFilter}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select state" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All States</SelectItem>
                                        {filteredStates.map((state) => (
                                            <SelectItem key={state.id} value={state.id.toString()}>
                                                {state.name}
                                            </SelectItem>
                                        ))}
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
                                        <TableHead>Code</TableHead>
                                        <TableHead>Country</TableHead>
                                        <TableHead>State</TableHead>
                                        <TableHead className="text-right">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {rows.length === 0 ? (
                                        <TableRow>
                                            <TableCell colSpan={5}>
                                                <div className="flex flex-col items-center justify-center py-12">
                                                    <GraduationCap className="h-12 w-12 text-muted-foreground" />
                                                    <h3 className="mt-2 text-sm font-semibold">No universities found</h3>
                                                    <p className="mt-1 text-sm text-muted-foreground">
                                                        Get started by creating your first university.
                                                    </p>
                                                    <Button className="mt-4" asChild>
                                                        <Link href="/universities/create">
                                                            <Plus className="h-4 w-4 mr-2" />
                                                            Create University
                                                        </Link>
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ) : (
                                        rows.map((university) => (
                                            <TableRow key={university.id}>
                                                <TableCell>
                                                    <div className="flex items-center gap-3">
                                                        {university.image_path ? (
                                                            <img
                                                                src={university.image_path}
                                                                alt={university.name}
                                                                className="h-8 w-8 rounded object-cover"
                                                            />
                                                        ) : (
                                                            <div className="h-8 w-8 rounded bg-gray-200 flex items-center justify-center">
                                                                <GraduationCap className="h-4 w-4 text-gray-500" />
                                                            </div>
                                                        )}
                                                        <div className="font-medium">{university.name}</div>
                                                    </div>
                                                </TableCell>
                                                <TableCell>
                                                    <Badge variant="outline">{university.code}</Badge>
                                                </TableCell>
                                                <TableCell>
                                                    {university.country ? (
                                                        <Badge variant="secondary">
                                                            <Globe className="mr-1 h-3 w-3" />
                                                            {university.country.name}
                                                        </Badge>
                                                    ) : (
                                                        <span className="text-muted-foreground">-</span>
                                                    )}
                                                </TableCell>
                                                <TableCell>
                                                    {university.state ? (
                                                        <Badge variant="outline">
                                                            <MapPin className="mr-1 h-3 w-3" />
                                                            {university.state.name}
                                                        </Badge>
                                                    ) : (
                                                        <span className="text-muted-foreground">-</span>
                                                    )}
                                                </TableCell>
                                                <TableCell className="text-right">
                                                    <div className="flex justify-end gap-2">
                                                        <Button size="sm" variant="outline" asChild>
                                                            <Link href={`/universities/${university.id}`}>
                                                                <Eye className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button size="sm" variant="outline" asChild>
                                                            <Link href={`/universities/${university.id}/edit`}>
                                                                <Pencil className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button
                                                            size="sm"
                                                            variant="outline"
                                                            onClick={() => {
                                                                setDeleteId(university.id);
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
                        
                        {universities.links && universities.links.length > 3 && (
                            <Pagination links={universities.links} />
                        )}
                    </CardContent>
                </Card>
                
                <ConfirmDeleteDialog
                    open={deleteOpen}
                    onOpenChange={setDeleteOpen}
                    onConfirm={onConfirmDelete}
                    title="Delete University"
                    description="Are you sure you want to delete this university? This action cannot be undone."
                />
            </div>
        </AppLayout>
    );
}
