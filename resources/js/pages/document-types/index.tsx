import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Plus, Search, Edit, Trash2, Eye } from 'lucide-react';

interface DocumentType {
    id: number;
    name: string;
    code: string;
    description?: string;
    category: string;
    is_required: boolean;
    has_expiry: boolean;
    allowed_file_types: string[];
    max_file_size: number;
    is_active: boolean;
    sort_order: number;
    created_at: string;
    updated_at: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedData {
    data: DocumentType[];
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
    links: PaginationLink[];
}

interface Props {
    documentTypes: PaginatedData;
    filters: {
        search?: string;
        category?: string;
    };
    categories: string[];
}

export default function DocumentTypeIndex({ documentTypes, filters, categories }: Props) {
    const [search, setSearch] = React.useState(filters.search || '');
    const [category, setCategory] = React.useState(filters.category || '');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (category) params.append('category', category);
        
        window.location.href = `/document-types?${params.toString()}`;
    };

    const getCategoryBadge = (category: string) => {
        const colors: Record<string, string> = {
            academic: 'bg-blue-100 text-blue-800',
            financial: 'bg-green-100 text-green-800',
            identity: 'bg-purple-100 text-purple-800',
            medical: 'bg-red-100 text-red-800',
            visa: 'bg-yellow-100 text-yellow-800',
            language: 'bg-indigo-100 text-indigo-800',
            experience: 'bg-orange-100 text-orange-800',
            recommendation: 'bg-pink-100 text-pink-800',
            personal: 'bg-gray-100 text-gray-800',
            travel: 'bg-cyan-100 text-cyan-800',
            accommodation: 'bg-teal-100 text-teal-800',
            other: 'bg-slate-100 text-slate-800',
        };
        
        return colors[category] || colors.other;
    };

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Document Types', href: '/document-types' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Document Types" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <CardTitle className="text-xl font-semibold">
                                Document Types
                            </CardTitle>
                            <Button asChild>
                                <Link href="/document-types/create">
                                    <Plus className="h-4 w-4 mr-2" />
                                    Create Document Type
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        {/* Filters */}
                        <form onSubmit={handleSearch} className="mb-6">
                            <div className="grid gap-4 md:grid-cols-3">
                                <div>
                                    <Input
                                        placeholder="Search document types..."
                                        value={search}
                                        onChange={(e) => setSearch(e.target.value)}
                                    />
                                </div>
                                <div>
                                    <select
                                        value={category}
                                        onChange={(e) => setCategory(e.target.value)}
                                        className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                    >
                                        <option value="">All Categories</option>
                                        {categories.map((cat) => (
                                            <option key={cat} value={cat}>
                                                {cat.charAt(0).toUpperCase() + cat.slice(1)}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <div>
                                    <Button type="submit" className="w-full">
                                        <Search className="h-4 w-4 mr-2" />
                                        Search
                                    </Button>
                                </div>
                            </div>
                        </form>

                        {/* Table */}
                        <div className="overflow-x-auto">
                            <table className="w-full border-collapse">
                                <thead>
                                    <tr className="border-b">
                                        <th className="text-left p-3">Name</th>
                                        <th className="text-left p-3">Code</th>
                                        <th className="text-left p-3">Category</th>
                                        <th className="text-left p-3">File Types</th>
                                        <th className="text-left p-3">Max Size</th>
                                        <th className="text-left p-3">Status</th>
                                        <th className="text-left p-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {documentTypes.data.map((docType) => (
                                        <tr key={docType.id} className="border-b hover:bg-gray-50">
                                            <td className="p-3">
                                                <div>
                                                    <div className="font-medium">{docType.name}</div>
                                                    {docType.description && (
                                                        <div className="text-sm text-gray-600">{docType.description}</div>
                                                    )}
                                                </div>
                                            </td>
                                            <td className="p-3">
                                                <code className="bg-gray-100 px-2 py-1 rounded text-sm">
                                                    {docType.code}
                                                </code>
                                            </td>
                                            <td className="p-3">
                                                <Badge className={getCategoryBadge(docType.category)}>
                                                    {docType.category}
                                                </Badge>
                                            </td>
                                            <td className="p-3">
                                                <div className="flex flex-wrap gap-1">
                                                    {JSON.parse(docType.allowed_file_types as any).map((type: string) => (
                                                        <Badge key={type} variant="outline" className="text-xs">
                                                            {type.toUpperCase()}
                                                        </Badge>
                                                    ))}
                                                </div>
                                            </td>
                                            <td className="p-3">
                                                {docType.max_file_size} KB
                                            </td>
                                            <td className="p-3">
                                                <div className="flex gap-2">
                                                    {docType.is_active && (
                                                        <Badge className="bg-green-100 text-green-800">Active</Badge>
                                                    )}
                                                    {docType.is_required && (
                                                        <Badge className="bg-red-100 text-red-800">Required</Badge>
                                                    )}
                                                    {docType.has_expiry && (
                                                        <Badge className="bg-orange-100 text-orange-800">Expiry</Badge>
                                                    )}
                                                </div>
                                            </td>
                                            <td className="p-3">
                                                <div className="flex gap-2">
                                                    <Button size="sm" variant="outline" asChild>
                                                        <Link href={`/document-types/${docType.id}`}>
                                                            <Eye className="h-4 w-4" />
                                                        </Link>
                                                    </Button>
                                                    <Button size="sm" variant="outline" asChild>
                                                        <Link href={`/document-types/${docType.id}/edit`}>
                                                            <Edit className="h-4 w-4" />
                                                        </Link>
                                                    </Button>
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        onClick={() => {
                                                            if (confirm('Are you sure you want to delete this document type?')) {
                                                                router.delete(`/document-types/${docType.id}`);
                                                            }
                                                        }}
                                                    >
                                                        <Trash2 className="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>

                            {documentTypes.data.length === 0 && (
                                <div className="text-center py-8 text-gray-500">
                                    No document types found.
                                </div>
                            )}
                        </div>

                        {/* Pagination */}
                        {documentTypes.last_page > 1 && (
                            <div className="mt-6 flex justify-center">
                                <div className="flex gap-2">
                                    {documentTypes.links.map((link, index) => (
                                        <Button
                                            key={index}
                                            variant={link.active ? 'default' : 'outline'}
                                            size="sm"
                                            disabled={!link.url}
                                            asChild={!!link.url && !link.active}
                                        >
                                            {link.url && !link.active ? (
                                                <Link href={link.url}>
                                                    <span dangerouslySetInnerHTML={{ __html: link.label }} />
                                                </Link>
                                            ) : (
                                                <span dangerouslySetInnerHTML={{ __html: link.label }} />
                                            )}
                                        </Button>
                                    ))}
                                </div>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
