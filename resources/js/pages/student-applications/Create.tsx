import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { AlertCircle, ArrowLeft } from 'lucide-react';

interface PageProps {
    flash: {
        error?: string;
        success?: string;
    };
    student: {
        id: number;
        name: string;
    };
    universities: Array<{ id: number; name: string }>;
    courses: Array<{ id: number; name: string }>;
    intakes: Array<{ id: number; name: string }>;
}

export default function ApplicationCreate() {
    const { student, universities, courses, intakes } = usePage<PageProps>().props;
    
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
        post(route('student-applications.store'));
    };

    return (
        <>
            <Head title="New Application" />
            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-2xl font-bold tracking-tight">New Application</h2>
                        <p className="text-muted-foreground">
                            Create a new application for {student.name}
                        </p>
                    </div>
                    <Link href={route('students.show', student.id)}>
                        <Button variant="outline">
                            <ArrowLeft className="mr-2 h-4 w-4" /> Back to Student
                        </Button>
                    </Link>
                </div>

                {usePage<PageProps>().props.flash?.error && (
                    <Alert variant="destructive" className="mb-4">
                        <AlertCircle className="h-4 w-4" />
                        <AlertDescription>
                            {usePage<PageProps>().props.flash.error}
                        </AlertDescription>
                    </Alert>
                )}

                <Card>
                    <form onSubmit={handleSubmit}>
                        <CardHeader>
                            <CardTitle>Application Details</CardTitle>
                            <CardDescription>
                                Fill in the application details for {student.name}
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="university_id">University</Label>
                                    <Select
                                        onValueChange={(value) => setData('university_id', value)}
                                        value={data.university_id}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select a university" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {universities.map((university) => (
                                                <SelectItem key={university.id} value={university.id.toString()}>
                                                    {university.name}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    {errors.university_id && (
                                        <p className="text-sm text-red-500">{errors.university_id}</p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="course_id">Course</Label>
                                    <Select
                                        onValueChange={(value) => setData('course_id', value)}
                                        value={data.course_id}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select a course" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {courses.map((course) => (
                                                <SelectItem key={course.id} value={course.id.toString()}>
                                                    {course.name}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    {errors.course_id && (
                                        <p className="text-sm text-red-500">{errors.course_id}</p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="intake_id">Intake</Label>
                                    <Select
                                        onValueChange={(value) => setData('intake_id', value)}
                                        value={data.intake_id}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select an intake" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {intakes.map((intake) => (
                                                <SelectItem key={intake.id} value={intake.id.toString()}>
                                                    {intake.name}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    {errors.intake_id && (
                                        <p className="text-sm text-red-500">{errors.intake_id}</p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="status">Status</Label>
                                    <Select
                                        onValueChange={(value) => setData('status', value)}
                                        value={data.status}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select status" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="pending">Pending</SelectItem>
                                            <SelectItem value="in_review">In Review</SelectItem>
                                            <SelectItem value="accepted">Accepted</SelectItem>
                                            <SelectItem value="rejected">Rejected</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="notes">Notes</Label>
                                <Input
                                    id="notes"
                                    as="textarea"
                                    rows={3}
                                    value={data.notes}
                                    onChange={(e) => setData('notes', e.target.value)}
                                    placeholder="Additional notes about the application"
                                />
                            </div>
                        </CardContent>
                        <div className="border-t px-6 py-4 flex justify-end space-x-2">
                            <Link href={route('students.show', student.id)}>
                                <Button type="button" variant="outline" disabled={processing}>
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" disabled={processing}>
                                {processing ? 'Creating...' : 'Create Application'}
                            </Button>
                        </div>
                    </form>
                </Card>
            </div>
        </>
    );
}
