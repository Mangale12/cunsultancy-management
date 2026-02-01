import { Head, Link, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { DataTable } from '@/components/ui/data-table';
import { Plus } from 'lucide-react';
import { columns } from './columns';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { CheckCircle } from 'lucide-react';
import { AlertCircle } from 'lucide-react';

export default function IntakeIndex({ intakes }) {
    const { flash } = usePage().props;
    
    return (
        <>
            <Head title="Intakes" />
            <div className="space-y-6">
                <div className="flex justify-between items-center">
                    <div>
                        <h2 className="text-2xl font-bold tracking-tight">Intakes</h2>
                        <p className="text-muted-foreground">
                            Manage your application intakes
                        </p>
                    </div>
                    <Link href="/intakes/create">
                        <Button>
                            <Plus className="mr-2 h-4 w-4" /> Add New
                        </Button>
                    </Link>
                </div>

                {/* Success/Error Messages */}
                {flash.success && (
                    <Alert className="bg-green-50 border-green-200">
                        <CheckCircle className="h-4 w-4 text-green-600" />
                        <AlertTitle>Success</AlertTitle>
                        <AlertDescription className="text-green-700">
                            {flash.success}
                        </AlertDescription>
                    </Alert>
                )}
                
                {flash.error && (
                    <Alert variant="destructive">
                        <AlertCircle className="h-4 w-4" />
                        <AlertTitle>Error</AlertTitle>
                        <AlertDescription>
                            {flash.error}
                        </AlertDescription>
                    </Alert>
                )}

                <Card>
                    <CardHeader>
                        <CardTitle>Intakes</CardTitle>
                        <CardDescription>
                            List of all available intakes
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <DataTable 
                            columns={columns} 
                            data={intakes} 
                            searchKey="name" 
                        />
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
