import React, { useState } from 'react';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import { Switch } from '@/components/ui/switch';
import { Badge } from '@/components/ui/badge';
import { Spinner } from '@/components/ui/spinner';
import InputError from '@/components/input-error';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { ArrowLeft, Save, XCircle } from 'lucide-react';

interface Props {
    categories: Record<string, string>;
}

export default function DocumentTypeCreate({ categories }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        code: '',
        description: '',
        category: 'other',
        is_required: false,
        has_expiry: false,
        allowed_file_types: [],
        max_file_size: 5120,
        is_active: true,
        sort_order: 0,
    });

    const availableFileTypes = [
        { value: 'pdf', label: 'PDF', icon: '📄' },
        { value: 'doc', label: 'Word', icon: '📝' },
        { value: 'docx', label: 'Word', icon: '📝' },
        { value: 'jpg', label: 'JPEG', icon: '🖼️' },
        { value: 'jpeg', label: 'JPEG', icon: '🖼️' },
        { value: 'png', label: 'PNG', icon: '🖼️' },
        { value: 'txt', label: 'Text', icon: '📄' },
    ];

    const handleFileTypeToggle = (fileType: string) => {
        setData('allowed_file_types', 
            data.allowed_file_types.includes(fileType)
                ? data.allowed_file_types.filter(type => type !== fileType)
                : [...data.allowed_file_types, fileType]
        );
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/document-types');
    };

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Document Types', href: '/document-types' },
        { title: 'Create', href: '#' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Document Type" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <CardTitle className="text-xl font-semibold">
                                Create Document Type
                            </CardTitle>
                            <Button asChild>
                                <Link href="/document-types">
                                    <ArrowLeft className="h-4 w-4 mr-2" />
                                    Back
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div className="grid gap-6 md:grid-cols-2">
                                <div className="space-y-2">
                                    <Label htmlFor="name">Name</Label>
                                    <Input
                                        id="name"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                        placeholder="Enter document type name"
                                        required
                                    />
                                    <InputError message={errors.name} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="code">Code</Label>
                                    <Input
                                        id="code"
                                        value={data.code}
                                        onChange={(e) => setData('code', e.target.value)}
                                        placeholder="Enter unique code"
                                        required
                                    />
                                    <InputError message={errors.code} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="category">Category</Label>
                                    <select
                                        id="category"
                                        value={data.category}
                                        onChange={(e) => setData('category', e.target.value)}
                                        className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                        required
                                    >
                                        {Object.entries(categories).map(([value, label]) => (
                                            <option key={value} value={value}>
                                                {label}
                                            </option>
                                        ))}
                                    </select>
                                    <InputError message={errors.category} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="max_file_size">Max File Size (KB)</Label>
                                    <Input
                                        id="max_file_size"
                                        type="number"
                                        min="1"
                                        max="10240"
                                        value={data.max_file_size}
                                        onChange={(e) => setData('max_file_size', parseInt(e.target.value))}
                                        placeholder="Enter max file size in KB"
                                        required
                                    />
                                    <InputError message={errors.max_file_size} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="sort_order">Sort Order</Label>
                                    <Input
                                        id="sort_order"
                                        type="number"
                                        min="0"
                                        value={data.sort_order}
                                        onChange={(e) => setData('sort_order', parseInt(e.target.value))}
                                        placeholder="Enter sort order"
                                        required
                                    />
                                    <InputError message={errors.sort_order} />
                                </div>

                                <div className="space-y-2 md:col-span-2">
                                    <Label htmlFor="description">Description</Label>
                                    <Textarea
                                        id="description"
                                        value={data.description}
                                        onChange={(e) => setData('description', e.target.value)}
                                        placeholder="Enter document type description"
                                        rows={3}
                                    />
                                    <InputError message={errors.description} />
                                </div>

                                <div className="space-y-2 md:col-span-2">
                                    <Label>Allowed File Types</Label>
                                    <div className="flex flex-wrap gap-2">
                                        {availableFileTypes.map((fileType) => (
                                            <div key={fileType.value} className="flex items-center space-x-2">
                                                <Checkbox
                                                    id={`file-type-${fileType.value}`}
                                                    checked={data.allowed_file_types.includes(fileType.value)}
                                                    onCheckedChange={() => handleFileTypeToggle(fileType.value)}
                                                />
                                                <Label
                                                    htmlFor={`file-type-${fileType.value}`}
                                                    className="flex items-center gap-1 cursor-pointer"
                                                >
                                                    <span>{fileType.icon}</span>
                                                    <span>{fileType.label}</span>
                                                </Label>
                                            </div>
                                        ))}
                                    </div>
                                    <InputError message={errors.allowed_file_types} />
                                </div>

                                <div className="space-y-2 md:col-span-2">
                                    <div className="grid gap-4 md:grid-cols-3">
                                        <div className="space-y-2">
                                            <div className="flex items-center space-x-2">
                                                <Switch
                                                    id="is_required"
                                                    checked={data.is_required}
                                                    onCheckedChange={(checked) => setData('is_required', checked)}
                                                />
                                                <Label htmlFor="is_required">Required</Label>
                                            </div>
                                            <InputError message={errors.is_required} />
                                        </div>

                                        <div className="space-y-2">
                                            <div className="flex items-center space-x-2">
                                                <Switch
                                                    id="has_expiry"
                                                    checked={data.has_expiry}
                                                    onCheckedChange={(checked) => setData('has_expiry', checked)}
                                                />
                                                <Label htmlFor="has_expiry">Has Expiry</Label>
                                            </div>
                                            <InputError message={errors.has_expiry} />
                                        </div>

                                        <div className="space-y-2">
                                            <div className="flex items-center space-x-2">
                                                <Switch
                                                    id="is_active"
                                                    checked={data.is_active}
                                                    onCheckedChange={(checked) => setData('is_active', checked)}
                                                />
                                                <Label htmlFor="is_active">Active</Label>
                                            </div>
                                            <InputError message={errors.is_active} />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="flex justify-end gap-2">
                                <Button variant="outline" type="button" asChild>
                                    <Link href="/document-types">
                                        <XCircle className="h-4 w-4 mr-2" />
                                        Cancel
                                    </Link>
                                </Button>
                                <Button type="submit" disabled={processing}>
                                    {processing ? (
                                        <>
                                            <Spinner className="mr-2 h-4 w-4" />
                                            Creating...
                                        </>
                                    ) : (
                                        <>
                                            <Save className="mr-2 h-4 w-4" />
                                            Create Document Type
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
