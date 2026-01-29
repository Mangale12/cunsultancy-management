import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import Heading from '@/components/heading';
import { ArrowLeft, Edit, Eye, Mail, Phone, MapPin, Calendar, User, FileText, CreditCard } from 'lucide-react';

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
    applications: Array<{
        id: number;
        university: {
            name: string;
        };
        course: {
            name: string;
        };
        application_status: string;
        application_date: string;
        tuition_fee: number;
    }>;
    documents: Array<{
        id: number;
        document_type: {
            name: string;
        };
        file_name: string;
        uploaded_at: string;
    }>;
    payments: Array<{
        id: number;
        payment_type: string;
        amount: number;
        status: string;
        due_date: string;
        paid_amount: number;
    }>;
}

interface Props {
    student: Student;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Students',
        href: '/students',
    },
    {
        title: 'Student Details',
        href: '#',
    },
];

const getStatusBadge = (status: string) => {
    const statusConfig = {
        active: { variant: 'default', label: 'Active' },
        inactive: { variant: 'secondary', label: 'Inactive' },
        pending: { variant: 'outline', label: 'Pending' },
        graduated: { variant: 'default', label: 'Graduated' },
        suspended: { variant: 'destructive', label: 'Suspended' },
    };
    
    const config = statusConfig[status as keyof typeof statusConfig] || { variant: 'secondary', label: status };
    
    return (
        <Badge variant={config.variant as any}>
            {config.label}
        </Badge>
    );
};

const getPaymentStatusBadge = (status: string) => {
    const statusConfig = {
        pending: { variant: 'secondary', label: 'Pending' },
        partial: { variant: 'default', label: 'Partial' },
        paid: { variant: 'default', label: 'Paid' },
        overdue: { variant: 'destructive', label: 'Overdue' },
        cancelled: { variant: 'outline', label: 'Cancelled' },
    };
    
    const config = statusConfig[status as keyof typeof statusConfig] || { variant: 'secondary', label: status };
    
    return (
        <Badge variant={config.variant as any}>
            {config.label}
        </Badge>
    );
};

export default function StudentShow({ student }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${student.first_name} ${student.last_name}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-6">
                <Card>
                    <CardHeader className="border-b pb-3">
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <Heading title={`${student.first_name} ${student.last_name}`} />
                            <div className="flex gap-2">
                                <Button size="sm" variant="outline" asChild>
                                    <Link href={`/students/${student.id}/edit`}>
                                        <Edit className="h-4 w-4 mr-2" />
                                        Edit
                                    </Link>
                                </Button>
                                <Button size="sm" variant="outline" asChild>
                                    <Link href="/students">
                                        <ArrowLeft className="h-4 w-4 mr-2" />
                                        Back
                                    </Link>
                                </Button>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
                            {/* Student Information */}
                            <div className="space-y-4">
                                <div className="flex items-center gap-3">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                                        <User className="h-6 w-6 text-primary" />
                                    </div>
                                    <div>
                                        <h3 className="text-lg font-semibold">Student Information</h3>
                                        <p className="text-sm text-muted-foreground">Personal details</p>
                                    </div>
                                </div>
                                
                                <div className="space-y-3">
                                    <div>
                                        <div className="text-sm text-muted-foreground">Full Name</div>
                                        <div className="font-medium">{student.first_name} {student.last_name}</div>
                                    </div>
                                    <div>
                                        <div className="text-sm text-muted-foreground">Email</div>
                                        <div className="font-medium flex items-center gap-2">
                                            <Mail className="h-4 w-4" />
                                            {student.email}
                                        </div>
                                    </div>
                                    <div>
                                        <div className="text-sm text-muted-foreground">Phone</div>
                                        <div className="font-medium flex items-center gap-2">
                                            <Phone className="h-4 w-4" />
                                            {student.phone || 'Not provided'}
                                        </div>
                                    </div>
                                    <div>
                                        <div className="text-sm text-muted-foreground">Date of Birth</div>
                                        <div className="font-medium flex items-center gap-2">
                                            <Calendar className="h-4 w-4" />
                                            {new Date(student.date_of_birth).toLocaleDateString()}
                                        </div>
                                    </div>
                                    <div>
                                        <div className="text-sm text-muted-foreground">Status</div>
                                        <div>{getStatusBadge(student.status)}</div>
                                    </div>
                                </div>
                            </div>

                            {/* Location Information */}
                            <div className="space-y-4">
                                <div className="flex items-center gap-3">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                                        <MapPin className="h-6 w-6 text-primary" />
                                    </div>
                                    <div>
                                        <h3 className="text-lg font-semibold">Location</h3>
                                        <p className="text-sm text-muted-foreground">Address details</p>
                                    </div>
                                </div>
                                
                                <div className="space-y-3">
                                    <div>
                                        <div className="text-sm text-muted-foreground">Address</div>
                                        <div className="font-medium">{student.address || 'Not provided'}</div>
                                    </div>
                                    <div>
                                        <div className="text-sm text-muted-foreground">Country</div>
                                        <div className="font-medium">{student.country?.name || 'Not specified'}</div>
                                    </div>
                                    <div>
                                        <div className="text-sm text-muted-foreground">State</div>
                                        <div className="font-medium">{student.state?.name || 'Not specified'}</div>
                                    </div>
                                </div>
                            </div>

                            {/* Academic Information */}
                            <div className="space-y-4">
                                <div className="flex items-center gap-3">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                                        <FileText className="h-6 w-6 text-primary" />
                                    </div>
                                    <div>
                                        <h3 className="text-lg font-semibold">Academic</h3>
                                        <p className="text-sm text-muted-foreground">Study details</p>
                                    </div>
                                </div>
                                
                                <div className="space-y-3">
                                    <div>
                                        <div className="text-sm text-muted-foreground">Branch</div>
                                        <div className="font-medium">{student.branch.name}</div>
                                    </div>
                                    <div>
                                        <div className="text-sm text-muted-foreground">Agent</div>
                                        <div className="font-medium">{student.agent?.name || 'No agent assigned'}</div>
                                    </div>
                                    <div>
                                        <div className="text-sm text-muted-foreground">Course</div>
                                        <div className="font-medium">{student.course?.name || 'No course selected'}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Applications */}
                        {student.applications.length > 0 && (
                            <div>
                                <h3 className="text-lg font-semibold mb-4">Applications</h3>
                                <div className="rounded-md border">
                                    <table className="w-full">
                                        <thead>
                                            <tr className="border-b bg-muted/50">
                                                <th className="px-4 py-2 text-left text-sm font-medium">University</th>
                                                <th className="px-4 py-2 text-left text-sm font-medium">Course</th>
                                                <th className="px-4 py-2 text-left text-sm font-medium">Status</th>
                                                <th className="px-4 py-2 text-left text-sm font-medium">Date</th>
                                                <th className="px-4 py-2 text-left text-sm font-medium">Tuition Fee</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {student.applications.map((application) => (
                                                <tr key={application.id} className="border-b">
                                                    <td className="px-4 py-2 text-sm">{application.university.name}</td>
                                                    <td className="px-4 py-2 text-sm">{application.course.name}</td>
                                                    <td className="px-4 py-2 text-sm">
                                                        <Badge variant="outline">{application.application_status}</Badge>
                                                    </td>
                                                    <td className="px-4 py-2 text-sm">
                                                        {new Date(application.application_date).toLocaleDateString()}
                                                    </td>
                                                    <td className="px-4 py-2 text-sm">${application.tuition_fee.toLocaleString()}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        )}

                        {/* Documents */}
                        {student.documents.length > 0 && (
                            <div>
                                <h3 className="text-lg font-semibold mb-4">Documents</h3>
                                <div className="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
                                    {student.documents.map((document) => (
                                        <div key={document.id} className="flex items-center gap-3 rounded-md border p-3">
                                            <FileText className="h-8 w-8 text-muted-foreground" />
                                            <div className="flex-1">
                                                <div className="font-medium text-sm">{document.document_type.name}</div>
                                                <div className="text-xs text-muted-foreground">
                                                    {new Date(document.uploaded_at).toLocaleDateString()}
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                        {/* Payments */}
                        {student.payments.length > 0 && (
                            <div>
                                <h3 className="text-lg font-semibold mb-4">Payments</h3>
                                <div className="rounded-md border">
                                    <table className="w-full">
                                        <thead>
                                            <tr className="border-b bg-muted/50">
                                                <th className="px-4 py-2 text-left text-sm font-medium">Type</th>
                                                <th className="px-4 py-2 text-left text-sm font-medium">Amount</th>
                                                <th className="px-4 py-2 text-left text-sm font-medium">Paid</th>
                                                <th className="px-4 py-2 text-left text-sm font-medium">Status</th>
                                                <th className="px-4 py-2 text-left text-sm font-medium">Due Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {student.payments.map((payment) => (
                                                <tr key={payment.id} className="border-b">
                                                    <td className="px-4 py-2 text-sm">{payment.payment_type}</td>
                                                    <td className="px-4 py-2 text-sm">${payment.amount.toLocaleString()}</td>
                                                    <td className="px-4 py-2 text-sm">${payment.paid_amount.toLocaleString()}</td>
                                                    <td className="px-4 py-2 text-sm">
                                                        {getPaymentStatusBadge(payment.status)}
                                                    </td>
                                                    <td className="px-4 py-2 text-sm">
                                                        {new Date(payment.due_date).toLocaleDateString()}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
