import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';
import type { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import {
    BookOpen,
    Building,
    Building2,
    Calendar,
    CalendarDays,
    Flag,
    Folder,
    GraduationCap,
    Key,
    LayoutGrid,
    ListChecks,
    MapPinned,
    Network,
    School,
    Shield,
    Users,
    FileText,
    Settings,
    FileCheck,
} from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Countries',
        href: '/countries',
        icon: Flag,
    },
    {
        title: 'States',
        href: '/states',
        icon: MapPinned,
    },
    {
        title: 'Branches',
        href: '/branches',
        icon: Building,
    },
    {
        title: 'Employees',
        href: '/employees',
        icon: Users,
    },
    {
        title: 'Agents',
        href: '/agents',
        icon: Users,
    },
    {
        title: 'Universities',
        href: '/universities',
        icon: School,
    },
    {
        title: 'Courses',
        href: '/courses',
        icon: GraduationCap,
    },
    {
        title: 'Students',
        href: '/students',
        icon: Building2,
    },
    {
        title: 'Documents',
        href: '/documents',
        icon: FileText,
    },
    {
        title: 'Document Types',
        href: '/document-types',
        icon: FileCheck,
    },
    {
        title: 'Intakes',
        href: '/intakes',
        icon: Calendar,
    },
    {
        title: 'Application Years',
        href: '/application-years',
        icon: CalendarDays,
    },
    {
        title: 'Application Statuses',
        href: '/application-statuses',
        icon: ListChecks,
    },
    {
        title: 'Admin',
        href: '/admin/user-roles',
        icon: Shield,
    },
    {
        title: 'Roles & Permissions',
        href: '/admin/roles',
        icon: Key,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    const { auth } = usePage<SharedData>().props;
    const user = auth?.user;
    
    // Check if user is authenticated and has superadmin role
    const isAdmin = user && user.roles && Array.isArray(user.roles) && 
        user.roles.some((role) => role.name === 'super_admin' || role.name === 'superadmin');
    
    // Filter nav items based on permissions
    const filteredNavItems = mainNavItems.filter(item => {
        if (item.title === 'Admin' || item.title === 'Roles & Permissions') {
            return isAdmin;
        }
        return true;
    });

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={filteredNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
