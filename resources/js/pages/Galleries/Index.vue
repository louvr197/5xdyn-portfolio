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

interface Image {
    id: number;
    image_path: string;
}

interface Project {
    id: number;
    title: string;
    slug: string;
}

interface Gallery {
    id: number;
    title: string;
    project: Project;
    images: Image[];
    created_at: string;
}

interface PaginatedGalleries {
    data: Gallery[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineProps<{
    galleries: PaginatedGalleries;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Galeries',
        href: '/galleries',
    },
];
</script>

<template>
    <Head title="Galeries" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Galeries</h1>
                <Button as-child>
                    <Link href="/galleries/create">Nouvelle galerie</Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Liste des galeries</CardTitle>
                    <CardDescription> Gérez les galeries d'images de vos projets </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Titre</TableHead>
                                <TableHead>Projet</TableHead>
                                <TableHead>Nombre d'images</TableHead>
                                <TableHead>Date de création</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="gallery in galleries.data" :key="gallery.id">
                                <TableCell class="font-medium">
                                    <Link
                                        :href="`/galleries/${gallery.id}`"
                                        class="hover:underline"
                                    >
                                        {{ gallery.title }}
                                    </Link>
                                </TableCell>
                                <TableCell>
                                    <Link
                                        :href="`/projects/${gallery.project.slug}`"
                                        class="text-sm text-muted-foreground hover:underline"
                                    >
                                        {{ gallery.project.title }}
                                    </Link>
                                </TableCell>
                                <TableCell>
                                    {{ gallery.images.length }}
                                </TableCell>
                                <TableCell>
                                    {{ new Date(gallery.created_at).toLocaleDateString('fr-FR') }}
                                </TableCell>
                                <TableCell class="text-right space-x-2">
                                    <Button size="sm" variant="outline" as-child>
                                        <Link :href="`/galleries/${gallery.id}`"> Voir </Link>
                                    </Button>
                                    <Button size="sm" variant="outline" as-child>
                                        <Link :href="`/galleries/${gallery.id}/edit`">
                                            Modifier
                                        </Link>
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <!-- Pagination -->
                    <div v-if="galleries.last_page > 1" class="flex justify-center gap-2 mt-4">
                        <Link
                            v-for="page in galleries.last_page"
                            :key="page"
                            :href="`/galleries?page=${page}`"
                            class="px-3 py-1 rounded border"
                            :class="{
                                'bg-primary text-primary-foreground':
                                    page === galleries.current_page,
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
