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
    intake: {
        id: number;
        name: string;
    };
}

// Define the form data type
interface FormData {
    name: string;
}

export default function IntakeEdit() {
    const { intake } = usePage<PageProps>().props;
    
    const { data, setData, put, processing, errors, reset } = useForm<FormData>({
        name: intake.name,
    });

    const { flash } = usePage<PageProps>().props;

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(route('intakes.update', intake.id));
    };

    return (
        <>
            <Head title={`Edit Intake - ${intake.name}`} />
            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-2xl font-bold tracking-tight">Edit Intake</h2>
                        <p className="text-muted-foreground">
                            Update the intake details
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
                                Update the details for this intake
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
                                {processing ? 'Updating...' : 'Update Intake'}
                            </Button>
                        </div>
                    </form>
                </Card>
                    </Card>
                </form>
            </div>
        </>
    );
}
