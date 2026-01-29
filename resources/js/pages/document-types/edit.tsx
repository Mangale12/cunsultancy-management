import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import { Badge } from '@/components/ui/badge';
import { ArrowLeft, Save, Upload } from 'lucide-react';

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
}

interface Props {
    documentType: DocumentType;
    categories: Record<string, string>;
}

export default function DocumentTypeEdit({ documentType, categories }: Props) {
    const breadcrumbs = [
        { title: 'Document Types', href: '/document-types' },
        { title: 'Edit Document Type', href: '#' },
    ];

    const allowedFileTypes = JSON.parse(documentType.allowed_file_types || '[]');

    const { data, setData, put, processing, errors } = useForm({
        name: documentType.name,
        code: documentType.code,
        description: documentType.description || '',
        category: documentType.category,
        is_required: documentType.is_required,
        has_expiry: documentType.has_expiry,
        allowed_file_types: allowedFileTypes,
        max_file_size: documentType.max_file_size,
        is_active: documentType.is_active,
        sort_order: documentType.sort_order,
    });

    const handleFileTypeChange = (fileType: string, checked: boolean) => {
        if (checked) {
            setData('allowed_file_types', [...data.allowed_file_types, fileType]);
        } else {
            setData('allowed_file_types', data.allowed_file_types.filter((type: string) => type !== fileType));
        }
    };

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        put(`/document-types/${documentType.id}`);
    };

    const availableFileTypes = [
        { value: 'pdf', label: 'PDF' },
        { value: 'doc', label: 'DOC' },
        { value: 'docx', label: 'DOCX' },
        { value: 'jpg', label: 'JPG' },
        { value: 'jpeg', label: 'JPEG' },
        { value: 'png', label: 'PNG' },
        { value: 'txt', label: 'TXT' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit ${documentType.name}`} />

            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Edit Document Type" />
                            <Button variant="outline" asChild>
                                <Link href="/document-types">
                                    <ArrowLeft className="h-4 w-4 mr-2" />
                                    Back to Document Types
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={submit} className="space-y-6">
                            {/* Basic Information */}
                            <div>
                                <h3 className="text-lg font-semibold mb-4">Basic Information</h3>
                                <div className="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <Label htmlFor="name">Name</Label>
                                        <Input
                                            id="name"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                            placeholder="Enter document type name"
                                            required
                                        />
                                        {errors.name && <p className="text-sm text-red-600 mt-1">{errors.name}</p>}
                                    </div>
                                    <div>
                                        <Label htmlFor="code">Code</Label>
                                        <Input
                                            id="code"
                                            value={data.code}
                                            onChange={(e) => setData('code', e.target.value)}
                                            placeholder="Enter unique code"
                                            required
                                        />
                                        {errors.code && <p className="text-sm text-red-600 mt-1">{errors.code}</p>}
                                    </div>
                                </div>
                                <div className="mt-4">
                                    <Label htmlFor="category">Category</Label>
                                    <select
                                        id="category"
                                        value={data.category}
                                        onChange={(e) => setData('category', e.target.value)}
                                        className="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    >
                                        <option value="">Select a category</option>
                                        {Object.entries(categories).map(([value, label]) => (
                                            <option key={value} value={value}>{label}</option>
                                        ))}
                                    </select>
                                    {errors.category && <p className="text-sm text-red-600 mt-1">{errors.category}</p>}
                                </div>
                                <div className="mt-4">
                                    <Label htmlFor="description">Description</Label>
                                    <Textarea
                                        id="description"
                                        value={data.description}
                                        onChange={(e) => setData('description', e.target.value)}
                                        placeholder="Enter document type description"
                                        rows={3}
                                    />
                                    {errors.description && <p className="text-sm text-red-600 mt-1">{errors.description}</p>}
                                </div>
                            </div>

                            {/* Requirements */}
                            <div>
                                <h3 className="text-lg font-semibold mb-4">Requirements</h3>
                                <div className="space-y-3">
                                    <div className="flex items-center space-x-2">
                                        <Checkbox
                                            id="is_required"
                                            checked={data.is_required}
                                            onCheckedChange={(checked) => setData('is_required', !!checked)}
                                        />
                                        <Label htmlFor="is_required">Required Document</Label>
                                    </div>
                                    <div className="flex items-center space-x-2">
                                        <Checkbox
                                            id="has_expiry"
                                            checked={data.has_expiry}
                                            onCheckedChange={(checked) => setData('has_expiry', !!checked)}
                                        />
                                        <Label htmlFor="has_expiry">Document Has Expiry Date</Label>
                                    </div>
                                </div>
                            </div>

                            {/* File Settings */}
                            <div>
                                <h3 className="text-lg font-semibold mb-4">File Settings</h3>
                                <div className="space-y-4">
                                    <div>
                                        <Label>Allowed File Types</Label>
                                        <p className="text-sm text-gray-600 mb-2">Select the file types that are allowed for this document type.</p>
                                        <div className="flex flex-wrap gap-3">
                                            {availableFileTypes.map((fileType) => (
                                                <div key={fileType.value} className="flex items-center space-x-2">
                                                    <Checkbox
                                                        id={`file_type_${fileType.value}`}
                                                        checked={data.allowed_file_types.includes(fileType.value)}
                                                        onCheckedChange={(checked) => handleFileTypeChange(fileType.value, !!checked)}
                                                    />
                                                    <Label htmlFor={`file_type_${fileType.value}`} className="text-sm">
                                                        {fileType.label}
                                                    </Label>
                                                </div>
                                            ))}
                                        </div>
                                        {errors.allowed_file_types && <p className="text-sm text-red-600 mt-1">{errors.allowed_file_types}</p>}
                                    </div>
                                    <div className="grid gap-4 md:grid-cols-2">
                                        <div>
                                            <Label htmlFor="max_file_size">Max File Size (KB)</Label>
                                            <Input
                                                id="max_file_size"
                                                type="number"
                                                value={data.max_file_size}
                                                onChange={(e) => setData('max_file_size', parseInt(e.target.value))}
                                                min="1"
                                                max="10240"
                                                required
                                            />
                                            {errors.max_file_size && <p className="text-sm text-red-600 mt-1">{errors.max_file_size}</p>}
                                        </div>
                                        <div>
                                            <Label htmlFor="sort_order">Sort Order</Label>
                                            <Input
                                                id="sort_order"
                                                type="number"
                                                value={data.sort_order}
                                                onChange={(e) => setData('sort_order', parseInt(e.target.value))}
                                                min="0"
                                            />
                                            {errors.sort_order && <p className="text-sm text-red-600 mt-1">{errors.sort_order}</p>}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Status */}
                            <div>
                                <h3 className="text-lg font-semibold mb-4">Status</h3>
                                <div className="flex items-center space-x-2">
                                    <Checkbox
                                        id="is_active"
                                        checked={data.is_active}
                                        onCheckedChange={(checked) => setData('is_active', !!checked)}
                                    />
                                    <Label htmlFor="is_active">Active</Label>
                                </div>
                            </div>

                            {/* Actions */}
                            <div className="flex justify-end gap-2 pt-4 border-t">
                                <Button variant="outline" type="button" asChild>
                                    <Link href="/document-types">
                                        <ArrowLeft className="h-4 w-4 mr-2" />
                                        Cancel
                                    </Link>
                                </Button>
                                <Button type="submit" disabled={processing}>
                                    {processing ? 'Updating...' : (
                                        <>
                                            <Save className="h-4 w-4 mr-2" />
                                            Update Document Type
                                        </>
                                    )}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
