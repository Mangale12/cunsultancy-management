import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Pencil, ArrowLeft, GraduationCap, Globe, MapPin, BookOpen, Calendar } from 'lucide-react';

type University = {
    id: number;
    name: string;
    code: string;
    image_path?: string;
    created_at: string;
    updated_at: string;
    country?: {
        id: number;
        name: string;
    };
    state?: {
        id: number;
        name: string;
    };
    courses?: Array<{
        id: number;
        name: string;
        level: string;
    }>;
};

export default function UniversityShow({ university }: { university: University }) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Universities',
            href: '/universities',
        },
        {
            title: university.name,
            href: `/universities/${university.id}`,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={university.name} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="View University" />
                            <div className="flex gap-2">
                                <Button variant="outline" asChild>
                                    <Link href={`/universities/${university.id}/edit`}>
                                        <Pencil className="h-4 w-4 mr-2" />
                                        Edit
                                    </Link>
                                </Button>
                                <Button asChild>
                                    <Link href="/universities">
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
                                    {university.image_path ? (
                                        <img
                                            src={university.image_path}
                                            alt={university.name}
                                            className="h-32 w-32 rounded object-cover"
                                        />
                                    ) : (
                                        <div className="h-32 w-32 rounded bg-gray-200 flex items-center justify-center">
                                            <GraduationCap className="h-16 w-16 text-gray-500" />
                                        </div>
                                    )}
                                </div>
                                
                                <div className="text-center">
                                    <h2 className="text-2xl font-bold">{university.name}</h2>
                                    <Badge variant="outline" className="mt-2">
                                        {university.code}
                                    </Badge>
                                </div>
                            </div>

                            <div className="space-y-6">
                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Location Information</h3>
                                    <div className="space-y-3">
                                        {university.country && (
                                            <div className="flex items-center gap-3">
                                                <Globe className="h-4 w-4 text-muted-foreground" />
                                                <Badge variant="secondary">
                                                    {university.country.name}
                                                </Badge>
                                            </div>
                                        )}
                                        {university.state && (
                                            <div className="flex items-center gap-3">
                                                <MapPin className="h-4 w-4 text-muted-foreground" />
                                                <Badge variant="outline">
                                                    {university.state.name}
                                                </Badge>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {university.courses && university.courses.length > 0 && (
                            <div className="mt-8 pt-6 border-t">
                                <h3 className="text-lg font-semibold mb-4">Courses</h3>
                                <div className="space-y-3">
                                    {university.courses.map((course) => (
                                        <div key={course.id} className="flex items-center justify-between p-3 border rounded-lg">
                                            <div className="flex items-center gap-3">
                                                <div className="h-8 w-8 rounded bg-blue-100 flex items-center justify-center">
                                                    <BookOpen className="h-4 w-4 text-blue-600" />
                                                </div>
                                                <div>
                                                    <p className="font-medium">{course.name}</p>
                                                    <p className="text-sm text-muted-foreground">{course.level}</p>
                                                </div>
                                            </div>
                                            <div className="flex gap-2">
                                                <Button size="sm" variant="outline" asChild>
                                                    <Link href={`/courses/${course.id}`}>
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
                                            <span className="text-muted-foreground">University ID:</span>
                                            <span className="font-mono">#{university.id}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-muted-foreground">Created:</span>
                                            <span>{new Date(university.created_at).toLocaleDateString()}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-muted-foreground">Last Updated:</span>
                                            <span>{new Date(university.updated_at).toLocaleDateString()}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 className="text-lg font-semibold mb-4">Quick Actions</h3>
                                    <div className="space-y-2">
                                        <Button className="w-full" variant="outline" asChild>
                                            <Link href={`/universities/${university.id}/edit`}>
                                                <Pencil className="h-4 w-4 mr-2" />
                                                Edit University
                                            </Link>
                                        </Button>
                                        <Button className="w-full" variant="outline" asChild>
                                            <Link href="/courses/create">
                                                <BookOpen className="h-4 w-4 mr-2" />
                                                Add Course
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
