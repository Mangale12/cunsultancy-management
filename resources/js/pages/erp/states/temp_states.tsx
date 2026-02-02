import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
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
import { Search, Plus, Edit, Eye, Trash2, MapPin, XCircle } from 'lucide-react';
import { useState } from 'react';
import { ConfirmDeleteDialog } from '@/components/confirm-delete-dialog';

interface State {
    id: number;
    name: string;
    code: string | null;
    country: {
        id: number;
        name: string;
    };
}

interface PaginatedStates {
    data: State[];
    links: any[];
    from: number;
    to: number;
    total: number;
}

interface Country {
    id: number;
    name: string;
}

interface Props {
    states: PaginatedStates;
    filters: {
        search: string;
        country_id: number;
    };
    countries: Country[];
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'ERP', href: '/erp' },
    { title: 'States', href: '/states' },
];

export default function StatesIndex({ states, filters }: Props) {
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
        
        if (filters.country_id) params.append('country_id', filters.country_id.toString());
        
        window.location.href = `/states?${params.toString()}`;
    };
    
    const onClear = () => {
        setData('search', '');
    };
    
    const onConfirmDelete = () => {
        if (!deleteId) return;
        deleteForm.delete(`/states/${deleteId}`, {
            preserveScroll: true,
            onSuccess: () => {
                setDeleteOpen(false);
                setDeleteId(null);
            },
        });
    };
    
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="States" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="States" />
                            <Button asChild>
                                <Link href="/states/create">
                                    <Plus className="h-4 w-4 mr-2" />
                                    Create State
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
                                        placeholder="Search states..."
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
                                    <XCircle className="h-4 w-4 mr-2" /> Clear
                                </Button>
                            </div>
                        </form>
                        
                        {states.data.length === 0 ? (
                            <div className="flex flex-col items-center justify-center py-12">
                                <MapPin className="h-12 w-12 text-muted-foreground" />
                                <h3 className="mt-2 text-sm font-semibold">No states found</h3>
                                <p className="mt-1 text-sm text-muted-foreground">
                                    Get started by creating your first state.
                                </p>
                                <Button className="mt-4" asChild>
                                    <Link href="/states/create">
                                        <Plus className="h-4 w-4 mr-2" />
                                        Create State
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
                                            <TableHead>Country</TableHead>
                                            <TableHead className="text-right">Actions</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {states.data.map((state) => (
                                            <TableRow key={state.id}>
                                                <TableCell className="font-medium">
                                                    <div>
                                                        <div className="font-semibold">{state.name}</div>
                                                        <div className="flex gap-2 mt-1">
                                                            {state.code && (
                                                                <Badge variant="secondary" className="text-xs">
                                                                    {state.code}
                                                                </Badge>
                                                            )}
                                                            <Badge variant="outline" className="text-xs">
                                                                {state.country.name}
                                                            </Badge>
                                                        </div>
                                                    </div>
                                                </TableCell>
                                                <TableCell>{state.code ?? '-'}</TableCell>
                                                <TableCell>{state.country.name}</TableCell>
                                                <TableCell className="text-right">
                                                    <div className="flex justify-end gap-2">
                                                        <Button size="sm" variant="outline" asChild>
                                                            <Link href={`/states/${state.id}`}>
                                                                <Eye className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button size="sm" variant="outline" asChild>
                                                            <Link href={`/states/${state.id}/edit`}>
                                                                <Edit className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button
                                                            size="sm"
                                                            variant="outline"
                                                            onClick={() => {
                                                                setDeleteId(state.id);
                                                                setDeleteOpen(true);
                                                            }}
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
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
