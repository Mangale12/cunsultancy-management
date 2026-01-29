import React, { useState, useEffect } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { ArrowLeft, Save, Shield, Users, UserCheck, Eye, Info } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

interface User {
    id: number;
    name: string;
    email: string;
    roles: string[];
    permissions: string[];
    employee?: {
        id: number;
        name: string;
        branch_id: number;
    };
    agent?: {
        id: number;
        name: string;
        branch_id: number;
    };
    student?: {
        id: number;
        name: string;
        branch_id: number;
    };
}

interface Role {
    id: number;
    name: string;
}

interface Permission {
    id: number;
    name: string;
}

interface Props {
    user: User;
    roles: Role[];
    permissions: { [key: string]: Permission[] };
    flash?: {
        success?: string;
        error?: string;
    };
}

export default function UserRolesEdit({ user, roles, permissions, flash }: Props) {
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
            title: 'Edit User',
            href: `/admin/user-roles/${user.id}/edit`,
        },
    ];

    const [selectedRoles, setSelectedRoles] = useState<string[]>(user.roles);
    const [selectedPermissions, setSelectedPermissions] = useState<string[]>(user.permissions);
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

    const getUserType = (user: User) => {
        if (user.employee) return { type: 'Employee', icon: Users, color: 'text-blue-600' };
        if (user.agent) return { type: 'Agent', icon: Shield, color: 'text-green-600' };
        if (user.student) return { type: 'Student', icon: UserCheck, color: 'text-purple-600' };
        return { type: 'User', icon: Eye, color: 'text-gray-600' };
    };

    const handleRoleChange = (roleName: string, checked: boolean) => {
        if (checked) {
            setSelectedRoles([...selectedRoles, roleName]);
        } else {
            setSelectedRoles(selectedRoles.filter(r => r !== roleName));
        }
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

        router.put(`/admin/user-roles/${user.id}`, {
            roles: selectedRoles,
            permissions: selectedPermissions,
        }, {
            onSuccess: () => {
                setIsSubmitting(false);
            },
            onError: (errors) => {
                setIsSubmitting(false);
                console.error('Error updating user roles:', errors);
            },
        });
    };

    const userType = getUserType(user);
    const Icon = userType.icon;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit Roles - ${user.name}`} />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div className="flex items-center gap-4">
                        <Link href="/admin/user-roles">
                            <Button variant="outline" size="sm">
                                <ArrowLeft className="mr-2 h-4 w-4" />
                                Back
                            </Button>
                        </Link>
                        <div>
                            <h1 className="text-3xl font-bold tracking-tight">Edit User Roles</h1>
                            <p className="text-muted-foreground">
                                Manage roles and permissions for {user.name}
                            </p>
                        </div>
                    </div>
                </div>

                {/* User Info */}
                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <Icon className={`h-5 w-5 ${userType.color}`} />
                            {user.name}
                        </CardTitle>
                        <CardDescription>
                            User information and current status
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <Label className="text-sm font-medium text-gray-500">Email</Label>
                                <p className="font-medium">{user.email}</p>
                            </div>
                            <div>
                                <Label className="text-sm font-medium text-gray-500">User Type</Label>
                                <div className="flex items-center gap-2 mt-1">
                                    <Icon className={`h-4 w-4 ${userType.color}`} />
                                    <span className="font-medium">{userType.type}</span>
                                </div>
                            </div>
                            <div>
                                <Label className="text-sm font-medium text-gray-500">Current Roles</Label>
                                <div className="flex flex-wrap gap-1 mt-1">
                                    {user.roles.length > 0 ? (
                                        user.roles.map((role) => (
                                            <Badge
                                                key={role}
                                                variant="outline"
                                                className={getRoleBadgeColor(role)}
                                            >
                                                {role.replace('_', ' ').toUpperCase()}
                                            </Badge>
                                        ))
                                    ) : (
                                        <Badge variant="outline" className="bg-gray-100 text-gray-600 border-gray-200">
                                            NO ROLES
                                        </Badge>
                                    )}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Alert */}
                {flash?.success && (
                    <Alert className="bg-green-50 border-green-200">
                        <Info className="h-4 w-4 text-green-600" />
                        <AlertDescription className="text-green-800">
                            {flash.success}
                        </AlertDescription>
                    </Alert>
                )}

                <form onSubmit={handleSubmit}>
                    {/* Roles Selection */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Roles</CardTitle>
                            <CardDescription>
                                Assign roles to the user. Roles come with predefined permissions.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                {roles.map((role) => (
                                    <div key={role.id} className="flex items-center space-x-2 p-3 border rounded-lg">
                                        <Checkbox
                                            id={`role-${role.name}`}
                                            checked={selectedRoles.includes(role.name)}
                                            onCheckedChange={(checked) => handleRoleChange(role.name, checked as boolean)}
                                        />
                                        <Label
                                            htmlFor={`role-${role.name}`}
                                            className="flex-1 cursor-pointer"
                                        >
                                            <div className="flex items-center gap-2">
                                                <Badge
                                                    variant="outline"
                                                    className={getRoleBadgeColor(role.name)}
                                                >
                                                    {role.name.replace('_', ' ').toUpperCase()}
                                                </Badge>
                                            </div>
                                        </Label>
                                    </div>
                                ))}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Permissions Selection */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Direct Permissions</CardTitle>
                            <CardDescription>
                                Assign additional permissions directly to the user. These permissions are in addition to role-based permissions.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-6">
                                {Object.entries(permissions).map(([category, categoryPermissions]) => (
                                    <div key={category}>
                                        <h4 className="font-medium mb-3 capitalize flex items-center gap-2">
                                            <Shield className="h-4 w-4" />
                                            {category} Permissions
                                        </h4>
                                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                            {categoryPermissions.map((permission) => (
                                                <div key={permission.id} className="flex items-center space-x-2 p-2 border rounded">
                                                    <Checkbox
                                                        id={`permission-${permission.name}`}
                                                        checked={selectedPermissions.includes(permission.name)}
                                                        onCheckedChange={(checked) => handlePermissionChange(permission.name, checked as boolean)}
                                                    />
                                                    <Label
                                                        htmlFor={`permission-${permission.name}`}
                                                        className="flex-1 cursor-pointer text-sm"
                                                    >
                                                        {permission.name.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
                                                    </Label>
                                                </div>
                                            ))}
                                        </div>
                                        <Separator className="mt-4" />
                                    </div>
                                ))}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Actions */}
                    <div className="flex justify-end gap-4">
                        <Link href="/admin/user-roles">
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
