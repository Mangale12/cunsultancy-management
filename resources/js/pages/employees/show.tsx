import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ArrowLeft, Edit, Users, Building, Mail, Phone, Calendar, DollarSign, MapPin } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

interface Branch {
    id: number;
    name: string;
    code: string;
}

interface Employee {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone?: string;
    address?: string;
    hire_date?: string;
    salary?: number;
    position?: string;
    department?: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    branch?: Branch;
    branch_id?: number;
}

interface Props {
    employee: Employee;
}

export default function EmployeeShow({ employee }: Props) {
    return (
        <AppLayout breadcrumbs={[
            { title: 'Employees', href: '/employees' },
            { title: 'Employee Details', href: `/employees/${employee.id}` }
        ]}>
            <Head title={`${employee.first_name} ${employee.last_name}`} />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex items-center gap-4">
                    <Link href="/employees">
                        <Button variant="outline" size="sm">
                            <ArrowLeft className="mr-2 h-4 w-4" />
                            Back to Employees
                        </Button>
                    </Link>
                    <div className="flex-1">
                        <h1 className="text-3xl font-bold tracking-tight">{employee.first_name} {employee.last_name}</h1>
                        <p className="text-muted-foreground">
                            Employee details and information
                        </p>
                    </div>
                    <Link href={`/employees/${employee.id}/edit`} className="inline-flex">
                        <Button>
                            <Edit className="mr-2 h-4 w-4" />
                            Edit Employee
                        </Button>
                    </Link>
                </div>

                {/* Employee Details */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Main Information */}
                    <div className="lg:col-span-2 space-y-6">
                        {/* Personal Information */}
                        <Card>
                            <CardHeader>
                                <CardTitle>Personal Information</CardTitle>
                                <CardDescription>
                                    Basic details about the employee
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 className="font-medium text-gray-900">Full Name</h4>
                                        <p className="text-gray-600">{employee.first_name} {employee.last_name}</p>
                                    </div>
                                    <div>
                                        <h4 className="font-medium text-gray-900">Email Address</h4>
                                        <a
                                            href={`mailto:${employee.email}`}
                                            className="text-blue-600 hover:text-blue-800 underline"
                                        >
                                            {employee.email}
                                        </a>
                                    </div>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 className="font-medium text-gray-900 flex items-center gap-2">
                                            <Phone className="h-4 w-4" />
                                            Phone Number
                                        </h4>
                                        {employee.phone ? (
                                            <a
                                                href={`tel:${employee.phone}`}
                                                className="text-blue-600 hover:text-blue-800 underline"
                                            >
                                                {employee.phone}
                                            </a>
                                        ) : (
                                            <p className="text-gray-400">No phone number</p>
                                        )}
                                    </div>
                                    <div>
                                        <h4 className="font-medium text-gray-900 flex items-center gap-2">
                                            <MapPin className="h-4 w-4" />
                                            Address
                                        </h4>
                                        <p className="text-gray-600">
                                            {employee.address || 'No address provided'}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <h4 className="font-medium text-gray-900">Status</h4>
                                    <Badge
                                        variant={employee.is_active ? 'default' : 'secondary'}
                                        className={employee.is_active ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-800 border-gray-200'}
                                    >
                                        {employee.is_active ? 'Active' : 'Inactive'}
                                    </Badge>
                                </div>
                            </CardContent>
                        </Card>

                        {/* Job Information */}
                        <Card>
                            <CardHeader>
                                <CardTitle>Job Information</CardTitle>
                                <CardDescription>
                                    Employment details and position
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 className="font-medium text-gray-900">Position</h4>
                                        <p className="text-gray-600">
                                            {employee.position || 'No position assigned'}
                                        </p>
                                    </div>
                                    <div>
                                        <h4 className="font-medium text-gray-900">Department</h4>
                                        <p className="text-gray-600">
                                            {employee.department || 'No department assigned'}
                                        </p>
                                    </div>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 className="font-medium text-gray-900 flex items-center gap-2">
                                            <DollarSign className="h-4 w-4" />
                                            Salary
                                        </h4>
                                        <p className="text-gray-600">
                                            {employee.salary ? `$${employee.salary.toLocaleString()}` : 'No salary specified'}
                                        </p>
                                    </div>
                                    <div>
                                        <h4 className="font-medium text-gray-900 flex items-center gap-2">
                                            <Calendar className="h-4 w-4" />
                                            Hire Date
                                        </h4>
                                        <p className="text-gray-600">
                                            {employee.hire_date ? new Date(employee.hire_date).toLocaleDateString() : 'No hire date'}
                                        </p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        {/* Location Information */}
                        <Card>
                            <CardHeader>
                                <CardTitle>Location Information</CardTitle>
                                <CardDescription>
                                    Branch and location details
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 className="font-medium text-gray-900 flex items-center gap-2">
                                            <Building className="h-4 w-4" />
                                            Branch
                                        </h4>
                                        <p className="text-gray-600">
                                            {employee.branch ? employee.branch.name : 'No branch assigned'}
                                        </p>
                                    </div>
                                    <div>
                                        <h4 className="font-medium text-gray-900">Branch Code</h4>
                                        <p className="text-gray-600">
                                            {employee.branch ? employee.branch.code : 'N/A'}
                                        </p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    {/* Sidebar */}
                    <div className="space-y-6">
                        {/* Quick Actions */}
                        <Card>
                            <CardHeader>
                                <CardTitle>Quick Actions</CardTitle>
                                <CardDescription>
                                    Common operations
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-3">
                                <Link href={`/employees/${employee.id}/edit`} className="block">
                                    <Button className="w-full">
                                        <Edit className="mr-2 h-4 w-4" />
                                        Edit Employee
                                    </Button>
                                </Link>
                                <Link href="/employees" className="block">
                                    <Button variant="outline" className="w-full">
                                        <ArrowLeft className="mr-2 h-4 w-4" />
                                        Back to List
                                    </Button>
                                </Link>
                            </CardContent>
                        </Card>

                        {/* Employment Summary */}
                        <Card>
                            <CardHeader>
                                <CardTitle>Employment Summary</CardTitle>
                                <CardDescription>
                                    Key employment metrics
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="flex justify-between items-center">
                                    <span className="text-gray-600">Employment Status</span>
                                    <Badge
                                        variant={employee.is_active ? 'default' : 'secondary'}
                                        className={employee.is_active ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-800 border-gray-200'}
                                    >
                                        {employee.is_active ? 'Active' : 'Inactive'}
                                    </Badge>
                                </div>
                                <div className="flex justify-between items-center">
                                    <span className="text-gray-600">Years of Service</span>
                                    <Badge variant="outline">
                                        {employee.hire_date 
                                            ? Math.floor((new Date().getTime() - new Date(employee.hire_date).getTime()) / (1000 * 60 * 60 * 24 * 365))
                                            : 0
                                        } years
                                    </Badge>
                                </div>
                                <div className="flex justify-between items-center">
                                    <span className="text-gray-600">Salary Range</span>
                                    <Badge variant="outline">
                                        {employee.salary ? 'Mid Range' : 'Not specified'}
                                    </Badge>
                                </div>
                            </CardContent>
                        </Card>

                        {/* Timestamps */}
                        <Card>
                            <CardHeader>
                                <CardTitle>Record Information</CardTitle>
                                <CardDescription>
                                    Creation and modification details
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div>
                                    <h4 className="font-medium text-gray-900 flex items-center gap-2">
                                        <Calendar className="h-4 w-4" />
                                        Created
                                    </h4>
                                    <p className="text-gray-600">
                                        {new Date(employee.created_at).toLocaleDateString()}
                                    </p>
                                </div>
                                <div>
                                    <h4 className="font-medium text-gray-900 flex items-center gap-2">
                                        <Calendar className="h-4 w-4" />
                                        Last Updated
                                    </h4>
                                    <p className="text-gray-600">
                                        {new Date(employee.updated_at).toLocaleDateString()}
                                    </p>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
