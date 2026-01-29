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
import { Plus, Search, Trash2, Eye, Pencil, Download, FileText, Calendar, User, AlertTriangle, CheckCircle, Clock, X, XCircle } from 'lucide-react';
import { useState, useMemo, type FormEvent } from 'react';
import { useToast } from '@/hooks/use-toast';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Documents',
        href: '/documents',
    },
];

type DocumentRow = {
    id: number;
    title: string;
    file_name?: string;
    file_type?: string;
    file_size?: number;
    status: string;
    expiry_date?: string;
    is_required: boolean;
    is_public: boolean;
    created_at: string;
    file_count?: number;
    primaryFile?: {
        id: number;
        file_name: string;
        file_type: string;
        file_size: number;
    };
    student?: {
        id: number;
        first_name: string;
        last_name: string;
        email: string;
    };
    documentType?: {
        id: number;
        name: string;
        category: string;
    };
    verifiedBy?: {
        id: number;
        name: string;
    };
};

type FilterData = {
    search?: string;
    status?: string;
    category?: string;
    student_id?: string;
    per_page: number;
};

export default function DocumentsIndex({
    documents,
    documentTypes,
    students,
    filters,
}: {
    documents: LaravelPagination<DocumentRow>;
    documentTypes: Array<{ id: number; name: string; category: string }>;
    students: Array<{ id: number; first_name: string; last_name: string; email: string }>;
    filters: FilterData;
}) {
    const [deleteOpen, setDeleteOpen] = useState(false);
    const [deleteId, setDeleteId] = useState<number | null>(null);
    const deleteForm = useForm({});
    const [searchTerm, setSearchTerm] = useState(filters.search ?? '');
    const [statusFilter, setStatusFilter] = useState(filters.status ?? 'all');
    const [categoryFilter, setCategoryFilter] = useState(filters.category ?? 'all');
    const [studentFilter, setStudentFilter] = useState(filters.student_id ?? 'all');
    const [perPage, setPerPage] = useState(filters.per_page?.toString() || '10');
    const { toast } = useToast();
    
    const rows = useMemo(() => {
        return documents.data;
    }, [documents.data]);
    
    const onSearch = (e: FormEvent) => {
        e.preventDefault();
        router.get('/documents', { 
            search: searchTerm,
            status: statusFilter === 'all' ? '' : statusFilter,
            category: categoryFilter === 'all' ? '' : categoryFilter,
            student_id: studentFilter === 'all' ? '' : studentFilter,
            per_page: perPage,
        }, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    };
    
    const onClear = () => {
        setSearchTerm('');
        setStatusFilter('all');
        setCategoryFilter('all');
        setStudentFilter('all');
        setPerPage('10');
        router.get('/documents', {}, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    };
    
    const onConfirmDelete = () => {
        if (!deleteId) return;
        deleteForm.delete(`/documents/${deleteId}`, {
            preserveScroll: true,
            onSuccess: () => {
                setDeleteOpen(false);
                setDeleteId(null);
                toast({
                    title: "Success",
                    description: "Document deleted successfully.",
                });
            },
            onError: () => {
                toast({
                    title: "Error",
                    description: "Failed to delete document.",
                    variant: "destructive",
                });
            }
        });
    };

    const getStatusBadge = (status: string, expiryDate?: string) => {
        if (status === 'expired' || (expiryDate && new Date(expiryDate) < new Date())) {
            return { label: 'Expired', variant: 'destructive' as const };
        }
        
        const statusConfig = {
            pending: { label: 'Pending', variant: 'secondary' as const },
            verified: { label: 'Verified', variant: 'default' as const },
            rejected: { label: 'Rejected', variant: 'destructive' as const },
            needs_revision: { label: 'Needs Revision', variant: 'outline' as const },
        };
        
        return statusConfig[status as keyof typeof statusConfig] || { label: status, variant: 'secondary' as const };
    };

    const getCategoryBadge = (category: string) => {
        const categoryConfig = {
            academic: { label: 'Academic', variant: 'default' as const },
            financial: { label: 'Financial', variant: 'secondary' as const },
            identification: { label: 'Identification', variant: 'outline' as const },
            visa: { label: 'Visa', variant: 'destructive' as const },
            medical: { label: 'Medical', variant: 'secondary' as const },
            other: { label: 'Other', variant: 'outline' as const },
        };
        
        return categoryConfig[category as keyof typeof categoryConfig] || { label: category, variant: 'secondary' as const };
    };

    const formatFileSize = (bytes: number) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    };

    const getFileIcon = (fileType: string | null) => {
        if (!fileType) return '📎';
        
        switch (fileType.toLowerCase()) {
            case 'pdf':
                return '📄';
            case 'doc':
            case 'docx':
                return '📝';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return '🖼️';
            case 'xls':
            case 'xlsx':
                return '📊';
            case 'ppt':
            case 'pptx':
                return '📽️';
            default:
                return '📎';
        }
    };

    const isExpiringSoon = (expiryDate?: string) => {
        if (!expiryDate) return false;
        const daysUntilExpiry = Math.ceil((new Date(expiryDate).getTime() - new Date().getTime()) / (1000 * 60 * 60 * 24));
        return daysUntilExpiry > 0 && daysUntilExpiry <= 30;
    };
    
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Documents" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Documents" />
                            <Button asChild>
                                <Link href="/documents/create">
                                    <Plus className="h-4 w-4 mr-2" />
                                    Upload Document
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        <form onSubmit={onSearch} className="space-y-4">
                            <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                                <div className="relative">
                                    <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                                    <Input
                                        placeholder="Search documents..."
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                        className="pl-8"
                                    />
                                </div>
                                <Select value={statusFilter} onValueChange={setStatusFilter}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All Statuses</SelectItem>
                                        <SelectItem value="pending">Pending</SelectItem>
                                        <SelectItem value="verified">Verified</SelectItem>
                                        <SelectItem value="rejected">Rejected</SelectItem>
                                        <SelectItem value="needs_revision">Needs Revision</SelectItem>
                                        <SelectItem value="expired">Expired</SelectItem>
                                    </SelectContent>
                                </Select>
                                <Select value={categoryFilter} onValueChange={setCategoryFilter}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select category" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All Categories</SelectItem>
                                        <SelectItem value="academic">Academic</SelectItem>
                                        <SelectItem value="financial">Financial</SelectItem>
                                        <SelectItem value="identification">Identification</SelectItem>
                                        <SelectItem value="visa">Visa</SelectItem>
                                        <SelectItem value="medical">Medical</SelectItem>
                                        <SelectItem value="other">Other</SelectItem>
                                    </SelectContent>
                                </Select>
                                <Select value={studentFilter} onValueChange={setStudentFilter}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select student" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All Students</SelectItem>
                                        {students.map((student) => (
                                            <SelectItem key={student.id} value={student.id.toString()}>
                                                {student.first_name} {student.last_name}
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
                                        <TableHead>Document</TableHead>
                                        <TableHead>Student</TableHead>
                                        <TableHead>Type</TableHead>
                                        <TableHead>Category</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead>Size</TableHead>
                                        <TableHead>Uploaded</TableHead>
                                        <TableHead className="text-right">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {rows.length === 0 ? (
                                        <TableRow>
                                            <TableCell colSpan={8}>
                                                <div className="flex flex-col items-center justify-center py-12">
                                                    <FileText className="h-12 w-12 text-muted-foreground" />
                                                    <h3 className="mt-2 text-sm font-semibold">No documents found</h3>
                                                    <p className="mt-1 text-sm text-muted-foreground">
                                                        Get started by uploading your first document.
                                                    </p>
                                                    <Button className="mt-4" asChild>
                                                        <Link href="/documents/create">
                                                            <Plus className="h-4 w-4 mr-2" />
                                                            Upload Document
                                                        </Link>
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ) : (
                                        rows.map((document) => (
                                            <TableRow key={document.id}>
                                                <TableCell>
                                                    <div className="flex items-center gap-3">
                                                        <span className="text-2xl">{getFileIcon(document.primaryFile?.file_type)}</span>
                                                        <div>
                                                            <div className="font-medium">{document.title}</div>
                                                            <div className="text-sm text-muted-foreground">{document.primaryFile?.file_name || 'No file'}</div>
                                                            {document.file_count && document.file_count > 1 && (
                                                                <div className="text-xs text-blue-600 mt-1">
                                                                    {document.file_count} files
                                                                </div>
                                                            )}
                                                            {document.is_required && (
                                                                <Badge variant="outline" className="mt-1">
                                                                    Required
                                                                </Badge>
                                                            )}
                                                        </div>
                                                    </div>
                                                </TableCell>
                                                <TableCell>
                                                    <div>
                                                        <div className="font-medium">
                                                            {document.student?.first_name || document.student?.['first_name'] || 'Unknown Student'} {document.student?.last_name || document.student?.['last_name'] || ''}
                                                        </div>
                                                        <div className="text-sm text-muted-foreground">{document.student?.email || document.student?.['email'] || 'No email'}</div>
                                                    </div>
                                                </TableCell>
                                                <TableCell>
                                                    <Badge variant="outline">{document.documentType?.name || document.documentType?.['name'] || 'Unknown Type'}</Badge>
                                                </TableCell>
                                                <TableCell>
                                                    <Badge variant={getCategoryBadge(document.documentType?.category || document.documentType?.['category'] || 'other').variant}>
                                                        {getCategoryBadge(document.documentType?.category || document.documentType?.['category'] || 'other').label}
                                                    </Badge>
                                                </TableCell>
                                                <TableCell>
                                                    <div className="flex items-center gap-2">
                                                        <Badge variant={getStatusBadge(document.status, document.expiry_date).variant}>
                                                            {getStatusBadge(document.status, document.expiry_date).label}
                                                        </Badge>
                                                        {isExpiringSoon(document.expiry_date) && (
                                                            <AlertTriangle className="h-4 w-4 text-yellow-500" />
                                                        )}
                                                    </div>
                                                </TableCell>
                                                <TableCell>
                                                    <span className="text-sm text-muted-foreground">
                                                        {document.primaryFile?.file_size ? formatFileSize(document.primaryFile.file_size) : 'No size'}
                                                    </span>
                                                </TableCell>
                                                <TableCell>
                                                    <div className="text-sm text-muted-foreground">
                                                        {new Date(document.created_at).toLocaleDateString()}
                                                    </div>
                                                </TableCell>
                                                <TableCell className="text-right">
                                                    <div className="flex justify-end gap-2">
                                                        <Button size="sm" variant="outline" asChild>
                                                            <Link href={`/documents/${document.id}`}>
                                                                <Eye className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button size="sm" variant="outline" asChild>
                                                            <Link href={`/documents/${document.id}/download`}>
                                                                <Download className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button size="sm" variant="outline" asChild>
                                                            <Link href={`/documents/${document.id}/edit`}>
                                                                <Pencil className="h-4 w-4" />
                                                            </Link>
                                                        </Button>
                                                        <Button
                                                            size="sm"
                                                            variant="outline"
                                                            onClick={() => {
                                                                setDeleteId(document.id);
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
                        
                        {documents.links && documents.links.length > 3 && (
                            <Pagination links={documents.links} />
                        )}
                    </CardContent>
                </Card>
                
                <ConfirmDeleteDialog
                    open={deleteOpen}
                    onOpenChange={setDeleteOpen}
                    onConfirm={onConfirmDelete}
                    title="Delete Document"
                    description="Are you sure you want to delete this document? This action cannot be undone."
                />
            </div>
        </AppLayout>
    );
}
