"use client"

import * as React from "react"
import { cn } from "@/lib/utils"
import { Button } from "@/components/ui/button"

interface AlertDialogProps {
    open: boolean;
    onOpenChange: (open: boolean) => void;
    children: React.ReactNode;
}

const AlertDialog: React.FC<AlertDialogProps> = ({ open, onOpenChange, children }) => {
    return (
        <>
            {children}
            {open && (
                <div className="fixed inset-0 z-50 flex items-center justify-center">
                    <div 
                        className="fixed inset-0 bg-black/50" 
                        onClick={() => onOpenChange(false)}
                    />
                    <div className="fixed left-[50%] top-[50%] z-50 grid w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg duration-200 sm:rounded-lg">
                        {/* Content will be rendered here */}
                    </div>
                </div>
            )}
        </>
    )
}

interface AlertDialogContentProps {
    children: React.ReactNode;
    className?: string;
}

const AlertDialogContent: React.FC<AlertDialogContentProps> = ({ children, className }) => {
    return <div className={cn("", className)}>{children}</div>
}

interface AlertDialogHeaderProps {
    children: React.ReactNode;
    className?: string;
}

const AlertDialogHeader: React.FC<AlertDialogHeaderProps> = ({ children, className }) => {
    return <div className={cn("flex flex-col space-y-2 text-center sm:text-left", className)}>{children}</div>
}

interface AlertDialogFooterProps {
    children: React.ReactNode;
    className?: string;
}

const AlertDialogFooter: React.FC<AlertDialogFooterProps> = ({ children, className }) => {
    return <div className={cn("flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2", className)}>{children}</div>
}

interface AlertDialogTitleProps {
    children: React.ReactNode;
    className?: string;
}

const AlertDialogTitle: React.FC<AlertDialogTitleProps> = ({ children, className }) => {
    return <h2 className={cn("text-lg font-semibold", className)}>{children}</h2>
}

interface AlertDialogDescriptionProps {
    children: React.ReactNode;
    className?: string;
}

const AlertDialogDescription: React.FC<AlertDialogDescriptionProps> = ({ children, className }) => {
    return <p className={cn("text-sm text-muted-foreground", className)}>{children}</p>
}

interface AlertDialogActionProps {
    children: React.ReactNode;
    onClick?: () => void;
    className?: string;
    variant?: "default" | "destructive" | "outline" | "secondary" | "ghost" | "link";
}

const AlertDialogAction: React.FC<AlertDialogActionProps> = ({ children, onClick, className, variant = "default" }) => {
    return (
        <Button onClick={onClick} variant={variant} className={className}>
            {children}
        </Button>
    )
}

interface AlertDialogCancelProps {
    children: React.ReactNode;
    onClick?: () => void;
    className?: string;
}

const AlertDialogCancel: React.FC<AlertDialogCancelProps> = ({ children, onClick, className }) => {
    return (
        <Button onClick={onClick} variant="outline" className={className}>
            {children}
        </Button>
    )
}

const AlertDialogTrigger = React.forwardRef<
    HTMLDivElement,
    React.HTMLAttributes<HTMLDivElement>
>(({ children, ...props }, ref) => (
    <div ref={ref} {...props}>
        {children}
    </div>
))
AlertDialogTrigger.displayName = "AlertDialogTrigger"

export {
    AlertDialog,
    AlertDialogTrigger,
    AlertDialogContent,
    AlertDialogHeader,
    AlertDialogFooter,
    AlertDialogTitle,
    AlertDialogDescription,
    AlertDialogAction,
    AlertDialogCancel,
}
