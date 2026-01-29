import { SidebarProvider } from '@/components/ui/sidebar';
import { Toaster } from '@/components/ui/toaster';
import { useFlashToast } from '@/hooks/use-flash-toast';
import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import type { ReactNode } from 'react';

type Props = {
    children: ReactNode;
    variant?: 'header' | 'sidebar';
};

export function AppShell({ children, variant = 'header' }: Props) {
    const isOpen = usePage<SharedData>().props.sidebarOpen;
    useFlashToast();

    const isClient = typeof window !== 'undefined';

    if (variant === 'header') {
        return (
            <div className="flex min-h-screen w-full flex-col">
                {children}
                {isClient ? <Toaster /> : null}
            </div>
        );
    }

    return (
        <SidebarProvider defaultOpen={isOpen}>
            {children}
            {isClient ? <Toaster /> : null}
        </SidebarProvider>
    );
}
