import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import Heading from '@/components/heading';
import { ArrowLeft, Edit, MapPin } from 'lucide-react';

interface State {
    id: number;
    name: string;
    country: {
        id: number;
        name: string;
    };
    created_at: string;
    updated_at: string;
}

interface Props {
    state: State;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'ERP', href: '/erp' },
    { title: 'States', href: '/states' },
    { title: 'State Details', href: '#' },
];

export default function StatesShow({ state }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="State Details" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title="View State" />
                            <Button asChild>
                                <Link href="/states">
                                    <ArrowLeft className="h-4 w-4 mr-2" />
                                    Back
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div className="grid gap-6 md:grid-cols-2">
                            <div className="space-y-6">
                                <div className="flex justify-center">
                                    <div className="h-32 w-32 rounded-xl bg-muted border-2 flex items-center justify-center">
                                        <MapPin className="h-16 w-16 text-muted-foreground" />
                                    </div>
                                </div>
                                
                                <div className="space-y-4">
                                    <div>
                                        <h3 className="text-lg font-semibold">State Information</h3>
                                        <div className="mt-4">
                                            <Badge variant="outline" className="text-sm">
                                                {state.country.name}
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div className="space-y-6">
                                <div className="space-y-4">
                                    <h3 className="text-lg font-semibold">Details</h3>
                                    <div className="grid gap-4">
                                        <div>
                                            <div className="text-sm text-muted-foreground">State Name</div>
                                            <div className="font-medium text-lg">{state.name}</div>
                                        </div>
                                        <div>
                                            <div className="text-sm text-muted-foreground">Country</div>
                                            <div className="font-medium">{state.country.name}</div>
                                        </div>
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
