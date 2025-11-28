<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

interface Technology {
    id: number;
    name: string;
}

interface Technique {
    id: number;
    name: string;
}

interface Project {
    id: number;
    title: string;
    slug: string;
    short_description: string;
    status: 'draft' | 'published' | 'archived';
    type: 'website' | 'mobile_app' | 'api' | 'design' | 'other';
    completion_date: string | null;
    main_image: string | null;
    technologies: Technology[];
    techniques: Technique[];
}

interface PaginatedProjects {
    data: Project[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineProps<{
    projects: PaginatedProjects;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Projets',
        href: '/projects',
    },
];
</script>

<template>
    <Head title="Projets" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Projets</h1>
                <Button as-child>
                    <Link href="/projects/create">
                        Nouveau projet
                    </Link>
                </Button>
            </div>

            <div class="rounded-lg border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Titre</TableHead>
                            <TableHead>Type</TableHead>
                            <TableHead>Statut</TableHead>
                            <TableHead>Date</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="project in projects.data"
                            :key="project.id"
                        >
                            <TableCell>
                                <Link
                                    :href="`/projects/${project.id}`"
                                    class="font-medium text-blue-600 hover:underline"
                                >
                                    {{ project.title }}
                                </Link>
                            </TableCell>
                            <TableCell class="capitalize">
                                {{ project.type.replace('_', ' ') }}
                            </TableCell>
                            <TableCell>
                                <span
                                    :class="{
                                        'text-green-600': project.status === 'published',
                                        'text-yellow-600': project.status === 'draft',
                                        'text-gray-600': project.status === 'archived'
                                    }"
                                    class="capitalize"
                                >
                                    {{ project.status === 'published' ? 'Publié' : project.status === 'draft' ? 'Brouillon' : 'Archivé' }}
                                </span>
                            </TableCell>
                            <TableCell>
                                {{ project.completion_date ? new Date(project.completion_date).toLocaleDateString('fr-FR') : '-' }}
                            </TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Button
                                        as-child
                                        variant="outline"
                                        size="sm"
                                    >
                                        <Link :href="`/projects/${project.slug}`">
                                            Voir
                                        </Link>
                                    </Button>
                                    <Button
                                        as-child
                                        variant="outline"
                                        size="sm"
                                    >
                                        <Link :href="`/projects/${project.slug}/edit`">
                                            Modifier
                                        </Link>
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div v-if="projects.last_page > 1" class="flex justify-center gap-2">
                <Button
                    v-for="page in projects.last_page"
                    :key="page"
                    :variant="page === projects.current_page ? 'default' : 'outline'"
                    size="sm"
                    as-child
                >
                    <Link :href="`/projects?page=${page}`">
                        {{ page }}
                    </Link>
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
