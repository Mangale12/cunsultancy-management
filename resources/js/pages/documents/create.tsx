import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { Textarea } from '@/components/ui/textarea';
import InputError from '@/components/input-error';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, useForm, router } from '@inertiajs/react';
import { ArrowLeft, Calendar, FileText, Shield, User, AlertTriangle, XCircle, Save } from 'lucide-react';
import MultiFileUpload from '@/components/multi-file-upload';
import { useState } from 'react';
import { Badge } from '@/components/ui/badge';
import { Upload, FileSpreadsheet, Presentation } from 'lucide-react';

const getFileIcon = (fileType: string) => {
    switch (fileType.toLowerCase()) {
        case 'pdf':
            return <FileText className="h-4 w-4 text-red-500" />;
        case 'doc':
        case 'docx':
            return <FileText className="h-4 w-4 text-blue-500" />;
        case 'jpg':
        case 'jpeg':
        case 'png':
            return <FileSpreadsheet className="h-4 w-4 text-green-500" />;
        case 'xls':
        case 'xlsx':
            return <FileSpreadsheet className="h-4 w-4 text-green-600" />;
        case 'ppt':
        case 'pptx':
            return <Presentation className="h-4 w-4 text-orange-500" />;
        default:
            return <FileText className="h-4 w-4 text-gray-500" />;
    }
};

const formatFileSize = (sizeInBytes: number) => {
    if (sizeInBytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(sizeInBytes) / Math.log(k));
    return parseFloat((sizeInBytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

type FileItem = {
    id: string;
    file: File;
    name: string;
    size: number;
    type: string;
    description?: string;
    isPrimary?: boolean;
    sortOrder: number;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Documents',
        href: '/documents',
    },
    {
        title: 'Upload',
        href: '/documents/create',
    },
];

type DocumentType = {
    id: number;
    name: string;
    category: string;
    allowed_file_types: string[];
    max_file_size: number;
    is_required: boolean;
    allows_multiple_files: boolean;
    max_files: number;
};

type Student = {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
};

export default function DocumentCreate({
    documentTypes,
    students,
    default_student_id,
}: {
    documentTypes: DocumentType[];
    students: Student[];
    default_student_id?: string;
}) {
    const form = useForm({
        student_id: default_student_id || '',
        document_type_id: '',
        title: '',
        description: '',
        file: null as File | null,
        expiry_date: '',
        is_required: false,
        is_public: false,
    });

    const [selectedFiles, setSelectedFiles] = useState<FileItem[]>([]);
    const [selectedDocumentType, setSelectedDocumentType] = useState<DocumentType | null>(null);
    const [uploadError, setUploadError] = useState('');

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        
        // Update student_id to empty string if it's "select" before submitting
        if (form.data.student_id === 'select') {
            form.setData('student_id', '');
        }
        
        // Update document_type_id to empty string if it's "select" before submitting
        if (form.data.document_type_id === 'select') {
            form.setData('document_type_id', '');
        }

        // Validate files
        if (selectedFiles.length === 0) {
            setUploadError('Please select at least one file');
            return;
        }

        // Create FormData for file upload
        const formData = new FormData();
        formData.append('student_id', form.data.student_id);
        formData.append('document_type_id', form.data.document_type_id);
        formData.append('title', form.data.title);
        formData.append('description', form.data.description);
        formData.append('expiry_date', form.data.expiry_date);
        formData.append('is_required', form.data.is_required ? '1' : '0');
        formData.append('is_public', form.data.is_public ? '1' : '0');
        
        // Add files
        selectedFiles.forEach((file, index) => {
            formData.append(`files[${index}]`, file.file);
            if (file.description) {
                formData.append(`file_descriptions[${index}]`, file.description);
            }
        });
        
        // Add primary file index
        const primaryIndex = selectedFiles.findIndex(f => f.isPrimary);
        if (primaryIndex !== -1) {
            formData.append('primary_file_index', primaryIndex.toString());
        }

        router.post('/documents', {
            student_id: form.data.student_id,
            document_type_id: form.data.document_type_id,
            title: form.data.title,
            description: form.data.description,
            expiry_date: form.data.expiry_date,
            is_required: form.data.is_required ? '1' : '0',
            is_public: form.data.is_public ? '1' : '0',
            files: selectedFiles.map(f => f.file),
            file_descriptions: selectedFiles.map(f => f.description || ''),
            primary_file_index: selectedFiles.findIndex(f => f.isPrimary),
        }, {
            onError: (errors) => {
                if (errors.files) {
                    setUploadError(errors.files);
                }
            },
            onSuccess: () => {
                // Redirect on success
                router.visit('/documents');
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
        form.setData('file', file);
        setUploadError('');
        
        // Auto-generate title if not set
        if (!form.data.title && selectedDocumentType) {
            form.setData('title', `${selectedDocumentType.name} - ${file.name}`);
        }
    };

    const handleFileRemove = () => {
        form.setData('file', null);
        setUploadError('');
    };

    const getFileTypeString = () => {
        if (!selectedDocumentType) return '.pdf,.jpg,.jpeg,.png,.doc,.docx';
        return '.' + (Array.isArray(selectedDocumentType.allowed_file_types) ? selectedDocumentType.allowed_file_types.join(',') : 'pdf,doc,docx,jpg,jpeg,png');
    };

    const getMaxFileSize = () => {
        if (!selectedDocumentType) return 10; // 10MB default
        return selectedDocumentType.max_file_size / 1024; // Convert KB to MB
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Upload Document" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Upload Document" />
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
                                        <div className="space-y-3 p-4 bg-gray-50 rounded-lg border">
                                            <h4 className="font-medium text-sm text-gray-900">File Type Requirements</h4>
                                            <div className="space-y-2">
                                                <div className="flex items-center gap-2">
                                                    <span className="text-sm font-medium text-gray-700">Category:</span>
                                                    <Badge variant="secondary">{selectedDocumentType.category}</Badge>
                                                </div>
                                                
                                                <div className="flex items-center gap-2">
                                                    <span className="text-sm font-medium text-gray-700">Allowed formats:</span>
                                                    <div className="flex flex-wrap gap-1">
                                                        {Array.isArray(selectedDocumentType.allowed_file_types) ? selectedDocumentType.allowed_file_types.map((type: string) => (
                                                            <div key={type} className="flex items-center gap-1 bg-white px-2 py-1 rounded border text-xs">
                                                                {getFileIcon(type)}
                                                                <span className="uppercase">{type}</span>
                                                            </div>
                                                        )) : (
                                                            <div className="text-xs text-gray-500">
                                                                No file types specified
                                                            </div>
                                                        )}
                                                    </div>
                                                </div>
                                                
                                                <div className="flex items-center gap-2">
                                                    <span className="text-sm font-medium text-gray-700">Max file size:</span>
                                                    <Badge variant="outline">{formatFileSize(selectedDocumentType.max_file_size)}</Badge>
                                                </div>
                                                
                                                {selectedDocumentType.allows_multiple_files && (
                                                    <div className="flex items-center gap-2">
                                                        <span className="text-sm font-medium text-gray-700">Max files:</span>
                                                        <Badge variant="outline">{selectedDocumentType.max_files} files</Badge>
                                                    </div>
                                                )}
                                                
                                                {selectedDocumentType.is_required && (
                                                    <div className="flex items-center gap-2 text-red-600">
                                                        <AlertTriangle className="h-4 w-4" />
                                                        <span className="text-sm font-medium">This document type is required</span>
                                                    </div>
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
                                <MultiFileUpload
                                    onFilesChange={setSelectedFiles}
                                    selectedFiles={selectedFiles}
                                    error={uploadError}
                                    accept={selectedDocumentType ? (Array.isArray(selectedDocumentType.allowed_file_types) ? selectedDocumentType.allowed_file_types.join(',') : 'pdf,doc,docx,jpg,jpeg,png') : '.pdf,.doc,.docx,.jpg,.jpeg,.png'}
                                    maxSize={selectedDocumentType ? selectedDocumentType.max_file_size : 10240}
                                    maxFiles={selectedDocumentType ? selectedDocumentType.max_files : 1}
                                    allowMultiple={selectedDocumentType ? selectedDocumentType.allows_multiple_files : false}
                                />
                                <InputError message={form.errors.files} />
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
                                    {form.processing ? 'Uploading...' : 'Upload Document'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
