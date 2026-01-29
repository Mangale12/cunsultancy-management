import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { Save, ArrowLeft, XCircle, Calendar, User, FileText, AlertTriangle } from 'lucide-react';
import type { FormEvent } from 'react';
import { useState } from 'react';
import DocumentUpload from '@/components/document-upload';

type Document = {
    id: number;
    title: string;
    description: string;
    file_name: string;
    file_type: string;
    file_size: number;
    file_path: string;
    status: string;
    rejection_reason?: string;
    expiry_date?: string;
    is_required: boolean;
    is_public: boolean;
    created_at: string;
    updated_at: string;
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
        allowed_file_types: string[];
        max_file_size: number;
        is_required: boolean;
    };
};

type Student = {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
};

type DocumentType = {
    id: number;
    name: string;
    category: string;
    description: string;
    allowed_file_types: string[];
    max_file_size: number;
    is_required: boolean;
};

export default function DocumentEdit({ document, documentTypes, students }: {
    document: Document;
    documentTypes: DocumentType[];
    students: Student[];
}) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Documents',
            href: '/documents',
        },
        {
            title: document.title,
            href: `/documents/${document.id}`,
        },
        {
            title: 'Edit',
            href: `/documents/${document.id}/edit`,
        },
    ];

    const form = useForm({
        student_id: document.student?.id?.toString() || '',
        document_type_id: document.documentType?.id?.toString() || '',
        title: document.title,
        description: document.description || '',
        expiry_date: document.expiry_date || '',
        is_required: document.is_required,
        is_public: document.is_public,
    });

    const [selectedDocumentType, setSelectedDocumentType] = useState<DocumentType | null>(null);
    const [uploadError, setUploadError] = useState('');
    const [selectedFile, setSelectedFile] = useState<File | null>(null);

    const submit = (e: FormEvent) => {
        e.preventDefault();
        
        // Update student_id to empty string if it's "select" before submitting
        if (form.data.student_id === 'select') {
            form.setData('student_id', '');
        }
        
        // Update document_type_id to empty string if it's "select" before submitting
        if (form.data.document_type_id === 'select') {
            form.setData('document_type_id', '');
        }

        const formData = new FormData();
        formData.append('student_id', form.data.student_id);
        formData.append('document_type_id', form.data.document_type_id);
        formData.append('title', form.data.title);
        formData.append('description', form.data.description);
        formData.append('expiry_date', form.data.expiry_date);
        formData.append('is_required', form.data.is_required ? '1' : '0');
        formData.append('is_public', form.data.is_public ? '1' : '0');
        
        if (selectedFile) {
            formData.append('file', selectedFile);
        }

        form.put(`/documents/${document.id}`, {
            forceFormData: true,
            onError: (errors) => {
                if (errors.file) {
                    setUploadError(errors.file);
                }
            },
        });
    };

    const handleDocumentTypeChange = (value: string) => {
        form.setData('document_type_id', value);
        const docType = documentTypes.find(dt => dt.id.toString() === value);
        setSelectedDocumentType(docType || null);
        setUploadError('');
    };

    const handleFileSelect = (file: File) => {
        setSelectedFile(file);
        setUploadError('');
    };

    const handleFileRemove = () => {
        setSelectedFile(null);
        setUploadError('');
    };

    const getFileTypeString = () => {
        if (!selectedDocumentType) return '.pdf,.jpg,.jpeg,.png,.doc,.docx';
        return '.' + selectedDocumentType.allowed_file_types.join(',.');
    };

    const getMaxFileSize = () => {
        if (!selectedDocumentType) return 10; // 10MB default
        return selectedDocumentType.max_file_size / 1024; // Convert KB to MB
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit ${document.title}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Edit Document" />
                            <Button asChild>
                                <Link href="/documents">
                                    <ArrowLeft className="h-4 w-4 mr-2" />
                                    Back
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={submit} className="space-y-6">
                            {/* Student Selection */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">Student Information</h3>
                                <div className="grid gap-6 md:grid-cols-2">
                                    <div className="space-y-2">
                                        <Label htmlFor="student_id">Student *</Label>
                                        <Select value={form.data.student_id} onValueChange={(value) => form.setData('student_id', value)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a student" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="select">Select a student</SelectItem>
                                                {students.map((student) => (
                                                    <SelectItem key={student.id} value={student.id.toString()}>
                                                        {student.first_name} {student.last_name} ({student.email})
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={form.errors.student_id} />
                                    </div>
                                </div>
                            </div>

                            {/* Document Type Selection */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">Document Type</h3>
                                <div className="grid gap-6 md:grid-cols-2">
                                    <div className="space-y-2">
                                        <Label htmlFor="document_type_id">Document Type *</Label>
                                        <Select value={form.data.document_type_id} onValueChange={handleDocumentTypeChange}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select document type" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="select">Select a document type</SelectItem>
                                                {documentTypes.map((docType) => (
                                                    <SelectItem key={docType.id} value={docType.id.toString()}>
                                                        <div className="flex items-center gap-2">
                                                            <span>{docType.name}</span>
                                                            {docType.is_required && (
                                                                <span className="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">
                                                                    Required
                                                                </span>
                                                            )}
                                                        </div>
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={form.errors.document_type_id} />
                                    </div>

                                    {selectedDocumentType && (
                                        <div className="space-y-2">
                                            <div className="text-sm text-muted-foreground">
                                                <p><strong>Category:</strong> {selectedDocumentType.category}</p>
                                                <p><strong>Allowed formats:</strong> {selectedDocumentType.allowed_file_types.join(', ').toUpperCase()}</p>
                                                <p><strong>Max size:</strong> {selectedDocumentType.max_file_size / 1024}MB</p>
                                                {selectedDocumentType.is_required && (
                                                    <p className="text-red-600">
                                                        <AlertTriangle className="inline h-4 w-4 mr-1" />
                                                        This document type is required
                                                    </p>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Document Details */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">Document Details</h3>
                                <div className="grid gap-6 md:grid-cols-2">
                                    <div className="space-y-2 md:col-span-2">
                                        <Label htmlFor="title">Title *</Label>
                                        <Input
                                            id="title"
                                            value={form.data.title}
                                            onChange={(e) => form.setData('title', e.target.value)}
                                            required
                                            placeholder="Enter document title"
                                        />
                                        <InputError message={form.errors.title} />
                                    </div>

                                    <div className="space-y-2 md:col-span-2">
                                        <Label htmlFor="description">Description</Label>
                                        <textarea
                                            id="description"
                                            value={form.data.description}
                                            onChange={(e) => form.setData('description', e.target.value)}
                                            placeholder="Enter document description (optional)"
                                            rows={3}
                                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        />
                                        <InputError message={form.errors.description} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="expiry_date">Expiry Date</Label>
                                        <Input
                                            id="expiry_date"
                                            type="date"
                                            value={form.data.expiry_date}
                                            onChange={(e) => form.setData('expiry_date', e.target.value)}
                                            placeholder="Select expiry date (if applicable)"
                                        />
                                        <InputError message={form.errors.expiry_date} />
                                    </div>
                                </div>
                            </div>

                            {/* File Upload */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">File Upload</h3>
                                <div className="space-y-2">
                                    <div className="text-sm text-muted-foreground">
                                        <p><strong>Current file:</strong> {document.file_name}</p>
                                        <p><strong>Size:</strong> {formatFileSize(document.file_size)}</p>
                                    </div>
                                    <DocumentUpload
                                        onFileSelect={handleFileSelect}
                                        onFileRemove={handleFileRemove}
                                        selectedFile={selectedFile}
                                        error={uploadError}
                                        accept={getFileTypeString()}
                                        maxSize={getMaxFileSize()}
                                    />
                                    <InputError message={form.errors.file} />
                                </div>
                            </div>

                            {/* Document Settings */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">Document Settings</h3>
                                <div className="grid gap-6 md:grid-cols-2">
                                    <div className="flex items-center space-x-2">
                                        <Checkbox
                                            id="is_required"
                                            checked={form.data.is_required}
                                            onCheckedChange={(checked) => form.setData('is_required', !!checked)}
                                        />
                                        <Label htmlFor="is_required">Mark as required document</Label>
                                    </div>
                                    <div className="flex items-center space-x-2">
                                        <Checkbox
                                            id="is_public"
                                            checked={form.data.is_public}
                                            onCheckedChange={(checked) => form.setData('is_public', !!checked)}
                                        />
                                        <Label htmlFor="is_public">Make publicly shareable</Label>
                                    </div>
                                </div>
                            </div>

                            <div className="flex justify-end gap-4">
                                <Button type="button" variant="outline" asChild>
                                    <Link href="/documents">
                                        <XCircle className="h-4 w-4 mr-2" />
                                        Cancel
                                    </Link>
                                </Button>
                                <Button type="submit" disabled={form.processing}>
                                    <Save className="h-4 w-4 mr-2" />
                                    {form.processing ? 'Updating...' : 'Update Document'}
                                </Button>
                            </div>

                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}

function formatFileSize(bytes: number) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
