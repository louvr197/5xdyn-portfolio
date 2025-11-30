<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
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
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    LayoutGrid,
    FolderKanban,
    Wrench,
    Target,
    Briefcase,
    GraduationCap,
    Image,
    User,
    Globe,
    Palette,
    Users
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';
import { Settings } from 'lucide-vue-next';

const page = usePage();
const isAdmin = computed(() => page.props.auth?.isAdmin === true);
const userId = computed(() => page.props.auth?.user?.id);

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const portfolioNavItems: NavItem[] = [
    {
        title: 'Projets',
        href: '/projects',
        icon: FolderKanban,
    },
    {
        title: 'Technologies',
        href: '/technologies',
        icon: Wrench,
    },
    {
        title: 'Techniques',
        href: '/techniques',
        icon: Palette,
    },
    {
        title: 'Expériences',
        href: '/experiences',
        icon: Briefcase,
    },
    {
        title: 'Formations',
        href: '/formations',
        icon: GraduationCap,
    },
    {
        title: 'Galeries',
        href: '/galleries',
        icon: Image,
    },
];

const settingsNavItems: NavItem[] = [
    {
        title: 'Informations',
        href: `/users/${userId.value}/edit`,
        icon: User,
    },
    {
        title: 'Portfolio Public',
        href: '/portfolios',
        icon: Globe,
    },
    {
        title: 'Paramètres',
        href: '/settings/profile',
        icon: Settings,
    },
];

const adminNavItems: NavItem[] = [
    {
        title: 'Gestion des utilisateurs',
        href: '/admin/users',
        icon: Users,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" label="Navigation" />
            <NavMain :items="portfolioNavItems" label="Portfolio" />
            <NavMain :items="settingsNavItems" label="Paramètres" />
            <NavMain v-if="isAdmin" :items="adminNavItems" label="Administration" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
