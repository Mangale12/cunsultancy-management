import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { Save, ArrowLeft, XCircle, BookOpen, GraduationCap, Clock, DollarSign } from 'lucide-react';
import type { FormEvent } from 'react';

type Course = {
    id: number;
    name: string;
    level: string;
    duration_months: number;
    tuition_fee: number;
    currency: string;
    image_path?: string;
    university_id?: number;
};

type University = {
    id: number;
    name: string;
};

export default function CourseEdit({
    course,
    universities,
}: {
    course: Course;
    universities: University[];
}) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Courses',
            href: '/courses',
        },
        {
            title: course.name,
            href: `/courses/${course.id}`,
        },
        {
            title: 'Edit',
            href: `/courses/${course.id}/edit`,
        },
    ];

    const form = useForm({
        name: course.name,
        level: course.level,
        duration_months: course.duration_months?.toString() || '',
        tuition_fee: course.tuition_fee?.toString() || '',
        currency: course.currency,
        university_id: course.university_id?.toString() || '',
        image_path: course.image_path || '',
    });

    const submit = (e: FormEvent) => {
        e.preventDefault();
        
        // Update university_id to empty string if it's "select" before submitting
        if (form.data.university_id === 'select') {
            form.setData('university_id', '');
        }
        
        // Update level to empty string if it's "select" before submitting
        if (form.data.level === 'select') {
            form.setData('level', '');
        }

        form.put(`/courses/${course.id}`);
    };

    const courseLevels = ['Bachelor', 'Master', 'PhD', 'Diploma', 'Certificate', 'Associate'];
    const currencies = ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY', 'CNY', 'INR'];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit ${course.name}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="Edit Course" />
                            <Button asChild>
                                <Link href="/courses">
                                    <ArrowLeft className="h-4 w-4 mr-2" />
                                    Back
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={submit} className="space-y-6">
                            {/* Basic Information */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">Basic Information</h3>
                                <div className="grid gap-6 md:grid-cols-2">
                                    <div className="space-y-2">
                                        <Label htmlFor="name">Course Name</Label>
                                        <Input
                                            id="name"
                                            value={form.data.name}
                                            onChange={(e) => form.setData('name', e.target.value)}
                                            required
                                            placeholder="Enter course name"
                                        />
                                        <InputError message={form.errors.name} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="level">Course Level</Label>
                                        <Select value={form.data.level} onValueChange={(value) => form.setData('level', value)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select course level" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="select">Select a level</SelectItem>
                                                {courseLevels.map((level) => (
                                                    <SelectItem key={level} value={level}>
                                                        {level}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={form.errors.level} />
                                    </div>

                                    <div className="space-y-2 md:col-span-2">
                                        <Label htmlFor="image_path">Course Image URL</Label>
                                        <Input
                                            id="image_path"
                                            type="url"
                                            value={form.data.image_path}
                                            onChange={(e) => form.setData('image_path', e.target.value)}
                                            placeholder="https://example.com/course-image.jpg"
                                        />
                                        <InputError message={form.errors.image_path} />
                                    </div>
                                </div>
                            </div>

                            {/* Course Details */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">Course Details</h3>
                                <div className="grid gap-6 md:grid-cols-3">
                                    <div className="space-y-2">
                                        <Label htmlFor="duration_months">Duration (Months)</Label>
                                        <Input
                                            id="duration_months"
                                            type="number"
                                            value={form.data.duration_months}
                                            onChange={(e) => form.setData('duration_months', e.target.value)}
                                            required
                                            placeholder="12"
                                            min="1"
                                        />
                                        <InputError message={form.errors.duration_months} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="tuition_fee">Tuition Fee</Label>
                                        <Input
                                            id="tuition_fee"
                                            type="number"
                                            step="0.01"
                                            value={form.data.tuition_fee}
                                            onChange={(e) => form.setData('tuition_fee', e.target.value)}
                                            required
                                            placeholder="10000.00"
                                            min="0"
                                        />
                                        <InputError message={form.errors.tuition_fee} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="currency">Currency</Label>
                                        <Select value={form.data.currency} onValueChange={(value) => form.setData('currency', value)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select currency" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                {currencies.map((currency) => (
                                                    <SelectItem key={currency} value={currency}>
                                                        {currency}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={form.errors.currency} />
                                    </div>
                                </div>
                            </div>

                            {/* Assignment */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">Assignment</h3>
                                <div className="grid gap-6 md:grid-cols-2">
                                    <div className="space-y-2">
                                        <Label htmlFor="university_id">University</Label>
                                        <Select value={form.data.university_id} onValueChange={(value) => form.setData('university_id', value)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a university" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="select">Select a university</SelectItem>
                                                {universities.map((university) => (
                                                    <SelectItem key={university.id} value={university.id.toString()}>
                                                        {university.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={form.errors.university_id} />
                                    </div>
                                </div>
                            </div>

                            <div className="flex justify-end gap-4">
                                <Button type="button" variant="outline" asChild>
                                    <Link href="/courses">
                                        <XCircle className="h-4 w-4 mr-2" />
                                        Cancel
                                    </Link>
                                </Button>
                                <Button type="submit" disabled={form.processing}>
                                    <Save className="h-4 w-4 mr-2" />
                                    {form.processing ? 'Updating...' : 'Update Course'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
