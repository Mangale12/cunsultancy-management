import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Search, Edit, Users, Shield, UserCheck, Eye } from 'lucide-react';
import { toast } from '@/hooks/use-toast';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

interface User {
    id: number;
    name: string;
    email: string;
    roles: Array<{ name: string }>;
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

interface Props {
    users: {
        data: User[];
        links: any[];
        total: number;
        current_page: number;
        last_page: number;
        per_page: number;
        from: number;
        to: number;
    };
    roles: Role[];
    filters: {
        search?: string;
        role?: string;
        per_page: number;
    };
}

export default function UserRolesIndex({ users, roles, filters }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Admin',
            href: '/admin/user-roles',
        },
        {
            title: 'User Roles',
            href: '/admin/user-roles',
        },
    ];

    const [search, setSearch] = useState(filters.search || '');
    const [roleFilter, setRoleFilter] = useState(filters.role || 'all');
    const [perPage, setPerPage] = useState(filters.per_page?.toString() || '10');

    const handleSearch = () => {
        router.get('/admin/user-roles', {
            search,
            role: roleFilter === 'all' ? '' : roleFilter,
            per_page: perPage,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const handleKeyPress = (e: React.KeyboardEvent) => {
        if (e.key === 'Enter') {
            handleSearch();
        }
    };

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

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="User Roles & Permissions" />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">User Roles & Permissions</h1>
                        <p className="text-muted-foreground">
                            Manage user roles and permissions for the system
                        </p>
                    </div>
                    <div className="flex gap-2">
                        <Link href="/admin/roles">
                            <Button variant="outline">
                                <Shield className="mr-2 h-4 w-4" />
                                Manage Roles
                            </Button>
                        </Link>
                    </div>
                </div>

                {/* Filters */}
                <Card>
                    <CardHeader>
                        <CardTitle>Filters</CardTitle>
                        <CardDescription>
                            Search and filter users
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="flex gap-4 items-end">
                            <div className="flex-1">
                                <label className="text-sm font-medium mb-2 block">Search</label>
                                <div className="relative">
                                    <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-4 w-4" />
                                    <Input
                                        placeholder="Search by name or email..."
                                        value={search}
                                        onChange={(e) => setSearch(e.target.value)}
                                        onKeyPress={handleKeyPress}
                                        className="pl-10"
                                    />
                                </div>
                            </div>
                            <div>
                                <label className="text-sm font-medium mb-2 block">Role</label>
                                <Select value={roleFilter} onValueChange={setRoleFilter}>
                                    <SelectTrigger className="w-48">
                                        <SelectValue placeholder="Filter by role" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All Roles</SelectItem>
                                        {roles.map((role) => (
                                            <SelectItem key={role.id} value={role.name}>
                                                {role.name.replace('_', ' ').toUpperCase()}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div>
                                <label className="text-sm font-medium mb-2 block">Per Page</label>
                                <Select value={perPage} onValueChange={setPerPage}>
                                    <SelectTrigger className="w-20">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="10">10</SelectItem>
                                        <SelectItem value="25">25</SelectItem>
                                        <SelectItem value="50">50</SelectItem>
                                        <SelectItem value="100">100</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <Button onClick={handleSearch}>
                                <Search className="mr-2 h-4 w-4" />
                                Search
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                {/* Users Table */}
                <Card>
                    <CardHeader>
                        <CardTitle>Users ({users?.total || 0})</CardTitle>
                        <CardDescription>
                            Manage roles and permissions for each user
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>User</TableHead>
                                    <TableHead>Type</TableHead>
                                    <TableHead>Roles</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {users?.data?.map((user) => {
                                    const userType = getUserType(user);
                                    const Icon = userType.icon;
                                    
                                    return (
                                        <TableRow key={user.id}>
                                            <TableCell>
                                                <div>
                                                    <div className="font-medium">{user.name}</div>
                                                    <div className="text-sm text-gray-500">{user.email}</div>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-2">
                                                    <Icon className={`h-4 w-4 ${userType.color}`} />
                                                    <span className="text-sm">{userType.type}</span>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex flex-wrap gap-1">
                                                    {user.roles.length > 0 ? (
                                                        user.roles.map((role) => (
                                                            <Badge
                                                                key={role.name}
                                                                variant="outline"
                                                                className={getRoleBadgeColor(role.name)}
                                                            >
                                                                {role.name.replace('_', ' ').toUpperCase()}
                                                            </Badge>
                                                        ))
                                                    ) : (
                                                        <Badge variant="outline" className="bg-gray-100 text-gray-600 border-gray-200">
                                                            NO ROLES
                                                        </Badge>
                                                    )}
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-2">
                                                    <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                                                    <span className="text-sm text-green-600">Active</span>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex gap-2">
                                                    <Link href={`/admin/user-roles/${user.id}/edit`}>
                                                        <Button size="sm" variant="outline">
                                                            <Edit className="mr-2 h-4 w-4" />
                                                            Edit Roles
                                                        </Button>
                                                    </Link>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    );
                                })}
                            </TableBody>
                        </Table>

                        {/* Pagination */}
                        {users?.links && users.links.length > 3 && (
                            <div className="flex justify-center mt-6">
                                <div className="flex gap-2">
                                    {users.links.map((link, index) => (
                                        <Link
                                            key={index}
                                            href={link.url || '#'}
                                            className={`px-3 py-2 rounded-md text-sm ${
                                                link.active
                                                    ? 'bg-blue-600 text-white'
                                                    : link.url
                                                    ? 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                                    : 'bg-gray-50 text-gray-400 cursor-not-allowed'
                                            }`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
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
