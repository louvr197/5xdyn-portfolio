<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
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

interface Experience {
    id: number;
    position: string;
    company: string;
    location: string | null;
    start_date: string;
    end_date: string | null;
    description: string | null;
    contract_type: string | null;
    display_order: number | null;
}

interface PaginatedExperiences {
    data: Experience[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineProps<{
    experiences: PaginatedExperiences;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Expériences',
        href: '/experiences',
    },
];

const formatDate = (date: string | null) => {
    if (!date) return 'En cours';
    return new Date(date).toLocaleDateString('fr-FR', {
        month: 'long',
        year: 'numeric',
    });
};
</script>

<template>
    <Head title="Expériences" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Expériences professionnelles</h1>
                <Button as-child>
                    <Link href="/experiences/create">Nouvelle expérience</Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Liste des expériences</CardTitle>
                    <CardDescription>
                        Gérez vos expériences professionnelles
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Poste</TableHead>
                                <TableHead>Entreprise</TableHead>
                                <TableHead>Type</TableHead>
                                <TableHead>Période</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="experience in experiences.data"
                                :key="experience.id"
                            >
                                <TableCell class="font-medium">
                                    <Link
                                        :href="`/experiences/${experience.id}`"
                                        class="hover:underline"
                                    >
                                        {{ experience.position }}
                                    </Link>
                                </TableCell>
                                <TableCell>{{ experience.company }}</TableCell>
                                <TableCell>{{ experience.contract_type || '-' }}</TableCell>
                                <TableCell>
                                    {{ formatDate(experience.start_date) }} -
                                    {{ formatDate(experience.end_date) }}
                                </TableCell>
                                <TableCell class="text-right space-x-2">
                                    <Button size="sm" variant="outline" as-child>
                                        <Link :href="`/experiences/${experience.id}`">
                                            Voir
                                        </Link>
                                    </Button>
                                    <Button size="sm" variant="outline" as-child>
                                        <Link :href="`/experiences/${experience.id}/edit`">
                                            Modifier
                                        </Link>
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <!-- Pagination -->
                    <div
                        v-if="experiences.last_page > 1"
                        class="flex justify-center gap-2 mt-4"
                    >
                        <Link
                            v-for="page in experiences.last_page"
                            :key="page"
                            :href="`/experiences?page=${page}`"
                            class="px-3 py-1 rounded border"
                            :class="{
                                'bg-primary text-primary-foreground':
                                    page === experiences.current_page,
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
