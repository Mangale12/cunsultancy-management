import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Pencil, ArrowLeft, BookOpen, GraduationCap, Clock, DollarSign, Users, Calendar } from 'lucide-react';

type Course = {
    id: number;
    name: string;
    level: string;
    duration_months: number;
    tuition_fee: number;
    currency: string;
    image_path?: string;
    created_at: string;
    updated_at: string;
    university?: {
        id: number;
        name: string;
    };
    students?: Array<{
        id: number;
        name: string;
        email: string;
    }>;
};

export default function CourseShow({ course }: { course: Course }) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Courses',
            href: '/courses',
        },
        {
            title: course.name,
            href: `/courses/${course.id}`,
        },
    ];

    const formatCurrency = (amount: number, currency: string) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency || 'USD',
        }).format(amount);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={course.name} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="View Course" />
                            <div className="flex gap-2">
                                <Button variant="outline" asChild>
                                    <Link href={`/courses/${course.id}/edit`}>
                                        <Pencil className="h-4 w-4 mr-2" />
                                        Edit
                                    </Link>
                                </Button>
                                <Button asChild>
                                    <Link href="/courses">
                                        <ArrowLeft className="h-4 w-4 mr-2" />
                                        Back
                                    </Link>
                                </Button>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent className="pt-6">
                        <div className="grid gap-6 md:grid-cols-2">
                            <div className="space-y-6">
                                <div className="flex justify-center">
                                    {course.image_path ? (
                                        <img
                                            src={course.image_path}
                                            alt={course.name}
                                            className="h-32 w-32 rounded object-cover"
                                        />
                                    ) : (
                                        <div className="h-32 w-32 rounded bg-gray-200 flex items-center justify-center">
                                            <BookOpen className="h-16 w-16 text-gray-500" />
                                        </div>
                                    )}
                                </div>
                                
                                <div className="text-center">
                                    <h2 className="text-2xl font-bold">{course.name}</h2>
                                    <Badge variant="outline" className="mt-2">
                                        {course.level}
                                    </Badge>
                                </div>
                            </div>

                            <div className="space-y-6">
                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Course Details</h3>
                                    <div className="space-y-3">
                                        <div className="flex items-center gap-3">
                                            <Clock className="h-4 w-4 text-muted-foreground" />
                                            <span>{course.duration_months} months</span>
                                        </div>
                                        <div className="flex items-center gap-3">
                                            <DollarSign className="h-4 w-4 text-muted-foreground" />
                                            <span className="font-medium">
                                                {formatCurrency(course.tuition_fee, course.currency)}
                                            </span>
                                        </div>
                                        <div className="flex items-center gap-3">
                                            <Calendar className="h-4 w-4 text-muted-foreground" />
                                            <span>
                                                Monthly: {formatCurrency(course.tuition_fee / course.duration_months, course.currency)}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Assignment</h3>
                                    <div className="space-y-3">
                                        {course.university && (
                                            <div className="flex items-center gap-3">
                                                <GraduationCap className="h-4 w-4 text-muted-foreground" />
                                                <Badge variant="secondary">
                                                    {course.university.name}
                                                </Badge>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {course.students && course.students.length > 0 && (
                            <div className="mt-8 pt-6 border-t">
                                <h3 className="text-lg font-semibold mb-4">Enrolled Students</h3>
                                <div className="space-y-3">
                                    {course.students.map((student) => (
                                        <div key={student.id} className="flex items-center justify-between p-3 border rounded-lg">
                                            <div className="flex items-center gap-3">
                                                <div className="h-8 w-8 rounded bg-blue-100 flex items-center justify-center">
                                                    <Users className="h-4 w-4 text-blue-600" />
                                                </div>
                                                <div>
                                                    <p className="font-medium">{student.name}</p>
                                                    <p className="text-sm text-muted-foreground">{student.email}</p>
                                                </div>
                                            </div>
                                            <div className="flex gap-2">
                                                <Button size="sm" variant="outline" asChild>
                                                    <Link href={`/students/${student.id}`}>
                                                        View
                                                    </Link>
                                                </Button>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                        <div className="mt-8 pt-6 border-t">
                            <div className="grid gap-6 md:grid-cols-2">
                                <div>
                                    <h3 className="text-lg font-semibold mb-4">System Information</h3>
                                    <div className="space-y-2 text-sm">
                                        <div className="flex justify-between">
                                            <span className="text-muted-foreground">Course ID:</span>
                                            <span className="font-mono">#{course.id}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-muted-foreground">Created:</span>
                                            <span>{new Date(course.created_at).toLocaleDateString()}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-muted-foreground">Last Updated:</span>
                                            <span>{new Date(course.updated_at).toLocaleDateString()}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Quick Actions</h3>
                                    <div className="space-y-2">
                                        <Button className="w-full" variant="outline" asChild>
                                            <Link href={`/courses/${course.id}/edit`}>
                                                <Pencil className="h-4 w-4 mr-2" />
                                                Edit Course
                                            </Link>
                                        </Button>
                                        <Button className="w-full" variant="outline" asChild>
                                            <Link href="/students/create">
                                                <Users className="h-4 w-4 mr-2" />
                                                Add Student
                                            </Link>
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
