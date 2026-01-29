import { useToast } from '@/hooks/use-toast';
import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { useEffect, useRef } from 'react';

export function useFlashToast() {
    const { flash } = usePage<SharedData>().props;
    const { toast } = useToast();
    const last = useRef<string>('');

    useEffect(() => {
        const success = flash?.success ?? undefined;
        const error = flash?.error ?? undefined;
        const info = flash?.info ?? undefined;
        const warning = flash?.warning ?? undefined;

        const key = [success, error, info, warning].filter(Boolean).join('|');
        if (!key || key === last.current) {
            return;
        }

        last.current = key;

        if (success) {
            toast({ variant: 'success', title: 'Success', description: success });
        }
        if (error) {
            toast({ variant: 'destructive', title: 'Error', description: error });
        }
        if (warning) {
            toast({ variant: 'warning', title: 'Warning', description: warning });
        }
        if (info) {
            toast({ title: 'Info', description: info });
        }
    }, [flash, toast]);
}
