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

interface Technique {
    id: number;
    name: string;
    description: string | null;
    proficiency_level: string;
    display_order: number | null;
}

interface PaginatedTechniques {
    data: Technique[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineProps<{
    techniques: PaginatedTechniques;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Techniques',
        href: '/techniques',
    },
];

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
    <Head title="Techniques" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Mes techniques</h1>
                <Button as-child>
                    <Link href="/techniques/create">Nouvelle technique</Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Liste des techniques</CardTitle>
                    <CardDescription> Gérez vos techniques et méthodes </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nom</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead>Niveau</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="technique in techniques.data"
                                :key="technique.id"
                            >
                                <TableCell class="font-medium">
                                    <Link
                                        :href="`/techniques/${technique.id}`"
                                        class="hover:underline"
                                    >
                                        {{ technique.name }}
                                    </Link>
                                </TableCell>
                                <TableCell>
                                    <span class="text-sm text-muted-foreground line-clamp-2">
                                        {{ technique.description || '-' }}
                                    </span>
                                </TableCell>
                                <TableCell>
                                    <Badge
                                        :class="
                                            proficiencyColors[technique.proficiency_level] ||
                                            'bg-gray-100'
                                        "
                                    >
                                        {{
                                            proficiencyLabels[technique.proficiency_level] ||
                                            technique.proficiency_level
                                        }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-right space-x-2">
                                    <Button size="sm" variant="outline" as-child>
                                        <Link :href="`/techniques/${technique.id}`"> Voir </Link>
                                    </Button>
                                    <Button size="sm" variant="outline" as-child>
                                        <Link :href="`/techniques/${technique.id}/edit`">
                                            Modifier
                                        </Link>
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <!-- Pagination -->
                    <div v-if="techniques.last_page > 1" class="flex justify-center gap-2 mt-4">
                        <Link
                            v-for="page in techniques.last_page"
                            :key="page"
                            :href="`/techniques?page=${page}`"
                            class="px-3 py-1 rounded border"
                            :class="{
                                'bg-primary text-primary-foreground':
                                    page === techniques.current_page,
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
