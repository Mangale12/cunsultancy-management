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
}

interface Props {
    application: StudentApplication;
    students: Student[];
    universities: University[];
    courses: Course[];
}

const applicationStatuses = [
    { value: 'draft', label: 'Draft', variant: 'secondary' as const },
    { value: 'submitted', label: 'Submitted', variant: 'default' as const },
    { value: 'under_review', label: 'Under Review', variant: 'secondary' as const },
    { value: 'admitted', label: 'Admitted', variant: 'default' as const },
    { value: 'rejected', label: 'Rejected', variant: 'destructive' as const },
    { value: 'enrolled', label: 'Enrolled', variant: 'default' as const },
    { value: 'withdrawn', label: 'Withdrawn', variant: 'outline' as const },
    { value: 'deferred', label: 'Deferred', variant: 'secondary' as const },
];

const visaStatuses = [
    { value: 'not_started', label: 'Not Started', variant: 'secondary' as const },
    { value: 'documents_collected', label: 'Documents Collected', variant: 'default' as const },
    { value: 'application_submitted', label: 'Application Submitted', variant: 'default' as const },
    { value: 'interview_scheduled', label: 'Interview Scheduled', variant: 'secondary' as const },
    { value: 'interview_completed', label: 'Interview Completed', variant: 'default' as const },
    { value: 'approved', label: 'Approved', variant: 'default' as const },
    { value: 'rejected', label: 'Rejected', variant: 'destructive' as const },
    { value: 'issued', label: 'Issued', variant: 'default' as const },
];

const preDepartureStatuses = [
    { value: 'not_started', label: 'Not Started', variant: 'secondary' as const },
    { value: 'documents_ready', label: 'Documents Ready', variant: 'default' as const },
    { value: 'flight_booked', label: 'Flight Booked', variant: 'default' as const },
    { value: 'accommodation_arranged', label: 'Accommodation Arranged', variant: 'default' as const },
    { value: 'insurance_done', label: 'Insurance Done', variant: 'default' as const },
    { value: 'ready', label: 'Ready', variant: 'default' as const },
];

export default function StudentApplicationsEdit({ application, students, universities, courses }: Props) {
    const [selectedUniversity, setSelectedUniversity] = useState(application.university.id.toString());
    const [availableCourses, setAvailableCourses] = useState(courses);

    const { data, setData, put, processing, errors } = useForm({
        student_id: application.student.id.toString(),
        university_id: application.university.id.toString(),
        course_id: application.course.id.toString(),
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

    const handleUniversityChange = (universityId: string) => {
        setData('university_id', universityId);
        setData('course_id', '');
        setSelectedUniversity(universityId);

        // Fetch courses for selected university
        if (universityId) {
            router.get(
                `/api/universities/${universityId}/courses`,
                {},
                {
                    onSuccess: (response: any) => {
                        setAvailableCourses(response.props.courses || []);
                    },
                    onError: () => {
                        setAvailableCourses([]);
                    },
                }
            );
        } else {
            setAvailableCourses([]);
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(`/student-applications/${application.id}`);
    };

    const getNetFee = () => {
        const tuition = parseFloat(data.tuition_fee) || 0;
        const scholarship = parseFloat(data.scholarship_amount) || 0;
        return tuition - scholarship;
    };

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Student Applications', href: '/student-applications' },
        { title: 'Edit Application', href: `/student-applications/${application.id}/edit` },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit Application - ${application.student.first_name} ${application.student.last_name}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold">Edit Application</h1>
                        <p className="text-muted-foreground">
                            {application.student.first_name} {application.student.last_name} - {application.university.name}
                        </p>
                    </div>
                    <div className="flex gap-2">
                        <Button variant="outline" asChild>
                            <Link href={`/student-applications/${application.id}`}>
                                <ArrowLeft className="h-4 w-4 mr-2" />
                                Back to Application
                            </Link>
                        </Button>
                    </div>
                </div>

                <form onSubmit={handleSubmit} className="space-y-6">
                    {/* Basic Information */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Basic Information</CardTitle>
                            <CardDescription>
                                Update the basic application details
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="grid gap-6 md:grid-cols-2">
                                <div className="space-y-2">
                                    <Label htmlFor="student_id">Student *</Label>
                                    <Select value={data.student_id} onValueChange={(value) => setData('student_id', value)}>
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select a student" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {students.map((student) => (
                                                <SelectItem key={student.id} value={student.id.toString()}>
                                                    <div className="flex items-center gap-2">
                                                        <User className="h-4 w-4" />
                                                        <div>
                                                            <div className="font-medium">
                                                                {student.first_name} {student.last_name}
                                                            </div>
                                                            <div className="text-sm text-muted-foreground">
                                                                {student.email}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <InputError message={errors.student_id} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="university_id">University *</Label>
                                    <Select value={data.university_id} onValueChange={handleUniversityChange}>
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select a university" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {universities.map((university) => (
                                                <SelectItem key={university.id} value={university.id.toString()}>
                                                    <div className="flex items-center gap-2">
                                                        <Globe className="h-4 w-4" />
                                                        {university.name}
                                                    </div>
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <InputError message={errors.university_id} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="course_id">Course *</Label>
                                    <Select 
                                        value={data.course_id} 
                                        onValueChange={(value) => setData('course_id', value)}
                                        disabled={!selectedUniversity}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder={selectedUniversity ? "Select a course" : "First select a university"} />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {availableCourses.map((course) => (
                                                <SelectItem key={course.id} value={course.id.toString()}>
                                                    <div className="flex items-center gap-2">
                                                        <GraduationCap className="h-4 w-4" />
                                                        {course.name}
                                                    </div>
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <InputError message={errors.course_id} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="tuition_fee">Tuition Fee (USD)</Label>
                                    <div className="relative">
                                        <DollarSign className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <Input
                                            id="tuition_fee"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            placeholder="0.00"
                                            value={data.tuition_fee}
                                            onChange={(e) => setData('tuition_fee', e.target.value)}
                                            className="pl-10"
                                        />
                                    </div>
                                    <InputError message={errors.tuition_fee} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="scholarship_amount">Scholarship Amount (USD)</Label>
                                    <div className="relative">
                                        <DollarSign className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <Input
                                            id="scholarship_amount"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            placeholder="0.00"
                                            value={data.scholarship_amount}
                                            onChange={(e) => setData('scholarship_amount', e.target.value)}
                                            className="pl-10"
                                        />
                                    </div>
                                    <InputError message={errors.scholarship_amount} />
                                </div>

                                <div className="space-y-2">
                                    <Label>Net Fee</Label>
                                    <div className="p-3 bg-muted rounded-md">
                                        <div className="flex items-center gap-2 text-lg font-semibold">
                                            <DollarSign className="h-5 w-5" />
                                            ${getNetFee().toFixed(2)}
                                        </div>
                                        <div className="text-sm text-muted-foreground">
                                            Tuition: ${parseFloat(data.tuition_fee || '0').toFixed(2)} - 
                                            Scholarship: ${parseFloat(data.scholarship_amount || '0').toFixed(2)}
                                        </div>
                                    </div>
                                </div>

                                <div className="space-y-2 md:col-span-2">
                                    <Label htmlFor="notes">Notes</Label>
                                    <Textarea
                                        id="notes"
                                        placeholder="Add any additional notes about this application..."
                                        value={data.notes}
                                        onChange={(e) => setData('notes', e.target.value)}
                                        rows={4}
                                    />
                                    <InputError message={errors.notes} />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    {/* Status Management */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Status Management</CardTitle>
                            <CardDescription>
                                Update the current status of the application process
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="grid gap-6 md:grid-cols-3">
                                <div className="space-y-2">
                                    <Label htmlFor="application_status">Application Status</Label>
                                    <Select value={data.application_status} onValueChange={(value) => setData('application_status', value)}>
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select status" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {applicationStatuses.map((status) => (
                                                <SelectItem key={status.value} value={status.value}>
                                                    <div className="flex items-center gap-2">
                                                        <Badge variant={status.variant} className="text-xs">
                                                            {status.label}
                                                        </Badge>
                                                    </div>
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <InputError message={errors.application_status} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="visa_status">Visa Status</Label>
                                    <Select value={data.visa_status} onValueChange={(value) => setData('visa_status', value)}>
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select visa status" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {visaStatuses.map((status) => (
                                                <SelectItem key={status.value} value={status.value}>
                                                    <div className="flex items-center gap-2">
                                                        <Badge variant={status.variant} className="text-xs">
                                                            {status.label}
                                                        </Badge>
                                                    </div>
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <InputError message={errors.visa_status} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="pre_departure_status">Pre-Departure Status</Label>
                                    <Select value={data.pre_departure_status} onValueChange={(value) => setData('pre_departure_status', value)}>
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select status" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {preDepartureStatuses.map((status) => (
                                                <SelectItem key={status.value} value={status.value}>
                                                    <div className="flex items-center gap-2">
                                                        <Badge variant={status.variant} className="text-xs">
                                                            {status.label}
                                                        </Badge>
                                                    </div>
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <InputError message={errors.pre_departure_status} />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    {/* Important Dates */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Important Dates</CardTitle>
                            <CardDescription>
                                Track important dates throughout the application process
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="grid gap-6 md:grid-cols-2">
                                <div className="space-y-2">
                                    <Label htmlFor="application_date">Application Date</Label>
                                    <div className="relative">
                                        <Calendar className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <Input
                                            id="application_date"
                                            type="date"
                                            value={data.application_date}
                                            onChange={(e) => setData('application_date', e.target.value)}
                                            className="pl-10"
                                        />
                                    </div>
                                    <InputError message={errors.application_date} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="submission_deadline">Submission Deadline</Label>
                                    <div className="relative">
                                        <Calendar className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <Input
                                            id="submission_deadline"
                                            type="date"
                                            value={data.submission_deadline}
                                            onChange={(e) => setData('submission_deadline', e.target.value)}
                                            className="pl-10"
                                        />
                                    </div>
                                    <InputError message={errors.submission_deadline} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="admission_deadline">Admission Deadline</Label>
                                    <div className="relative">
                                        <Calendar className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <Input
                                            id="admission_deadline"
                                            type="date"
                                            value={data.admission_deadline}
                                            onChange={(e) => setData('admission_deadline', e.target.value)}
                                            className="pl-10"
                                        />
                                    </div>
                                    <InputError message={errors.admission_deadline} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="visa_application_date">Visa Application Date</Label>
                                    <div className="relative">
                                        <Calendar className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <Input
                                            id="visa_application_date"
                                            type="date"
                                            value={data.visa_application_date}
                                            onChange={(e) => setData('visa_application_date', e.target.value)}
                                            className="pl-10"
                                        />
                                    </div>
                                    <InputError message={errors.visa_application_date} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="visa_interview_date">Visa Interview Date</Label>
                                    <div className="relative">
                                        <Calendar className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <Input
                                            id="visa_interview_date"
                                            type="date"
                                            value={data.visa_interview_date}
                                            onChange={(e) => setData('visa_interview_date', e.target.value)}
                                            className="pl-10"
                                        />
                                    </div>
                                    <InputError message={errors.visa_interview_date} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="visa_approval_date">Visa Approval Date</Label>
                                    <div className="relative">
                                        <Calendar className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <Input
                                            id="visa_approval_date"
                                            type="date"
                                            value={data.visa_approval_date}
                                            onChange={(e) => setData('visa_approval_date', e.target.value)}
                                            className="pl-10"
                                        />
                                    </div>
                                    <InputError message={errors.visa_approval_date} />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    {/* Actions */}
                    <div className="flex justify-end gap-2">
                        <Button variant="outline" type="button" asChild>
                            <Link href={`/student-applications/${application.id}`}>
                                Cancel
                            </Link>
                        </Button>
                        <Button type="submit" disabled={processing}>
                            <Save className="h-4 w-4 mr-2" />
                            {processing ? 'Updating...' : 'Update Application'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
