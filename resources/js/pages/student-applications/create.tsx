import React, { useState } from 'react';
import { Head, Link, useForm, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import InputError from '@/components/input-error';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { ArrowLeft, Save, User, GraduationCap, Globe, Calendar, DollarSign, AlertCircle } from 'lucide-react';

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

interface Props {
    students: Student[];
    universities: University[];
    default_student_id?: number;
}

export default function StudentApplicationsCreate({ students, universities, default_student_id }: Props) {
    const [selectedUniversity, setSelectedUniversity] = useState('');
    const [courses, setCourses] = useState<Course[]>([]);

    const { data, setData, post, processing, errors } = useForm({
        student_id: default_student_id?.toString() || '',
        university_id: '',
        course_id: '',
        tuition_fee: '',
        scholarship_amount: '',
        submission_deadline: '',
        admission_deadline: '',
        notes: '',
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
                        setCourses(response.props.courses || []);
                    },
                    onError: () => {
                        setCourses([]);
                    },
                }
            );
        } else {
            setCourses([]);
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/student-applications');
    };

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Student Applications', href: '/student-applications' },
        { title: 'Create Application', href: '/student-applications/create' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Student Application" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold">Create Student Application</h1>
                        <p className="text-muted-foreground">
                            Create a new application for abroad studies
                        </p>
                    </div>
                    <Button variant="outline" asChild>
                        <Link href="/student-applications">
                            <ArrowLeft className="h-4 w-4 mr-2" />
                            Back to Applications
                        </Link>
                    </Button>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Application Details</CardTitle>
                        <CardDescription>
                            Fill in the application information for the student
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div className="grid gap-6 md:grid-cols-2">
                                {/* Student Selection */}
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

                                {/* University Selection */}
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

                                {/* Course Selection */}
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
                                            {courses.map((course) => (
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

                                {/* Tuition Fee */}
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

                                {/* Scholarship Amount */}
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

                                {/* Net Fee Display */}
                                {(data.tuition_fee || data.scholarship_amount) && (
                                    <div className="space-y-2">
                                        <Label>Net Fee</Label>
                                        <div className="p-3 bg-muted rounded-md">
                                            <div className="flex items-center gap-2 text-lg font-semibold">
                                                <DollarSign className="h-5 w-5" />
                                                ${((parseFloat(data.tuition_fee) || 0) - (parseFloat(data.scholarship_amount) || 0)).toFixed(2)}
                                            </div>
                                            <div className="text-sm text-muted-foreground">
                                                Tuition: ${parseFloat(data.tuition_fee || '0').toFixed(2)} - 
                                                Scholarship: ${parseFloat(data.scholarship_amount || '0').toFixed(2)}
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {/* Submission Deadline */}
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

                                {/* Admission Deadline */}
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

                                {/* Notes */}
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

                            {/* Important Information */}
                            <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div className="flex items-start gap-3">
                                    <AlertCircle className="h-5 w-5 text-blue-600 mt-0.5" />
                                    <div>
                                        <h4 className="font-semibold text-blue-900">Important Information</h4>
                                        <ul className="text-sm text-blue-800 mt-2 space-y-1">
                                            <li>• An application fee payment of $100 will be automatically created</li>
                                            <li>• The application will start in "Draft" status</li>
                                            <li>• Visa process will be marked as "Not Started"</li>
                                            <li>• You can update all details after creation</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {/* Actions */}
                            <div className="flex justify-end gap-2">
                                <Button variant="outline" type="button" asChild>
                                    <Link href="/student-applications">
                                        Cancel
                                    </Link>
                                </Button>
                                <Button type="submit" disabled={processing}>
                                    <Save className="h-4 w-4 mr-2" />
                                    {processing ? 'Creating...' : 'Create Application'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
