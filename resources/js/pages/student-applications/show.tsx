import React, { useState } from 'react';
import { Head, Link, useForm, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Badge } from '@/components/ui/badge';
import InputError from '@/components/input-error';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { 
    ArrowLeft, 
    Save, 
    User, 
    GraduationCap, 
    Globe, 
    Calendar, 
    DollarSign, 
    AlertCircle,
    CheckCircle,
    Clock,
    FileText,
    CreditCard,
    Plane,
    Home
} from 'lucide-react';

interface Student {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
}

interface University {
    id: number;
    name: string;
}

interface Course {
    id: number;
    name: string;
    university_id: number;
}

interface Payment {
    id: number;
    payment_type: string;
    amount: number;
    currency: string;
    due_date: string;
    paid_date?: string;
    status: string;
    paid_amount: number;
}

interface StudentApplication {
    id: number;
    student: Student;
    university: University;
    course: Course;
    application_status: string;
    visa_status: string;
    pre_departure_status: string;
    application_date?: string;
    submission_deadline?: string;
    admission_deadline?: string;
    visa_application_date?: string;
    visa_interview_date?: string;
    visa_approval_date?: string;
    tuition_fee?: number;
    scholarship_amount?: number;
    notes?: string;
    payments: Payment[];
    created_at: string;
    updated_at: string;
}

interface Props {
    application: StudentApplication;
}

const applicationStatuses = [
    { value: 'draft', label: 'Draft', variant: 'secondary' as const, icon: FileText },
    { value: 'submitted', label: 'Submitted', variant: 'default' as const, icon: CheckCircle },
    { value: 'under_review', label: 'Under Review', variant: 'secondary' as const, icon: Clock },
    { value: 'admitted', label: 'Admitted', variant: 'default' as const, icon: CheckCircle },
    { value: 'rejected', label: 'Rejected', variant: 'destructive' as const, icon: AlertCircle },
    { value: 'enrolled', label: 'Enrolled', variant: 'default' as const, icon: CheckCircle },
    { value: 'withdrawn', label: 'Withdrawn', variant: 'outline' as const, icon: FileText },
    { value: 'deferred', label: 'Deferred', variant: 'secondary' as const, icon: Clock },
];

const visaStatuses = [
    { value: 'not_started', label: 'Not Started', variant: 'secondary' as const, icon: Clock },
    { value: 'documents_collected', label: 'Documents Collected', variant: 'default' as const, icon: CheckCircle },
    { value: 'application_submitted', label: 'Application Submitted', variant: 'default' as const, icon: FileText },
    { value: 'interview_scheduled', label: 'Interview Scheduled', variant: 'secondary' as const, icon: Calendar },
    { value: 'interview_completed', label: 'Interview Completed', variant: 'default' as const, icon: CheckCircle },
    { value: 'approved', label: 'Approved', variant: 'default' as const, icon: CheckCircle },
    { value: 'rejected', label: 'Rejected', variant: 'destructive' as const, icon: AlertCircle },
    { value: 'issued', label: 'Issued', variant: 'default' as const, icon: CheckCircle },
];

const preDepartureStatuses = [
    { value: 'not_started', label: 'Not Started', variant: 'secondary' as const, icon: Clock },
    { value: 'documents_ready', label: 'Documents Ready', variant: 'default' as const, icon: CheckCircle },
    { value: 'flight_booked', label: 'Flight Booked', variant: 'default' as const, icon: Plane },
    { value: 'accommodation_arranged', label: 'Accommodation Arranged', variant: 'default' as const, icon: Home },
    { value: 'insurance_done', label: 'Insurance Done', variant: 'default' as const, icon: CheckCircle },
    { value: 'ready', label: 'Ready', variant: 'default' as const, icon: CheckCircle },
];

export default function StudentApplicationsShow({ application }: Props) {
    const [isEditing, setIsEditing] = useState(false);
    
    const { data, setData, put, processing, errors } = useForm({
        application_status: application.application_status,
        visa_status: application.visa_status,
        pre_departure_status: application.pre_departure_status,
        application_date: application.application_date || '',
        submission_deadline: application.submission_deadline || '',
        admission_deadline: application.admission_deadline || '',
        visa_application_date: application.visa_application_date || '',
        visa_interview_date: application.visa_interview_date || '',
        visa_approval_date: application.visa_approval_date || '',
        tuition_fee: application.tuition_fee?.toString() || '',
        scholarship_amount: application.scholarship_amount?.toString() || '',
        notes: application.notes || '',
    });

    const handleStatusUpdate = () => {
        put(`/student-applications/${application.id}`, {
            onSuccess: () => setIsEditing(false),
        });
    };

    const getStatusBadge = (status: string, statuses: any[]) => {
        const statusObj = statuses.find(s => s.value === status);
        return statusObj || { label: status, variant: 'secondary' as const, icon: FileText };
    };

    const getNetFee = () => {
        if (!application.tuition_fee) return 0;
        return application.tuition_fee - (application.scholarship_amount || 0);
    };

    const getTotalPaid = () => {
        return application.payments.reduce((total, payment) => total + payment.paid_amount, 0);
    };

    const getTotalDue = () => {
        return application.payments.reduce((total, payment) => total + (payment.amount - payment.paid_amount), 0);
    };

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Student Applications', href: '/student-applications' },
        { title: 'Application Details', href: `/student-applications/${application.id}` },
    ];

    const currentAppStatus = getStatusBadge(application.application_status, applicationStatuses);
    const currentVisaStatus = getStatusBadge(application.visa_status, visaStatuses);
    const currentPreDepartureStatus = getStatusBadge(application.pre_departure_status, preDepartureStatuses);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Application - ${application.student.first_name} ${application.student.last_name}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold">Application Details</h1>
                        <p className="text-muted-foreground">
                            {application.student.first_name} {application.student.last_name} - {application.university.name}
                        </p>
                    </div>
                    <div className="flex gap-2">
                        <Button variant="outline" asChild>
                            <Link href={`/student-applications/${application.id}/edit`}>
                                <Save className="h-4 w-4 mr-2" />
                                Edit Application
                            </Link>
                        </Button>
                        <Button variant="outline" asChild>
                            <Link href="/student-applications">
                                <ArrowLeft className="h-4 w-4 mr-2" />
                                Back to Applications
                            </Link>
                        </Button>
                    </div>
                </div>

                {/* Student and Program Info */}
                <div className="grid gap-4 md:grid-cols-2">
                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                <User className="h-5 w-5" />
                                Student Information
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div>
                                <Label className="text-sm font-medium">Name</Label>
                                <p className="text-lg">{application.student.first_name} {application.student.last_name}</p>
                            </div>
                            <div>
                                <Label className="text-sm font-medium">Email</Label>
                                <p className="text-lg">{application.student.email}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                <GraduationCap className="h-5 w-5" />
                                Program Information
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div>
                                <Label className="text-sm font-medium">University</Label>
                                <p className="text-lg flex items-center gap-2">
                                    <Globe className="h-4 w-4" />
                                    {application.university.name}
                                </p>
                            </div>
                            <div>
                                <Label className="text-sm font-medium">Course</Label>
                                <p className="text-lg">{application.course.name}</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* Status Overview */}
                <Card>
                    <CardHeader>
                        <CardTitle>Application Status Overview</CardTitle>
                        <CardDescription>Current status of the application process</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="grid gap-4 md:grid-cols-3">
                            <div className="space-y-2">
                                <Label className="text-sm font-medium">Application Status</Label>
                                <Badge variant={currentAppStatus.variant} className="flex items-center gap-2 w-fit">
                                    <currentAppStatus.icon className="h-4 w-4" />
                                    {currentAppStatus.label}
                                </Badge>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-sm font-medium">Visa Status</Label>
                                <Badge variant={currentVisaStatus.variant} className="flex items-center gap-2 w-fit">
                                    <currentVisaStatus.icon className="h-4 w-4" />
                                    {currentVisaStatus.label}
                                </Badge>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-sm font-medium">Pre-Departure Status</Label>
                                <Badge variant={currentPreDepartureStatus.variant} className="flex items-center gap-2 w-fit">
                                    <currentPreDepartureStatus.icon className="h-4 w-4" />
                                    {currentPreDepartureStatus.label}
                                </Badge>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Financial Summary */}
                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <DollarSign className="h-5 w-5" />
                            Financial Summary
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="grid gap-4 md:grid-cols-4">
                            <div className="space-y-2">
                                <Label className="text-sm font-medium">Tuition Fee</Label>
                                <p className="text-2xl font-bold">
                                    ${application.tuition_fee?.toLocaleString() || '0'}
                                </p>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-sm font-medium">Scholarship</Label>
                                <p className="text-2xl font-bold text-green-600">
                                    -${application.scholarship_amount?.toLocaleString() || '0'}
                                </p>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-sm font-medium">Net Fee</Label>
                                <p className="text-2xl font-bold text-blue-600">
                                    ${getNetFee().toLocaleString()}
                                </p>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-sm font-medium">Total Paid</Label>
                                <p className="text-2xl font-bold text-green-600">
                                    ${getTotalPaid().toLocaleString()}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Payments */}
                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <CreditCard className="h-5 w-5" />
                            Payment Schedule
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-4">
                            {application.payments.map((payment) => (
                                <div key={payment.id} className="flex items-center justify-between p-4 border rounded-lg">
                                    <div className="flex items-center gap-4">
                                        <CreditCard className="h-5 w-5 text-muted-foreground" />
                                        <div>
                                            <p className="font-medium capitalize">{payment.payment_type.replace('_', ' ')}</p>
                                            <p className="text-sm text-muted-foreground">
                                                Due: {new Date(payment.due_date).toLocaleDateString()}
                                            </p>
                                        </div>
                                    </div>
                                    <div className="text-right">
                                        <p className="font-medium">${payment.amount.toLocaleString()}</p>
                                        <Badge variant={payment.status === 'paid' ? 'default' : 'secondary'}>
                                            {payment.status}
                                        </Badge>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </CardContent>
                </Card>

                {/* Important Dates */}
                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <Calendar className="h-5 w-5" />
                            Important Dates
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="grid gap-4 md:grid-cols-2">
                            <div className="space-y-4">
                                <div>
                                    <Label className="text-sm font-medium">Application Date</Label>
                                    <p className="flex items-center gap-2">
                                        <Calendar className="h-4 w-4" />
                                        {application.application_date ? new Date(application.application_date).toLocaleDateString() : 'Not set'}
                                    </p>
                                </div>
                                <div>
                                    <Label className="text-sm font-medium">Submission Deadline</Label>
                                    <p className="flex items-center gap-2">
                                        <Calendar className="h-4 w-4" />
                                        {application.submission_deadline ? new Date(application.submission_deadline).toLocaleDateString() : 'Not set'}
                                    </p>
                                </div>
                                <div>
                                    <Label className="text-sm font-medium">Admission Deadline</Label>
                                    <p className="flex items-center gap-2">
                                        <Calendar className="h-4 w-4" />
                                        {application.admission_deadline ? new Date(application.admission_deadline).toLocaleDateString() : 'Not set'}
                                    </p>
                                </div>
                            </div>
                            <div className="space-y-4">
                                <div>
                                    <Label className="text-sm font-medium">Visa Application Date</Label>
                                    <p className="flex items-center gap-2">
                                        <Calendar className="h-4 w-4" />
                                        {application.visa_application_date ? new Date(application.visa_application_date).toLocaleDateString() : 'Not set'}
                                    </p>
                                </div>
                                <div>
                                    <Label className="text-sm font-medium">Visa Interview Date</Label>
                                    <p className="flex items-center gap-2">
                                        <Calendar className="h-4 w-4" />
                                        {application.visa_interview_date ? new Date(application.visa_interview_date).toLocaleDateString() : 'Not set'}
                                    </p>
                                </div>
                                <div>
                                    <Label className="text-sm font-medium">Visa Approval Date</Label>
                                    <p className="flex items-center gap-2">
                                        <Calendar className="h-4 w-4" />
                                        {application.visa_approval_date ? new Date(application.visa_approval_date).toLocaleDateString() : 'Not set'}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Notes */}
                {application.notes && (
                    <Card>
                        <CardHeader>
                            <CardTitle>Notes</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p className="whitespace-pre-wrap">{application.notes}</p>
                        </CardContent>
                    </Card>
                )}
            </div>
        </AppLayout>
    );
}
