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

interface Formation {
    id: number;
    degree: string;
    institution: string;
    location: string | null;
    start_date: string;
    end_date: string | null;
    description: string | null;
    display_order: number | null;
}

interface PaginatedFormations {
    data: Formation[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineProps<{
    formations: PaginatedFormations;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Formations',
        href: '/formations',
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
    <Head title="Formations" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Formations</h1>
                <Button as-child>
                    <Link href="/formations/create">Nouvelle formation</Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Liste des formations</CardTitle>
                    <CardDescription>
                        Gérez vos formations et diplômes
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Diplôme</TableHead>
                                <TableHead>Établissement</TableHead>
                                <TableHead>Lieu</TableHead>
                                <TableHead>Période</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="formation in formations.data" :key="formation.id">
                                <TableCell class="font-medium">
                                    <Link
                                        :href="`/formations/${formation.id}`"
                                        class="hover:underline"
                                    >
                                        {{ formation.degree }}
                                    </Link>
                                </TableCell>
                                <TableCell>{{ formation.institution }}</TableCell>
                                <TableCell>{{ formation.location || '-' }}</TableCell>
                                <TableCell>
                                    {{ formatDate(formation.start_date) }} -
                                    {{ formatDate(formation.end_date) }}
                                </TableCell>
                                <TableCell class="text-right space-x-2">
                                    <Button size="sm" variant="outline" as-child>
                                        <Link :href="`/formations/${formation.id}`">
                                            Voir
                                        </Link>
                                    </Button>
                                    <Button size="sm" variant="outline" as-child>
                                        <Link :href="`/formations/${formation.id}/edit`">
                                            Modifier
                                        </Link>
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <!-- Pagination -->
                    <div
                        v-if="formations.last_page > 1"
                        class="flex justify-center gap-2 mt-4"
                    >
                        <Link
                            v-for="page in formations.last_page"
                            :key="page"
                            :href="`/formations?page=${page}`"
                            class="px-3 py-1 rounded border"
                            :class="{
                                'bg-primary text-primary-foreground':
                                    page === formations.current_page,
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
