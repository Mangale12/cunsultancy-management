import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';

export type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

export function Pagination({
    links,
    className,
}: {
    links: PaginationLink[];
    className?: string;
}) {
    if (!links || links.length <= 3) {
        return null;
    }

    return (
        <nav className={cn('flex flex-wrap gap-2', className)}>
            {links.map((link, index) => {
                const label = link.label
                    .replace('&laquo;', '«')
                    .replace('&raquo;', '»');

                return (
                    <Button
                        key={`${index}-${label}`}
                        size="sm"
                        variant={link.active ? 'default' : 'outline'}
                        asChild
                        disabled={!link.url}
                    >
                        <Link href={link.url || '#'} preserveScroll>
                            <span dangerouslySetInnerHTML={{ __html: label }} />
                        </Link>
                    </Button>
                );
            })}
        </nav>
    );
}
