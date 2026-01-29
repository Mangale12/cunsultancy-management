import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { ArrowLeft, Save, Shield, Users } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

interface Permission {
    id: number;
    name: string;
}

interface Role {
    id: number;
    name: string;
    guard_name: string;
    permissions: string[];
}

interface Props {
    role: Role;
    permissions: { [key: string]: Permission[] };
    flash?: {
        success?: string;
        error?: string;
    };
}

export default function EditRole({ role, permissions, flash }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Admin',
            href: '/admin/user-roles',
        },
        {
            title: 'User Roles',
            href: '/admin/user-roles',
        },
        {
            title: 'Manage Roles',
            href: '/admin/user-roles/roles',
        },
        {
            title: 'Edit Role',
            href: `/admin/user-roles/roles/${role.id}/edit`,
        },
    ];

    const [selectedPermissions, setSelectedPermissions] = useState<string[]>(role.permissions);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const getRoleBadgeColor = (roleName: string) => {
        const colors: { [key: string]: string } = {
            superadmin: 'bg-red-100 text-red-800 border-red-200',
            branch_admin: 'bg-blue-100 text-blue-800 border-blue-200',
            agent: 'bg-green-100 text-green-800 border-green-200',
            employee: 'bg-yellow-100 text-yellow-800 border-yellow-200',
            student: 'bg-purple-100 text-purple-800 border-purple-200',
        };
        return colors[roleName] || 'bg-gray-100 text-gray-800 border-gray-200';
    };

    const handlePermissionChange = (permissionName: string, checked: boolean) => {
        if (checked) {
            setSelectedPermissions([...selectedPermissions, permissionName]);
        } else {
            setSelectedPermissions(selectedPermissions.filter(p => p !== permissionName));
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);

        router.put(`/admin/roles/${role.id}`, {
            permissions: selectedPermissions,
        }, {
            onSuccess: () => {
                setIsSubmitting(false);
            },
            onError: (errors) => {
                setIsSubmitting(false);
                console.error('Error updating role permissions:', errors);
            },
        });
    };

    const getPermissionCountByCategory = (category: string) => {
        const categoryPermissions = permissions[category] || [];
        return categoryPermissions.filter(p => selectedPermissions.includes(p.name)).length;
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit Role - ${role.name.replace('_', ' ').toUpperCase()}`} />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div className="flex items-center gap-4">
                        <Link href="/admin/roles">
                            <Button variant="outline" size="sm">
                                <ArrowLeft className="mr-2 h-4 w-4" />
                                Back to Roles
                            </Button>
                        </Link>
                        <div>
                            <h1 className="text-3xl font-bold tracking-tight">Edit Role</h1>
                            <p className="text-muted-foreground">
                                Manage permissions for the {role.name.replace('_', ' ').toUpperCase()} role
                            </p>
                        </div>
                    </div>
                </div>

                {/* Role Info */}
                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <Shield className="h-5 w-5" />
                            <Badge
                                variant="outline"
                                className={getRoleBadgeColor(role.name)}
                            >
                                {role.name.replace('_', ' ').toUpperCase()}
                            </Badge>
                        </CardTitle>
                        <CardDescription>
                            Role information and current permissions
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <Label className="text-sm font-medium text-gray-500">Role Name</Label>
                                <p className="font-medium">{role.name.replace('_', ' ').toUpperCase()}</p>
                            </div>
                            <div>
                                <Label className="text-sm font-medium text-gray-500">Guard</Label>
                                <p className="font-medium">{role.guard_name}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Alert */}
                {flash?.success && (
                    <div className="bg-green-50 border border-green-200 rounded-md p-4">
                        <div className="flex">
                            <div className="flex-shrink-0">
                                <svg className="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                </svg>
                            </div>
                            <div className="ml-3">
                                <p className="text-sm text-green-800">{flash.success}</p>
                            </div>
                        </div>
                    </div>
                )}

                <form onSubmit={handleSubmit}>
                    {/* Permissions Selection */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Role Permissions</CardTitle>
                            <CardDescription>
                                Select permissions that should be assigned to this role. Users with this role will inherit all selected permissions.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-6">
                                {Object.entries(permissions).map(([category, categoryPermissions]) => {
                                    const selectedCount = getPermissionCountByCategory(category);
                                    const totalCount = categoryPermissions.length;
                                    
                                    return (
                                        <div key={category}>
                                            <div className="flex items-center justify-between mb-3">
                                                <h4 className="font-medium capitalize flex items-center gap-2">
                                                    <Shield className="h-4 w-4" />
                                                    {category} Permissions
                                                </h4>
                                                <Badge variant="outline">
                                                    {selectedCount} / {totalCount} selected
                                                </Badge>
                                            </div>
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                {categoryPermissions.map((permission) => (
                                                    <div key={permission.id} className="flex items-center space-x-2 p-3 border rounded-lg hover:bg-gray-50">
                                                        <Checkbox
                                                            id={`permission-${permission.name}`}
                                                            checked={selectedPermissions.includes(permission.name)}
                                                            onCheckedChange={(checked) => handlePermissionChange(permission.name, checked as boolean)}
                                                        />
                                                        <Label
                                                            htmlFor={`permission-${permission.name}`}
                                                            className="flex-1 cursor-pointer"
                                                        >
                                                            <div className="text-sm font-medium">
                                                                {permission.name.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
                                                            </div>
                                                        </Label>
                                                    </div>
                                                ))}
                                            </div>
                                            <Separator className="mt-4" />
                                        </div>
                                    );
                                })}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Summary */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Summary</CardTitle>
                            <CardDescription>
                                Overview of selected permissions for this role
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                                {Object.entries(permissions).map(([category, categoryPermissions]) => {
                                    const selectedCount = getPermissionCountByCategory(category);
                                    const totalCount = categoryPermissions.length;
                                    const percentage = totalCount > 0 ? (selectedCount / totalCount) * 100 : 0;
                                    
                                    return (
                                        <div key={category} className="text-center">
                                            <div className="text-2xl font-bold text-blue-600">
                                                {selectedCount}/{totalCount}
                                            </div>
                                            <div className="text-sm text-gray-500 capitalize">{category}</div>
                                            <div className="w-full bg-gray-200 rounded-full h-2 mt-2">
                                                <div
                                                    className="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                                    style={{ width: `${percentage}%` }}
                                                ></div>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Actions */}
                    <div className="flex justify-end gap-4">
                        <Link href="/admin/roles">
                            <Button variant="outline" type="button">
                                Cancel
                            </Button>
                        </Link>
                        <Button type="submit" disabled={isSubmitting}>
                            <Save className="mr-2 h-4 w-4" />
                            {isSubmitting ? 'Saving...' : 'Save Changes'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
