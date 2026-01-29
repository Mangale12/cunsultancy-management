import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ArrowLeft, Edit, FileText, Calendar, CheckCircle, XCircle } from 'lucide-react';

interface Document {
    id: number;
    file_name: string;
    file_path: string;
    file_size: number;
    uploaded_at: string;
    status: string;
}

interface DocumentType {
    id: number;
    name: string;
    code: string;
    description: string | null;
    category: string;
    is_required: boolean;
    has_expiry: boolean;
    allowed_file_types: string;
    max_file_size: number;
    is_active: boolean;
    sort_order: number;
    created_at: string;
    updated_at: string;
    documents: Document[];
}

interface Props {
    documentType: DocumentType;
}

export default function DocumentTypeShow({ documentType }: Props) {
    const breadcrumbs = [
        { title: 'Document Types', href: '/document-types' },
        { title: documentType.name, href: '#' },
    ];

    const allowedFileTypes = JSON.parse(documentType.allowed_file_types || '[]');

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={documentType.name} />

            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title={documentType.name} />
                            <div className="flex gap-2">
                                <Button size="sm" variant="outline" asChild>
                                    <Link href={`/document-types/${documentType.id}/edit`}>
                                        <Edit className="h-4 w-4 mr-2" />
                                        Edit
                                    </Link>
                                </Button>
                                <Button size="sm" variant="outline" asChild>
                                    <Link href="/document-types">
                                        <ArrowLeft className="h-4 w-4 mr-2" />
                                        Back
                                    </Link>
                                </Button>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        {/* Document Type Information */}
                        <div className="grid gap-6 md:grid-cols-2">
                            <div className="space-y-4">
                                <div>
                                    <h3 className="text-lg font-semibold mb-3">Basic Information</h3>
                                    <div className="space-y-3">
                                        <div className="flex justify-between">
                                            <span className="font-medium">Code:</span>
                                            <span className="font-mono">{documentType.code}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="font-medium">Category:</span>
                                            <Badge variant="secondary">
                                                {documentType.category.replace('_', ' ').toUpperCase()}
                                            </Badge>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="font-medium">Status:</span>
                                            <Badge variant={documentType.is_active ? "default" : "secondary"}>
                                                {documentType.is_active ? 'Active' : 'Inactive'}
                                            </Badge>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="font-medium">Sort Order:</span>
                                            <span>{documentType.sort_order}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 className="text-lg font-semibold mb-3">Requirements</h3>
                                    <div className="space-y-3">
                                        <div className="flex justify-between">
                                            <span className="font-medium">Required:</span>
                                            {documentType.is_required ? (
                                                <CheckCircle className="h-5 w-5 text-green-600" />
                                            ) : (
                                                <XCircle className="h-5 w-5 text-red-600" />
                                            )}
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="font-medium">Has Expiry:</span>
                                            {documentType.has_expiry ? (
                                                <CheckCircle className="h-5 w-5 text-green-600" />
                                            ) : (
                                                <XCircle className="h-5 w-5 text-red-600" />
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="space-y-4">
                                <div>
                                    <h3 className="text-lg font-semibold mb-3">File Settings</h3>
                                    <div className="space-y-3">
                                        <div>
                                            <span className="font-medium">Allowed File Types:</span>
                                            <div className="flex flex-wrap gap-1 mt-1">
                                                {allowedFileTypes.map((type: string) => (
                                                    <Badge key={type} variant="outline" className="text-xs">
                                                        {type.toUpperCase()}
                                                    </Badge>
                                                ))}
                                            </div>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="font-medium">Max File Size:</span>
                                            <span>{documentType.max_file_size} KB</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 className="text-lg font-semibold mb-3">Timestamps</h3>
                                    <div className="space-y-3">
                                        <div className="flex justify-between">
                                            <span className="font-medium">Created:</span>
                                            <span className="text-sm text-gray-600">
                                                {new Date(documentType.created_at).toLocaleDateString()}
                                            </span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="font-medium">Updated:</span>
                                            <span className="text-sm text-gray-600">
                                                {new Date(documentType.updated_at).toLocaleDateString()}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {documentType.description && (
                            <div>
                                <h3 className="text-lg font-semibold mb-3">Description</h3>
                                <p className="text-gray-700 bg-gray-50 p-3 rounded-md">
                                    {documentType.description}
                                </p>
                            </div>
                        )}

                        {/* Associated Documents */}
                        <div>
                            <h3 className="text-lg font-semibold mb-3 flex items-center gap-2">
                                <FileText className="h-5 w-5" />
                                Associated Documents ({documentType.documents.length})
                            </h3>
                            {documentType.documents.length > 0 ? (
                                <div className="border rounded-md">
                                    <table className="w-full">
                                        <thead className="bg-gray-50">
                                            <tr>
                                                <th className="px-4 py-2 text-left text-sm font-medium text-gray-900">File Name</th>
                                                <th className="px-4 py-2 text-left text-sm font-medium text-gray-900">Size</th>
                                                <th className="px-4 py-2 text-left text-sm font-medium text-gray-900">Status</th>
                                                <th className="px-4 py-2 text-left text-sm font-medium text-gray-900">Uploaded</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-gray-200">
                                            {documentType.documents.map((doc) => (
                                                <tr key={doc.id}>
                                                    <td className="px-4 py-2 text-sm">{doc.file_name}</td>
                                                    <td className="px-4 py-2 text-sm">{(doc.file_size / 1024).toFixed(1)} KB</td>
                                                    <td className="px-4 py-2 text-sm">
                                                        <Badge variant={doc.status === 'approved' ? 'default' : 'secondary'}>
                                                            {doc.status}
                                                        </Badge>
                                                    </td>
                                                    <td className="px-4 py-2 text-sm text-gray-600">
                                                        {new Date(doc.uploaded_at).toLocaleDateString()}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            ) : (
                                <div className="text-center py-8 text-gray-500 bg-gray-50 rounded-md">
                                    <FileText className="h-12 w-12 mx-auto mb-3 text-gray-400" />
                                    <p>No documents found for this document type.</p>
                                </div>
                            )}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
