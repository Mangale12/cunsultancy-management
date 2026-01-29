import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ArrowLeft, Save, Users, Building, Mail, Phone, Calendar, DollarSign } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

interface Branch {
    id: number;
    name: string;
    code: string;
}

interface Props {
    branches: Branch[];
}

export default function EmployeeCreate({ branches }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        address: '',
        position: '',
        department: '',
        salary: '',
        hire_date: '',
        branch_id: '',
        is_active: true,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        
        // Update branch_id to empty string if it's "select" before submitting
        if (data.branch_id === 'select') {
            setData('branch_id', '');
        }
        
        post('/employees');
    };

    return (
        <AppLayout breadcrumbs={[
            { title: 'Employees', href: '/employees' },
            { title: 'Create Employee', href: '/employees/create' }
        ]}>
            <Head title="Create Employee" />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex items-center gap-4">
                    <Link href="/employees">
                        <Button variant="outline" size="sm">
                            <ArrowLeft className="mr-2 h-4 w-4" />
                            Back to Employees
                        </Button>
                    </Link>
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">Create Employee</h1>
                        <p className="text-muted-foreground">
                            Add a new employee to the system
                        </p>
                    </div>
                </div>

                <form onSubmit={handleSubmit}>
                    {/* Basic Information */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Basic Information</CardTitle>
                            <CardDescription>
                                Enter the employee's personal details
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="first_name">First Name *</Label>
                                    <Input
                                        id="first_name"
                                        type="text"
                                        value={data.first_name}
                                        onChange={(e) => setData('first_name', e.target.value)}
                                        placeholder="Enter first name"
                                        required
                                    />
                                    {errors.first_name && (
                                        <p className="text-sm text-red-600 mt-1">{errors.first_name}</p>
                                    )}
                                </div>
                                <div>
                                    <Label htmlFor="last_name">Last Name *</Label>
                                    <Input
                                        id="last_name"
                                        type="text"
                                        value={data.last_name}
                                        onChange={(e) => setData('last_name', e.target.value)}
                                        placeholder="Enter last name"
                                        required
                                    />
                                    {errors.last_name && (
                                        <p className="text-sm text-red-600 mt-1">{errors.last_name}</p>
                                    )}
                                </div>
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="email">Email Address *</Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        placeholder="employee@example.com"
                                        required
                                    />
                                    {errors.email && (
                                        <p className="text-sm text-red-600 mt-1">{errors.email}</p>
                                    )}
                                </div>
                                <div>
                                    <Label htmlFor="phone">Phone Number</Label>
                                    <Input
                                        id="phone"
                                        type="tel"
                                        value={data.phone}
                                        onChange={(e) => setData('phone', e.target.value)}
                                        placeholder="+1 (555) 123-4567"
                                    />
                                    {errors.phone && (
                                        <p className="text-sm text-red-600 mt-1">{errors.phone}</p>
                                    )}
                                </div>
                                <div>
                                    <Label htmlFor="hire_date">Hire Date</Label>
                                    <Input
                                        id="hire_date"
                                        type="date"
                                        value={data.hire_date}
                                        onChange={(e) => setData('hire_date', e.target.value)}
                                    />
                                    {errors.hire_date && (
                                        <p className="text-sm text-red-600 mt-1">{errors.hire_date}</p>
                                    )}
                                </div>
                            </div>
                            <div>
                                <Label htmlFor="address">Address</Label>
                                <textarea
                                    id="address"
                                    value={data.address}
                                    onChange={(e) => setData('address', e.target.value)}
                                    placeholder="Enter full address"
                                    rows={3}
                                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                                {errors.address && (
                                    <p className="text-sm text-red-600 mt-1">{errors.address}</p>
                                )}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Job Information */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Job Information</CardTitle>
                            <CardDescription>
                                Enter the employee's job details
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="position">Position</Label>
                                    <Input
                                        id="position"
                                        type="text"
                                        value={data.position}
                                        onChange={(e) => setData('position', e.target.value)}
                                        placeholder="e.g., Software Developer"
                                    />
                                    {errors.position && (
                                        <p className="text-sm text-red-600 mt-1">{errors.position}</p>
                                    )}
                                </div>
                                <div>
                                    <Label htmlFor="department">Department</Label>
                                    <Input
                                        id="department"
                                        type="text"
                                        value={data.department}
                                        onChange={(e) => setData('department', e.target.value)}
                                        placeholder="e.g., Engineering"
                                    />
                                    {errors.department && (
                                        <p className="text-sm text-red-600 mt-1">{errors.department}</p>
                                    )}
                                </div>
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="salary">Salary</Label>
                                    <Input
                                        id="salary"
                                        type="number"
                                        step="0.01"
                                        value={data.salary}
                                        onChange={(e) => setData('salary', e.target.value)}
                                        placeholder="0.00"
                                    />
                                    {errors.salary && (
                                        <p className="text-sm text-red-600 mt-1">{errors.salary}</p>
                                    )}
                                </div>
                                <div>
                                    <Label htmlFor="branch_id">Branch *</Label>
                                    <Select value={data.branch_id} onValueChange={(value) => setData('branch_id', value)}>
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Select a branch" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="select">Select a branch</SelectItem>
                                            {branches.map((branch) => (
                                                <SelectItem key={branch.id} value={branch.id.toString()}>
                                                    {branch.name}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    {errors.branch_id && (
                                        <p className="text-sm text-red-600 mt-1">{errors.branch_id}</p>
                                    )}
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    {/* Status */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Status</CardTitle>
                            <CardDescription>
                                Set the employee's active status
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="flex items-center space-x-2">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    checked={data.is_active}
                                    onChange={(e) => setData('is_active', e.target.checked)}
                                    className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                />
                                <Label htmlFor="is_active" className="cursor-pointer">
                                    <span className={data.is_active ? 'text-green-600' : 'text-gray-600'}>
                                        {data.is_active ? 'Active' : 'Inactive'}
                                    </span>
                                </Label>
                            </div>
                        </CardContent>
                    </Card>

                    {/* Actions */}
                    <div className="flex justify-end gap-4">
                        <Link href="/employees">
                            <Button variant="outline" type="button">
                                Cancel
                            </Button>
                        </Link>
                        <Button type="submit" disabled={processing}>
                            <Save className="mr-2 h-4 w-4" />
                            {processing ? 'Creating...' : 'Create Employee'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
