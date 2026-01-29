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
import { Plus, Search, Trash2, Eye, Pencil, Users, Building, X, XCircle } from 'lucide-react';
import { useState, useMemo, type FormEvent } from 'react';
import { useToast } from '@/hooks/use-toast';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Agents',
        href: '/agents',
    },
];

type AgentRow = {
    id: number;
    name: string;
    email: string;
    phone?: string;
    code: string;
    image_path?: string;
    branch?: {
        id: number;
        name: string;
    };
    parent_agent?: {
        id: number;
        name: string;
    };
};

type FilterData = {
    search?: string;
    branch?: string;
    parent_agent?: string;
    per_page: number;
};

export default function AgentsIndex({
    agents,
    branches,
    parentAgents = [],
    filters,
}: {
    agents: LaravelPagination<AgentRow>;
    branches: Array<{ id: number; name: string }>;
    parentAgents?: Array<{ id: number; name: string }>;
    filters: FilterData;
}) {
    const [deleteOpen, setDeleteOpen] = useState(false);
    const [deleteId, setDeleteId] = useState<number | null>(null);
    const deleteForm = useForm({});
    const [searchTerm, setSearchTerm] = useState(filters.search ?? '');
    const [branchFilter, setBranchFilter] = useState(filters.branch ?? 'all');
    const [parentAgentFilter, setParentAgentFilter] = useState(filters.parent_agent ?? 'all');
    const [perPage, setPerPage] = useState(filters.per_page?.toString() || '10');
    const { toast } = useToast();
    
    const rows = useMemo(() => {
        return agents.data;
    }, [agents.data]);
    
    const onSearch = (e: FormEvent) => {
        e.preventDefault();
        router.get('/agents', { 
            search: searchTerm,
            branch: branchFilter === 'all' ? '' : branchFilter,
            parent_agent: parentAgentFilter === 'all' ? '' : parentAgentFilter,
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
        setParentAgentFilter('all');
        setPerPage('10');
        router.get('/agents', {}, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    };
    
    const onConfirmDelete = () => {
        if (!deleteId) return;
        deleteForm.delete(`/agents/${deleteId}`, {
            preserveScroll: true,
            onSuccess: () => {
                setDeleteOpen(false);
                setDeleteId(null);
                toast({
                    title: "Success",
                    description: "Agent deleted successfully.",
                });
            },
            onError: () => {
                toast({
                    title: "Error",
                    description: "Failed to delete agent.",
                    variant: "destructive",
                });
            }
        });
    };
    
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Agents" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Agents" />
                            <Button asChild>
                                <Link href="/agents/create">
                                    <Plus className="h-4 w-4 mr-2" />
                                    Create Agent
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
                                        placeholder="Search agents..."
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
                                <Select value={parentAgentFilter} onValueChange={setParentAgentFilter}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select parent agent" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All Parent Agents</SelectItem>
                                        {parentAgents?.map((agent) => (
                                            <SelectItem key={agent.id} value={agent.id.toString()}>
                                                {agent.name}
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
                                        <TableHead>Email</TableHead>
                                        <TableHead>Phone</TableHead>
                                        <TableHead>Code</TableHead>
                                        <TableHead>Branch</TableHead>
                                        <TableHead>Parent Agent</TableHead>
                                        <TableHead className="text-right">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {rows.length === 0 ? (
                                        <TableRow>
                                            <TableCell colSpan={7}>
                                                <div className="flex flex-col items-center justify-center py-12">
                                                    <Users className="h-12 w-12 text-muted-foreground" />
                                                    <h3 className="mt-2 text-sm font-semibold">No agents found</h3>
                                                    <p className="mt-1 text-sm text-muted-foreground">
                                                        Get started by creating your first agent.
                                                    </p>
                                                    <Button className="mt-4" asChild>
                                                        <Link href="/agents/create">
                                                            <Plus className="h-4 w-4 mr-2" />
                                                            Create Agent
                                                        </Link>
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ) : (
                                        rows.map((agent) => (
                                            <TableRow key={agent.id}>
                                                <TableCell>
                                                    <div className="flex items-center gap-3">
                                                        {agent.image_path ? (
                                                            <img
                                                                src={agent.image_path}
                                                                alt={agent.name}
                                                                className="h-8 w-8 rounded-full object-cover"
                                                            />
                                                        ) : (
                                                            <div className="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                                <Users className="h-4 w-4 text-gray-500" />
                                                            </div>
                                                        )}
                                                        <div className="font-medium">{agent.name}</div>
                                                    </div>
                                                </TableCell>
                                                <TableCell>
                                                    <a
                                                        href={`mailto:${agent.email}`}
                                                        className="text-blue-600 hover:text-blue-800 underline"
                                                    >
                                                        {agent.email}
                                                    </a>
                                                </TableCell>
                                                <TableCell>
                                                    {agent.phone ? (
                                                        <a
                                                            href={`tel:${agent.phone}`}
                                                            className="text-blue-600 hover:text-blue-800 underline"
                                                        >
                                                            {agent.phone}
                                                        </a>
                                                    ) : (
                                                        <span className="text-muted-foreground">-</span>
                                                    )}
                                                </TableCell>
                                                <TableCell>
                                                    <Badge variant="outline">{agent.code}</Badge>
                                                </TableCell>
                                                <TableCell>
                                                    {agent.branch ? (
                                                        <Badge variant="secondary">
                                                            <Building className="mr-1 h-3 w-3" />
                                                            {agent.branch.name}
                                                        </Badge>
                                                    ) : (
                                                        <span className="text-muted-foreground">-</span>
                                                    )}
                                                </TableCell>
                                                <TableCell>
                                                    {agent.parent_agent ? (
                                                        <Badge variant="outline">
                                                            <Users className="mr-1 h-3 w-3" />
                                                            {agent.parent_agent.name}
                                                        </Badge>
                                                    ) : (
                                                        <span className="text-muted-foreground">-</span>
                                                    )}
                                                </TableCell>
                                                <TableCell className="text-right">
                                                    <div className="flex justify-end gap-2">
                                                        <Button size="sm" variant="outline" asChild>
                                                            <Link href={`/agents/${agent.id}`}>
                                                                <Eye className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button size="sm" variant="outline" asChild>
                                                            <Link href={`/agents/${agent.id}/edit`}>
                                                                <Pencil className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button
                                                            size="sm"
                                                            variant="outline"
                                                            onClick={() => {
                                                                setDeleteId(agent.id);
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
                        
                        {agents.links && agents.links.length > 3 && (
                            <Pagination links={agents.links} />
                        )}
                    </CardContent>
                </Card>
                
                <ConfirmDeleteDialog
                    open={deleteOpen}
                    onOpenChange={setDeleteOpen}
                    onConfirm={onConfirmDelete}
                    title="Delete Agent"
                    description="Are you sure you want to delete this agent? This action cannot be undone."
                />
            </div>
        </AppLayout>
    );
}
