import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Plus, ArrowLeft, Eye, Download, FileText, User, Calendar, AlertTriangle, CheckCircle, Clock, XCircle } from 'lucide-react';

type Student = {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone?: string;
    address?: string;
    date_of_birth?: string;
    status: string;
    created_at: string;
};

type Document = {
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
    documentType?: {
        id: number;
        name: string;
        category: string;
        description: string;
    };
    verifiedBy?: {
        id: number;
        name: string;
    };
};

type DocumentType = {
    id: number;
    name: string;
    category: string;
    description: string;
    is_required: boolean;
};

export default function StudentDocuments({
    student,
    documents,
    documentTypes,
}: {
    student: Student;
    documents: Document[];
    documentTypes: DocumentType[];
}) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Students',
            href: '/students',
        },
        {
            title: `${student.first_name} ${student.last_name}`,
            href: `/students/${student.id}`,
        },
        {
            title: 'Documents',
            href: `/students/${student.id}/documents`,
        },
    ];

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
                return '🖼️';
            default:
                return '📎';
        }
    };

    const isExpiringSoon = (expiryDate?: string) => {
        if (!expiryDate) return false;
        const daysUntilExpiry = Math.ceil((new Date(expiryDate).getTime() - new Date().getTime()) / (1000 * 60 * 60 * 24));
        return daysUntilExpiry > 0 && daysUntilExpiry <= 30;
    };

    const getDocumentStats = () => {
        const stats = {
            total: documents.length,
            verified: documents.filter(d => d.status === 'verified').length,
            pending: documents.filter(d => d.status === 'pending').length,
            rejected: documents.filter(d => d.status === 'rejected').length,
            expired: documents.filter(d => d.status === 'expired' || (d.expiry_date && new Date(d.expiry_date) < new Date())).length,
            required: documents.filter(d => d.is_required).length,
        };
        return stats;
    };

    const stats = getDocumentStats();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${student.first_name} ${student.last_name} - Documents`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                {/* Student Info Card */}
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div className="flex items-center gap-4">
                                <div className="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                    <User className="h-6 w-6 text-blue-600" />
                                </div>
                                <div>
                                    <h2 className="text-xl font-bold">
                                        {student.first_name} {student.last_name}
                                    </h2>
                                    <p className="text-sm text-muted-foreground">{student.email}</p>
                                    <Badge variant={student.status === 'active' ? 'default' : 'secondary'}>
                                        {student.status}
                                    </Badge>
                                </div>
                            </div>
                            <div className="flex gap-2">
                                <Button variant="outline" asChild>
                                    <Link href={`/students/${student.id}`}>
                                        <ArrowLeft className="h-4 w-4 mr-2" />
                                        Back to Student
                                    </Link>
                                </Button>
                                <Button asChild>
                                    <Link href={`/documents/create?student_id=${student.id}`}>
                                        <Plus className="h-4 w-4 mr-2" />
                                        Upload Document
                                    </Link>
                                </Button>
                            </div>
                        </div>
                    </CardHeader>
                </Card>

                {/* Statistics Cards */}
                <div className="grid gap-4 md:grid-cols-3 lg:grid-cols-6">
                    <Card>
                        <CardContent className="pt-6">
                            <div className="text-center">
                                <div className="text-2xl font-bold">{stats.total}</div>
                                <p className="text-sm text-muted-foreground">Total Documents</p>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardContent className="pt-6">
                            <div className="text-center">
                                <div className="text-2xl font-bold text-green-600">{stats.verified}</div>
                                <p className="text-sm text-muted-foreground">Verified</p>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardContent className="pt-6">
                            <div className="text-center">
                                <div className="text-2xl font-bold text-yellow-600">{stats.pending}</div>
                                <p className="text-sm text-muted-foreground">Pending</p>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardContent className="pt-6">
                            <div className="text-center">
                                <div className="text-2xl font-bold text-red-600">{stats.rejected}</div>
                                <p className="text-sm text-muted-foreground">Rejected</p>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardContent className="pt-6">
                            <div className="text-center">
                                <div className="text-2xl font-bold text-orange-600">{stats.expired}</div>
                                <p className="text-sm text-muted-foreground">Expired</p>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardContent className="pt-6">
                            <div className="text-center">
                                <div className="text-2xl font-bold text-blue-600">{stats.required}</div>
                                <p className="text-sm text-muted-foreground">Required</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* Documents List */}
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Documents" />
                            <Button asChild>
                                <Link href={`/documents/create?student_id=${student.id}`}>
                                    <Plus className="h-4 w-4 mr-2" />
                                    Upload Document
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        {documents.length === 0 ? (
                            <div className="text-center py-12">
                                <FileText className="h-12 w-12 text-muted-foreground mx-auto" />
                                <h3 className="mt-2 text-sm font-semibold">No documents found</h3>
                                <p className="mt-1 text-sm text-muted-foreground">
                                    This student hasn't uploaded any documents yet.
                                </p>
                                <Button className="mt-4" asChild>
                                    <Link href={`/documents/create?student_id=${student.id}`}>
                                        <Plus className="h-4 w-4 mr-2" />
                                        Upload First Document
                                    </Link>
                                </Button>
                            </div>
                        ) : (
                            <div className="space-y-4">
                                {documents.map((document) => (
                                    <Card key={document.id} className="border-l-4 border-l-gray-200">
                                        <CardContent className="pt-6">
                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center gap-4">
                                                    <div className="text-3xl">
                                                        {getFileIcon(document.primaryFile?.file_type)}
                                                    </div>
                                                    <div className="flex-1">
                                                        <div className="flex items-center gap-2">
                                                            <h4 className="font-medium">{document.title}</h4>
                                                            {document.file_count && document.file_count > 1 && (
                                                                <Badge variant="outline" className="text-xs">
                                                                    {document.file_count} files
                                                                </Badge>
                                                            )}
                                                            {document.is_required && (
                                                                <Badge variant="outline" className="text-xs">
                                                                    Required
                                                                </Badge>
                                                            )}
                                                        </div>
                                                        <div className="flex items-center gap-2 mt-1">
                                                            <Badge variant="outline">{document.documentType?.name || document.documentType?.['name'] || 'Unknown Type'}</Badge>
                                                            <Badge variant={getCategoryBadge(document.documentType?.category || document.documentType?.['category'] || 'other').variant}>
                                                                {getCategoryBadge(document.documentType?.category || document.documentType?.['category'] || 'other').label}
                                                            </Badge>
                                                            <Badge variant={getStatusBadge(document.status, document.expiry_date).variant}>
                                                                {getStatusBadge(document.status, document.expiry_date).label}
                                                            </Badge>
                                                            {isExpiringSoon(document.expiry_date) && (
                                                                <div className="text-yellow-600">
                                                                    <AlertTriangle className="inline h-4 w-4" />
                                                                </div>
                                                            )}
                                                        </div>
                                                        <div className="text-sm text-muted-foreground mt-1">
                                                            {document.file_name} • {formatFileSize(document.file_size)}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="flex items-center gap-2">
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
                                                </div>
                                            </div>
                                            
                                            {document.expiry_date && (
                                                <div className="mt-4 text-sm text-muted-foreground">
                                                    <Calendar className="inline h-4 w-4 mr-1" />
                                                    Expires: {new Date(document.expiry_date).toLocaleDateString()}
                                                </div>
                                            )}
                                            
                                            {document.verifiedBy && (
                                                <div className="mt-2 text-sm text-muted-foreground">
                                                    <CheckCircle className="inline h-4 w-4 mr-1" />
                                                    Verified by {document.verifiedBy.name}
                                                </div>
                                            )}
                                        </CardContent>
                                    </Card>
                                ))}
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
