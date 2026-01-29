import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Shield, Edit, Users, ArrowLeft } from 'lucide-react';
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
    permissions: Permission[];
    users_count: number;
}

interface Props {
    roles: Role[];
    permissions: { [key: string]: Permission[] };
}

export default function RolesIndex({ roles, permissions }: Props) {
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
    ];

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

    const getPermissionCategory = (permissionName: string) => {
        return permissionName.split('_')[0];
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Roles & Permissions Management" />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div className="flex items-center gap-4">
                        <Link href="/admin/user-roles">
                            <Button variant="outline" size="sm">
                                <ArrowLeft className="mr-2 h-4 w-4" />
                                Back to Users
                            </Button>
                        </Link>
                        <div>
                            <h1 className="text-3xl font-bold tracking-tight">Roles & Permissions</h1>
                            <p className="text-muted-foreground">
                                Manage system roles and their associated permissions
                            </p>
                        </div>
                    </div>
                </div>

                {/* Roles Overview */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {roles.map((role) => (
                        <Card key={role.id} className="hover:shadow-md transition-shadow">
                            <CardHeader>
                                <CardTitle className="flex items-center justify-between">
                                    <div className="flex items-center gap-2">
                                        <Shield className="h-5 w-5" />
                                        <Badge
                                            variant="outline"
                                            className={getRoleBadgeColor(role.name)}
                                        >
                                            {role.name.replace('_', ' ').toUpperCase()}
                                        </Badge>
                                    </div>
                                    <Link href={`/admin/user-roles/roles/${role.id}/edit`}>
                                        <Button size="sm" variant="outline">
                                            <Edit className="h-4 w-4" />
                                        </Button>
                                    </Link>
                                </CardTitle>
                                <CardDescription>
                                    <div className="flex items-center gap-2">
                                        <Users className="h-4 w-4" />
                                        {role.users_count} user{role.users_count !== 1 ? 's' : ''} assigned
                                    </div>
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-2">
                                    <div className="text-sm font-medium">Permissions ({role.permissions.length}):</div>
                                    <div className="flex flex-wrap gap-1">
                                        {role.permissions.length > 0 ? (
                                            role.permissions.slice(0, 5).map((permission) => (
                                                <Badge
                                                    key={permission.id}
                                                    variant="secondary"
                                                    className="text-xs"
                                                >
                                                    {permission.name.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
                                                </Badge>
                                            ))
                                        ) : (
                                            <span className="text-sm text-gray-500">No permissions assigned</span>
                                        )}
                                        {role.permissions.length > 5 && (
                                            <Badge variant="outline" className="text-xs">
                                                +{role.permissions.length - 5} more
                                            </Badge>
                                        )}
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                {/* Detailed Permissions Table */}
                <Card>
                    <CardHeader>
                        <CardTitle>All Permissions</CardTitle>
                        <CardDescription>
                            Complete list of available permissions in the system
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-6">
                            {Object.entries(permissions).map(([category, categoryPermissions]) => (
                                <div key={category}>
                                    <h4 className="font-medium mb-3 capitalize flex items-center gap-2">
                                        <Shield className="h-4 w-4" />
                                        {category} Permissions ({categoryPermissions.length})
                                    </h4>
                                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                        {categoryPermissions.map((permission) => (
                                            <div
                                                key={permission.id}
                                                className="p-2 border rounded-md bg-gray-50"
                                            >
                                                <span className="text-sm font-medium">
                                                    {permission.name.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
                                                </span>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </CardContent>
                </Card>

                {/* Role-Permission Matrix */}
                <Card>
                    <CardHeader>
                        <CardTitle>Role-Permission Matrix</CardTitle>
                        <CardDescription>
                            Overview of which permissions are assigned to each role
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="overflow-x-auto">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Role</TableHead>
                                        {Object.keys(permissions).map((category) => (
                                            <TableHead key={category} className="text-center">
                                                {category.charAt(0).toUpperCase() + category.slice(1)}
                                            </TableHead>
                                        ))}
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {roles.map((role) => (
                                        <TableRow key={role.id}>
                                            <TableCell>
                                                <Badge
                                                    variant="outline"
                                                    className={getRoleBadgeColor(role.name)}
                                                >
                                                    {role.name.replace('_', ' ').toUpperCase()}
                                                </Badge>
                                            </TableCell>
                                            {Object.keys(permissions).map((category) => {
                                                const categoryPermissions = permissions[category];
                                                const roleHasCategoryPermission = categoryPermissions.some(cp =>
                                                    role.permissions.some(rp => rp.id === cp.id)
                                                );
                                                
                                                return (
                                                    <TableCell key={category} className="text-center">
                                                        {roleHasCategoryPermission ? (
                                                            <div className="w-4 h-4 bg-green-500 rounded-full mx-auto"></div>
                                                        ) : (
                                                            <div className="w-4 h-4 bg-gray-200 rounded-full mx-auto"></div>
                                                        )}
                                                    </TableCell>
                                                );
                                            })}
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
