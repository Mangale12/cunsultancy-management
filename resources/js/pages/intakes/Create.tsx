import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { AlertCircle, ArrowLeft } from 'lucide-react';

// Define the shape of our page props
interface PageProps {
    flash: {
        error?: string;
        success?: string;
    };
}

// Define the form data type
interface FormData {
    name: string;
}

export default function IntakeCreate() {
    const { data, setData, post, processing, errors, reset } = useForm<FormData>({
        name: '',
    });

    const { flash } = usePage<PageProps>().props;

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('intakes.store'), {
            onSuccess: () => reset(),
        });
    };

    return (
        <>
            <Head title="Create Intake" />
            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-2xl font-bold tracking-tight">Create New Intake</h2>
                        <p className="text-muted-foreground">
                            Add a new intake to the system
                        </p>
                    </div>
                    <Link href="/intakes">
                        <Button variant="outline">
                            <ArrowLeft className="mr-2 h-4 w-4" /> Back to List
                        </Button>
                    </Link>
                </div>

                {/* Error Message */}
                {flash?.error && (
                    <Alert variant="destructive" className="mb-4">
                        <AlertCircle className="h-4 w-4" />
                        <AlertDescription>
                            {flash.error}
                        </AlertDescription>
                    </Alert>
                )}

                <Card>
                    <form onSubmit={handleSubmit}>
                        <CardHeader>
                            <CardTitle>Intake Information</CardTitle>
                            <CardDescription>
                                Enter the details for the new intake
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="space-y-2">
                                <Label htmlFor="name">Name</Label>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    placeholder="e.g., January 2024"
                                    required
                                    disabled={processing}
                                    className={errors.name ? 'border-red-500' : ''}
                                />
                                {errors.name && (
                                    <p className="text-sm text-red-500">{errors.name}</p>
                                )}
                            </div>
                        </CardContent>
                        <div className="border-t px-6 py-4 flex justify-end space-x-2">
                            <Link href="/intakes">
                                <Button type="button" variant="outline" disabled={processing}>
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" disabled={processing}>
                                {processing ? 'Creating...' : 'Create Intake'}
                            </Button>
                        </div>
                    </form>
                </Card>
            </div>
        </>
    );
}
