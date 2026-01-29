import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import {
    LayoutDashboard,
    Users,
    Shield,
    Settings,
    ChevronDown,
    ChevronRight,
    Building2,
    UserCheck,
    GraduationCap,
    BookOpen,
    MapPin,
    Globe,
    Menu,
    X,
} from 'lucide-react';

interface SidebarItem {
    title: string;
    href?: string;
    icon: React.ComponentType<{ className?: string }>;
    badge?: string;
    children?: SidebarItem[];
    permission?: string;
}

interface Props {
    className?: string;
}

export default function Sidebar({ className }: Props) {
    const [expandedItems, setExpandedItems] = useState<string[]>(['erp', 'admin']);
    const [isMobileOpen, setIsMobileOpen] = useState(false);
    const page = usePage();
    const auth = page.props.auth as any;

    const toggleExpanded = (item: string) => {
        setExpandedItems(prev =>
            prev.includes(item)
                ? prev.filter(i => i !== item)
                : [...prev, item]
        );
    };

    const hasPermission = (permission?: string): boolean => {
        if (!permission) return true;
        return auth?.user?.permissions?.includes(permission) || auth?.user?.roles?.some((role: any) => role.name === 'superadmin');
    };

    const isActive = (href: string): boolean => {
        return window.location.pathname === href;
    };

    const sidebarItems: SidebarItem[] = [
        {
            title: 'Dashboard',
            href: '/dashboard',
            icon: LayoutDashboard,
        },
        {
            title: 'ERP',
            icon: Building2,
            children: [
                {
                    title: 'Branches',
                    href: '/branches',
                    icon: Building2,
                    permission: 'manage_branches',
                },
                {
                    title: 'Employees',
                    href: '/employees',
                    icon: Users,
                    permission: 'manage_employees',
                },
                {
                    title: 'Agents',
                    href: '/agents',
                    icon: UserCheck,
                    permission: 'manage_agents',
                },
                {
                    title: 'Students',
                    href: '/students',
                    icon: GraduationCap,
                    permission: 'manage_students',
                },
                {
                    title: 'Universities',
                    href: '/universities',
                    icon: BookOpen,
                    permission: 'manage_universities',
                },
                {
                    title: 'Courses',
                    href: '/courses',
                    icon: BookOpen,
                    permission: 'manage_courses',
                },
                {
                    title: 'Countries',
                    href: '/countries',
                    icon: Globe,
                    permission: 'manage_countries',
                },
                {
                    title: 'States',
                    href: '/states',
                    icon: MapPin,
                    permission: 'manage_states',
                },
            ],
        },
        {
            title: 'Admin',
            icon: Shield,
            permission: 'superadmin',
            children: [
                {
                    title: 'User Roles',
                    href: '/admin/user-roles',
                    icon: Users,
                    badge: 'New',
                },
                {
                    title: 'Roles & Permissions',
                    href: '/admin/roles',
                    icon: Shield,
                },
                {
                    title: 'System Settings',
                    href: '/admin/settings',
                    icon: Settings,
                },
            ],
        },
    ];

    const renderSidebarItem = (item: SidebarItem, depth = 0) => {
        const isExpanded = expandedItems.includes(item.title);
        const hasChildren = item.children && item.children.length > 0;
        const isItemActive = item.href ? isActive(item.href) : false;

        if (!hasPermission(item.permission)) {
            return null;
        }

        return (
            <div key={item.title}>
                <div
                    className={`
                        flex items-center justify-between w-full px-3 py-2 text-sm rounded-md transition-colors
                        ${isItemActive
                            ? 'bg-blue-100 text-blue-700 font-medium'
                            : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
                        }
                        ${depth > 0 ? 'ml-4' : ''}
                    `}
                >
                    {item.href ? (
                        <Link
                            href={item.href}
                            className="flex items-center justify-between w-full"
                            onClick={() => setIsMobileOpen(false)}
                        >
                            <div className="flex items-center gap-3">
                                <item.icon className="h-4 w-4" />
                                <span>{item.title}</span>
                            </div>
                            {item.badge && (
                                <Badge variant="secondary" className="text-xs">
                                    {item.badge}
                                </Badge>
                            )}
                        </Link>
                    ) : (
                        <button
                            onClick={() => toggleExpanded(item.title)}
                            className="flex items-center justify-between w-full"
                        >
                            <div className="flex items-center gap-3">
                                <item.icon className="h-4 w-4" />
                                <span>{item.title}</span>
                            </div>
                            {hasChildren && (
                                isExpanded ? (
                                    <ChevronDown className="h-4 w-4" />
                                ) : (
                                    <ChevronRight className="h-4 w-4" />
                                )
                            )}
                        </button>
                    )}
                </div>
                {hasChildren && isExpanded && (
                    <div className="mt-1">
                        {item.children!.map((child) => renderSidebarItem(child, depth + 1))}
                    </div>
                )}
            </div>
        );
    };

    const SidebarContent = () => (
        <div className="flex flex-col h-full">
            {/* Logo */}
            <div className="p-6 border-b">
                <Link href="/dashboard" className="flex items-center gap-2">
                    <div className="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <Shield className="h-5 w-5 text-white" />
                    </div>
                    <span className="text-xl font-bold text-gray-900">ERP System</span>
                </Link>
            </div>

            {/* Navigation */}
            <nav className="flex-1 p-4 space-y-2">
                {sidebarItems.map((item) => renderSidebarItem(item))}
            </nav>

            {/* User Info */}
            <div className="p-4 border-t">
                <div className="flex items-center gap-3">
                    <div className="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                        <Users className="h-4 w-4 text-gray-600" />
                    </div>
                    <div className="flex-1 min-w-0">
                        <div className="text-sm font-medium text-gray-900 truncate">
                            {auth?.user?.name}
                        </div>
                        <div className="text-xs text-gray-500 truncate">
                            {auth?.user?.roles?.[0]?.name?.replace('_', ' ').toUpperCase() || 'User'}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );

    return (
        <>
            {/* Mobile Menu Button */}
            <div className="lg:hidden fixed top-4 left-4 z-50">
                <Button
                    variant="outline"
                    size="sm"
                    onClick={() => setIsMobileOpen(!isMobileOpen)}
                >
                    {isMobileOpen ? <X className="h-4 w-4" /> : <Menu className="h-4 w-4" />}
                </Button>
            </div>

            {/* Mobile Sidebar */}
            {isMobileOpen && (
                <div className="lg:hidden fixed inset-0 z-40 flex">
                    <div className="fixed inset-0 bg-black/50" onClick={() => setIsMobileOpen(false)} />
                    <div className="relative flex flex-col w-64 bg-white shadow-xl">
                        <SidebarContent />
                    </div>
                </div>
            )}

            {/* Desktop Sidebar */}
            <div className={`hidden lg:flex lg:flex-col lg:w-64 lg:bg-white lg:border-r lg:fixed lg:inset-y-0 ${className}`}>
                <SidebarContent />
            </div>
        </>
    );
}
