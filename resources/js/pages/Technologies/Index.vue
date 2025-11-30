<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
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
    category: string;
    proficiency_level: string;
    logo_path: string | null;
    color_code: string | null;
    display_order: number | null;
}

interface PaginatedTechnologies {
    data: Technology[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineProps<{
    technologies: PaginatedTechnologies;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Technologies',
        href: '/technologies',
    },
];

const categoryLabels: Record<string, string> = {
    backend: 'Backend',
    frontend: 'Frontend',
    database: 'Base de données',
    devops: 'DevOps',
    design: 'Design',
    other: 'Autre',
};

const proficiencyLabels: Record<string, string> = {
    beginner: 'Débutant',
    intermediate: 'Intermédiaire',
    advanced: 'Avancé',
    expert: 'Expert',
};

const proficiencyColors: Record<string, string> = {
    beginner: 'bg-gray-100 text-gray-800',
    intermediate: 'bg-blue-100 text-blue-800',
    advanced: 'bg-green-100 text-green-800',
    expert: 'bg-purple-100 text-purple-800',
};
</script>

<template>
    <Head title="Technologies" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Mes technologies</h1>
                <Button as-child>
                    <Link href="/technologies/create">Nouvelle technologie</Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Liste des technologies</CardTitle>
                    <CardDescription> Gérez vos compétences technologiques </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nom</TableHead>
                                <TableHead>Catégorie</TableHead>
                                <TableHead>Niveau</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="technology in technologies.data"
                                :key="technology.id"
                            >
                                <TableCell class="font-medium">
                                    <div class="flex items-center gap-2">
                                        <div
                                            v-if="technology.logo_path"
                                            class="h-6 w-6 flex-shrink-0"
                                        >
                                            <img
                                                :src="`/storage/${technology.logo_path}`"
                                                :alt="`Logo ${technology.name}`"
                                                class="h-full w-full object-contain"
                                            />
                                        </div>
                                        <Link
                                            :href="`/technologies/${technology.id}`"
                                            class="hover:underline"
                                        >
                                            {{ technology.name }}
                                        </Link>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    {{ categoryLabels[technology.category] || technology.category }}
                                </TableCell>
                                <TableCell>
                                    <Badge
                                        :class="
                                            proficiencyColors[technology.proficiency_level] ||
                                            'bg-gray-100'
                                        "
                                    >
                                        {{
                                            proficiencyLabels[technology.proficiency_level] ||
                                            technology.proficiency_level
                                        }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-right space-x-2">
                                    <Button size="sm" variant="outline" as-child>
                                        <Link :href="`/technologies/${technology.id}`"> Voir </Link>
                                    </Button>
                                    <Button size="sm" variant="outline" as-child>
                                        <Link :href="`/technologies/${technology.id}/edit`">
                                            Modifier
                                        </Link>
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <!-- Pagination -->
                    <div v-if="technologies.last_page > 1" class="flex justify-center gap-2 mt-4">
                        <Link
                            v-for="page in technologies.last_page"
                            :key="page"
                            :href="`/technologies?page=${page}`"
                            class="px-3 py-1 rounded border"
                            :class="{
                                'bg-primary text-primary-foreground':
                                    page === technologies.current_page,
                            }"
                        >
                            {{ page }}
                        </Link>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
