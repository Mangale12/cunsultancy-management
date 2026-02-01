import { ColumnDef } from '@tanstack/react-table';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { ArrowUpDown, Pencil, Trash2 } from 'lucide-react';
import { Link } from '@inertiajs/react';

export const columns: ColumnDef<any>[] = [
    {
        id: 'select',
        header: ({ table }) => (
            <Checkbox
                checked={table.getIsAllPageRowsSelected()}
                onCheckedChange={(value) => table.toggleAllPageRowsSelected(!!value)}
                aria-label="Select all"
            />
        ),
        cell: ({ row }) => (
            <Checkbox
                checked={row.getIsSelected()}
                onCheckedChange={(value) => row.toggleSelected(!!value)}
                aria-label="Select row"
            />
        ),
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: 'name',
        header: ({ column }) => {
            return (
                <Button
                    variant="ghost"
                    onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}
                >
                    Name
                    <ArrowUpDown className="ml-2 h-4 w-4" />
                </Button>
            );
        },
    },
    {
        accessorKey: 'created_at',
        header: 'Created At',
        cell: ({ row }) => {
            return new Date(row.getValue('created_at')).toLocaleDateString();
        },
    },
    {
        id: 'actions',
        cell: ({ row }) => {
            const intake = row.original;
            return (
                <div className="flex space-x-2">
                    <Link href={`/intakes/${intake.id}/edit`}>
                        <Button variant="outline" size="sm">
                            <Pencil className="h-4 w-4" />
                        </Button>
                    </Link>
                    <Link
                        href={`/intakes/${intake.id}`}
                        method="delete"
                        as="button"
                        onBefore={() => {
                            return window.confirm('Are you sure you want to delete this intake?');
                        }}
                    >
                        <Button variant="outline" size="sm" className="text-red-500 hover:text-red-700">
                            <Trash2 className="h-4 w-4" />
                        </Button>
                    </Link>
                </div>
            );
        },
    },
];
