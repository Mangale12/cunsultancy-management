import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import Heading from '@/components/heading';
import { ArrowLeft, Edit, Eye, Mail, Phone, MapPin, Calendar, User, FileText, CreditCard, Plus, X } from 'lucide-react';
import { useState } from 'react';

interface Student {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone: string | null;
    address: string | null;
    date_of_birth: string;
    status: string;
    branch: {
        id: number;
        name: string;
    };
    agent: {
        id: number;
        name: string;
    } | null;
    course: {
        id: number;
        name: string;
    } | null;
    country: {
        id: number;
        name: string;
    } | null;
    state: {
        id: number;
        name: string;
    } | null;
    created_at: string;
    updated_at: string;
}

interface Props {
    student: Student;
    universities: Array<{ id: number; name: string }>;
    courses: Array<{ id: number; name: string }>;
    intakes: Array<{ id: number; name: string }>;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Students',
        href: '/students',
        isCurrent: false,
    },
    {
        title: 'View Student',
        href: '#',
        isCurrent: true,
    },
];

const getStatusBadge = (status: string) => {
    // ... existing getStatusBadge implementation ...
};

const getPaymentStatusBadge = (status: string) => {
    // ... existing getPaymentStatusBadge implementation ...
};

export default function StudentShow({ student, universities, courses, intakes }: Props) {
    const [showModal, setShowModal] = useState(false);
    const { data, setData, post, processing, errors } = useForm({
        student_id: student.id,
        university_id: '',
        course_id: '',
        intake_id: '',
        status: 'pending',
        notes: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('student-applications.store'), {
            onSuccess: () => {
                setShowModal(false);
                window.location.reload();
            },
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${student.first_name} ${student.last_name}`} />
            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <Link href="/students" className="flex items-center text-sm text-muted-foreground hover:text-foreground">
                        <ArrowLeft className="h-4 w-4 mr-1" /> Back to Students
                    </Link>
                    <div className="flex items-center space-x-2">
                        <Link href={`/students/${student.id}/edit`}>
                            <Button variant="outline" size="sm">
                                <Edit className="h-4 w-4 mr-2" />
                                Edit
                            </Button>
                        </Link>
                        <Button size="sm" onClick={() => setShowModal(true)}>
                            <Plus className="h-4 w-4 mr-2" />
                            Apply for Application
                        </Button>
                    </div>
                </div>

                {/* Student Details Card */}
                <Card>
                    <CardHeader>
                        <div className="flex items-center space-x-4">
                            <div className="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center">
                                <User className="h-8 w-8 text-gray-500" />
                            </div>
                            <div>
                                <h2 className="text-2xl font-bold">{student.first_name} {student.last_name}</h2>
                                <p className="text-muted-foreground">{student.email}</p>
                                <div className="mt-2">
                                    <Badge variant="outline">{student.status}</Badge>
                                </div>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div className="space-y-2">
                                <h3 className="text-sm font-medium text-muted-foreground">Contact Information</h3>
                                <div className="space-y-1">
                                    <div className="flex items-center">
                                        <Mail className="h-4 w-4 mr-2 text-muted-foreground" />
                                        <span>{student.email}</span>
                                    </div>
                                    <div className="flex items-center">
                                        <Phone className="h-4 w-4 mr-2 text-muted-foreground" />
                                        <span>{student.phone || 'N/A'}</span>
                                    </div>
                                    <div className="flex items-start">
                                        <MapPin className="h-4 w-4 mr-2 mt-0.5 text-muted-foreground flex-shrink-0" />
                                        <span>{student.address || 'N/A'}</span>
                                    </div>
                                </div>
                            </div>
                            <div className="space-y-2">
                                <h3 className="text-sm font-medium text-muted-foreground">Personal Information</h3>
                                <div className="space-y-1">
                                    <div className="flex items-center">
                                        <Calendar className="h-4 w-4 mr-2 text-muted-foreground" />
                                        <span>Date of Birth: {new Date(student.date_of_birth).toLocaleDateString()}</span>
                                    </div>
                                    <div className="flex items-center">
                                        <User className="h-4 w-4 mr-2 text-muted-foreground" />
                                        <span>Agent: {student.agent?.name || 'N/A'}</span>
                                    </div>
                                    <div className="flex items-center">
                                        <MapPin className="h-4 w-4 mr-2 text-muted-foreground" />
                                        <span>Branch: {student.branch?.name || 'N/A'}</span>
                                    </div>
                                </div>
                            </div>
                            <div className="space-y-2">
                                <h3 className="text-sm font-medium text-muted-foreground">Course Information</h3>
                                <div className="space-y-1">
                                    <div className="flex items-center">
                                        <FileText className="h-4 w-4 mr-2 text-muted-foreground" />
                                        <span>Course: {student.course?.name || 'N/A'}</span>
                                    </div>
                                    <div className="flex items-center">
                                        <MapPin className="h-4 w-4 mr-2 text-muted-foreground" />
                                        <span>Country: {student.country?.name || 'N/A'}</span>
                                    </div>
                                    <div className="flex items-center">
                                        <MapPin className="h-4 w-4 mr-2 text-muted-foreground" />
                                        <span>State: {student.state?.name || 'N/A'}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Application Modal */}
                {showModal && (
                    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                        <div className="bg-white rounded-lg w-full max-w-2xl">
                            <div className="flex items-center justify-between p-4 border-b">
                                <h3 className="text-lg font-semibold">New Application for {student.first_name} {student.last_name}</h3>
                                <button 
                                    onClick={() => setShowModal(false)}
                                    className="text-gray-500 hover:text-gray-700"
                                >
                                    <X className="h-5 w-5" />
                                </button>
                            </div>
                            <form onSubmit={handleSubmit} className="p-6 space-y-4">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div className="space-y-2">
                                        <label className="block text-sm font-medium text-gray-700">University</label>
                                        <select
                                            className="w-full border rounded-md p-2"
                                            value={data.university_id}
                                            onChange={(e) => setData('university_id', e.target.value)}
                                            required
                                        >
                                            <option value="">Select University</option>
                                            {universities.map((university) => (
                                                <option key={university.id} value={university.id}>
                                                    {university.name}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.university_id && (
                                            <p className="text-red-500 text-sm">{errors.university_id}</p>
                                        )}
                                    </div>

                                    <div className="space-y-2">
                                        <label className="block text-sm font-medium text-gray-700">Course</label>
                                        <select
                                            className="w-full border rounded-md p-2"
                                            value={data.course_id}
                                            onChange={(e) => setData('course_id', e.target.value)}
                                            required
                                        >
                                            <option value="">Select Course</option>
                                            {courses.map((course) => (
                                                <option key={course.id} value={course.id}>
                                                    {course.name}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.course_id && (
                                            <p className="text-red-500 text-sm">{errors.course_id}</p>
                                        )}
                                    </div>

                                    <div className="space-y-2">
                                        <label className="block text-sm font-medium text-gray-700">Intake</label>
                                        <select
                                            className="w-full border rounded-md p-2"
                                            value={data.intake_id}
                                            onChange={(e) => setData('intake_id', e.target.value)}
                                            required
                                        >
                                            <option value="">Select Intake</option>
                                            {intakes.map((intake) => (
                                                <option key={intake.id} value={intake.id}>
                                                    {intake.name}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.intake_id && (
                                            <p className="text-red-500 text-sm">{errors.intake_id}</p>
                                        )}
                                    </div>

                                    <div className="space-y-2 md:col-span-2">
                                        <label className="block text-sm font-medium text-gray-700">Notes</label>
                                        <textarea
                                            className="w-full border rounded-md p-2"
                                            rows={3}
                                            value={data.notes}
                                            onChange={(e) => setData('notes', e.target.value)}
                                        />
                                    </div>
                                </div>

                                <div className="flex justify-end space-x-3 pt-4 border-t">
                                    <button
                                        type="button"
                                        onClick={() => setShowModal(false)}
                                        className="px-4 py-2 border rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                                        disabled={processing}
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        className="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 disabled:opacity-50"
                                        disabled={processing}
                                    >
                                        {processing ? 'Submitting...' : 'Submit Application'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}