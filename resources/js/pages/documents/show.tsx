import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, useForm, router } from '@inertiajs/react';
import { Pencil, ArrowLeft, Download, Eye, FileText, Calendar, User, CheckCircle, XCircle, Clock, AlertTriangle, History, Shield, Send } from 'lucide-react';
import { useState } from 'react';
import { useToast } from '@/hooks/use-toast';
import InputError from '@/components/input-error';

type Document = {
    id: number;
    title: string;
    description: string;
    file_name: string;
    file_type: string;
    file_size: number;
    file_path: string;
    file_url: string;
    status: string;
    rejection_reason?: string;
    expiry_date?: string;
    is_required: boolean;
    is_public: boolean;
    created_at: string;
    updated_at: string;
    verified_at?: string;
    student: {
        id: number;
        first_name: string;
        last_name: string;
        email: string;
    };
    documentType: {
        id: number;
        name: string;
        category: string;
        description: string;
    };
    verifiedBy?: {
        id: number;
        name: string;
    };
    verifications: Array<{
        id: number;
        status: string;
        notes?: string;
        rejection_reason?: string;
        verification_checklist?: Array<{ item: string; checked: boolean }>;
        verified_at: string;
        verifiedBy: {
            id: number;
            name: string;
        };
    }>;
};

export default function DocumentShow({ document }: { document: Document }) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Documents',
            href: '/documents',
        },
        {
            title: document.title,
            href: `/documents/${document.id}`,
        },
    ];

    const [verificationFormOpen, setVerificationFormOpen] = useState(false);
    const { toast } = useToast();
    
    const verificationForm = useForm({
        status: '',
        notes: '',
        rejection_reason: '',
        verification_checklist: [] as Array<{ item: string; checked: boolean }>,
    });

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

    const getFileIcon = (fileType: string) => {
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

    const handleVerification = (e: React.FormEvent) => {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('status', verificationForm.data.status);
        formData.append('notes', verificationForm.data.notes);
        formData.append('verification_checklist', JSON.stringify(verificationForm.data.verification_checklist));
        
        if (verificationForm.data.status === 'rejected') {
            formData.append('rejection_reason', verificationForm.data.rejection_reason);
        }

        router.post(`/documents/${document.id}/verify`, formData, {
            onSuccess: () => {
                setVerificationFormOpen(false);
                toast({
                    title: "Success",
                    description: "Document verification status updated.",
                });
                router.reload();
            },
            onError: () => {
                toast({
                    title: "Error",
                    description: "Failed to update verification status.",
                    variant: "destructive",
                });
            }
        });
    };

    const downloadDocument = () => {
        window.open(`/documents/${document.id}/download`, '_blank');
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={document.title} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="View Document" />
                            <div className="flex gap-2">
                                <Button variant="outline" onClick={() => setVerificationFormOpen(true)}>
                                    <Shield className="h-4 w-4 mr-2" />
                                    Verify
                                </Button>
                                <Button variant="outline" onClick={downloadDocument}>
                                    <Download className="h-4 w-4 mr-2" />
                                    Download
                                </Button>
                                <Button variant="outline" asChild>
                                    <Link href={`/documents/${document.id}/edit`}>
                                        <Pencil className="h-4 w-4 mr-2" />
                                        Edit
                                    </Link>
                                </Button>
                                <Button asChild>
                                    <Link href="/documents">
                                        <ArrowLeft className="h-4 w-4 mr-2" />
                                        Back
                                    </Link>
                                </Button>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent className="pt-6">
                        <div className="grid gap-6 md:grid-cols-2">
                            <div className="space-y-6">
                                <div className="flex justify-center">
                                    <div className="text-6xl">
                                        {getFileIcon(document.file_type)}
                                    </div>
                                </div>
                                
                                <div className="text-center">
                                    <h2 className="text-2xl font-bold">{document.title}</h2>
                                    <Badge variant={getStatusBadge(document.status, document.expiry_date).variant} className="mt-2">
                                        {getStatusBadge(document.status, document.expiry_date).label}
                                    </Badge>
                                    {isExpiringSoon(document.expiry_date) && (
                                        <div className="mt-2 text-sm text-yellow-600">
                                            <AlertTriangle className="inline h-4 w-4 mr-1" />
                                            Expiring soon
                                        </div>
                                    )}
                                </div>

                                <div className="text-center text-sm text-muted-foreground">
                                    {document.file_name}
                                </div>
                            </div>

                            <div className="space-y-6">
                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Document Information</h3>
                                    <div className="space-y-3">
                                        <div className="flex justify-between">
                                            <span className="text-sm font-medium">Type:</span>
                                            <Badge variant="outline">{document.documentType.name}</Badge>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-sm font-medium">Category:</span>
                                            <Badge variant={getCategoryBadge(document.documentType.category).variant}>
                                                {getCategoryBadge(document.documentType.category).label}
                                            </Badge>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-sm font-medium">Size:</span>
                                            <span className="text-sm text-muted-foreground">{formatFileSize(document.file_size)}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-sm font-medium">Uploaded:</span>
                                            <span className="text-sm text-muted-foreground">
                                                {new Date(document.created_at).toLocaleDateString()}
                                            </span>
                                        </div>
                                        {document.expiry_date && (
                                            <div className="flex justify-between">
                                                <span className="text-sm font-medium">Expires:</span>
                                                <span className="text-sm text-muted-foreground">
                                                    {new Date(document.expiry_date).toLocaleDateString()}
                                                </span>
                                            </div>
                                        )}
                                        {document.verified_at && (
                                            <div className="flex justify-between">
                                                <span className="text-sm font-medium">Verified:</span>
                                                <span className="text-sm text-muted-foreground">
                                                    {new Date(document.verified_at).toLocaleDateString()}
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                </div>

                                {document.description && (
                                    <div>
                                        <h3 className="text-lg font-semibold mb-2">Description</h3>
                                        <p className="text-sm text-muted-foreground">{document.description}</p>
                                    </div>
                                )}

                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Student Information</h3>
                                    <div className="space-y-3">
                                        <div className="flex items-center gap-3">
                                            <div className="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <User className="h-4 w-4 text-blue-600" />
                                            </div>
                                            <div>
                                                <p className="font-medium">
                                                    {document.student.first_name} {document.student.last_name}
                                                </p>
                                                <p className="text-sm text-muted-foreground">{document.student.email}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Settings</h3>
                                    <div className="space-y-3">
                                        <div className="flex items-center gap-2">
                                            <span className="text-sm font-medium">Required:</span>
                                            <Badge variant={document.is_required ? 'default' : 'secondary'}>
                                                {document.is_required ? 'Yes' : 'No'}
                                            </Badge>
                                        </div>
                                        <div className="flex items-center gap-2">
                                            <span className="text-sm font-medium">Public:</span>
                                            <Badge variant={document.is_public ? 'default' : 'secondary'}>
                                                {document.is_public ? 'Yes' : 'No'}
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {document.rejection_reason && (
                            <Alert variant="destructive">
                                <XCircle className="h-4 w-4" />
                                <AlertDescription>
                                    <strong>Rejection Reason:</strong> {document.rejection_reason}
                                </AlertDescription>
                            </Alert>
                        )}

                        <Separator />

                        <div className="space-y-6">
                            <div>
                                <h3 className="text-lg font-semibold mb-4">Verification History</h3>
                                <div className="space-y-4">
                                    {document.verifications.length === 0 ? (
                                        <div className="text-center py-8 text-muted-foreground">
                                            <History className="h-12 w-12 mx-auto" />
                                            <p className="mt-2">No verification history available</p>
                                        </div>
                                    ) : (
                                        document.verifications.map((verification, index) => (
                                            <Card key={verification.id} className="border-l-4 border-l-gray-200">
                                                <CardContent className="pt-6">
                                                    <div className="flex items-center justify-between mb-4">
                                                        <div className="flex items-center gap-2">
                                                            <Badge variant={getStatusBadge(verification.status).variant}>
                                                                {getStatusBadge(verification.status).label}
                                                            </Badge>
                                                            <span className="text-sm text-muted-foreground">
                                                                {new Date(verification.verified_at).toLocaleDateString()}
                                                            </span>
                                                        </div>
                                                        <div className="text-sm text-muted-foreground">
                                                            by {verification.verifiedBy.name}
                                                        </div>
                                                    </div>
                                                    
                                                    {verification.notes && (
                                                        <div className="mb-4">
                                                            <p className="text-sm text-muted-foreground">
                                                                <strong>Notes:</strong> {verification.notes}
                                                            </p>
                                                        </div>
                                                    )}
                                                    
                                                    {verification.rejection_reason && (
                                                        <Alert variant="destructive">
                                                            <XCircle className="h-4 w-4" />
                                                            <AlertDescription>
                                                                <strong>Reason:</strong> {verification.rejection_reason}
                                                            </AlertDescription>
                                                        </Alert>
                                                    )}
                                                    
                                                    {verification.verification_checklist && (
                                                        <div className="space-y-2">
                                                            <p className="text-sm font-medium">Verification Checklist:</p>
                                                            <div className="space-y-1">
                                                                {verification.verification_checklist.map((item, idx) => (
                                                                    <div key={idx} className="flex items-center gap-2">
                                                                        <input
                                                                            type="checkbox"
                                                                            checked={item.checked}
                                                                            readOnly
                                                                            className="rounded"
                                                                        />
                                                                        <span className="text-sm">{item.item}</span>
                                                                    </div>
                                                                ))}
                                                            </div>
                                                        </div>
                                                    )}
                                                </CardContent>
                                            </Card>
                                        ))
                                    )}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Verification Form Modal */}
                {verificationFormOpen && (
                    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                        <Card className="w-full max-w-md">
                            <CardHeader>
                                <CardTitle>Verify Document</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <form onSubmit={handleVerification} className="space-y-4">
                                    <div>
                                        <Label htmlFor="status">Verification Status</Label>
                                        <Select value={verificationForm.data.status} onValueChange={(value) => verificationForm.setData('status', value)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select status" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="approved">Approve</SelectItem>
                                                <SelectItem value="rejected">Reject</SelectItem>
                                                <SelectItem value="needs_revision">Needs Revision</SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <InputError message={verificationForm.errors.status} />
                                    </div>

                                    {verificationForm.data.status === 'rejected' && (
                                        <div>
                                            <Label htmlFor="rejection_reason">Rejection Reason *</Label>
                                            <Textarea
                                                id="rejection_reason"
                                                value={verificationForm.data.rejection_reason}
                                                onChange={(e) => verificationForm.setData('rejection_reason', e.target.value)}
                                                placeholder="Please provide a reason for rejection"
                                                rows={3}
                                                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            />
                                            <InputError message={verificationForm.errors.rejection_reason} />
                                        </div>
                                    )}

                                    <div>
                                        <Label htmlFor="notes">Notes</Label>
                                        <Textarea
                                            id="notes"
                                            value={verificationForm.data.notes}
                                            onChange={(e) => verificationForm.setData('notes', e.target.value)}
                                            placeholder="Add any additional notes (optional)"
                                            rows={3}
                                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        />
                                        <InputError message={verificationForm.errors.notes} />
                                    </div>

                                    <div className="flex justify-end gap-2">
                                        <Button
                                            type="button"
                                            variant="outline"
                                            onClick={() => setVerificationFormOpen(false)}
                                        >
                                            Cancel
                                        </Button>
                                        <Button type="submit" disabled={verificationForm.processing}>
                                            {verificationForm.processing ? 'Processing...' : 'Submit Verification'}
                                        </Button>
                                    </div>
                                </form>
                            </CardContent>
                        </Card>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
