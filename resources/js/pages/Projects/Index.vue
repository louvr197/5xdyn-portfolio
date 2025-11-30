<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Card, CardContent } from '@/components/ui/card';
import { ref, watch } from 'vue';
import { ChevronDown, ChevronUp } from 'lucide-vue-next';

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

interface Filters {
    search?: string;
    status?: string;
    type?: string;
    year?: string;
    sort_by?: string;
    sort_order?: string;
}

const props = defineProps<{
    projects: PaginatedProjects;
    filters: Filters;
}>();

const search = ref(props.filters.search || '');
const status = ref<string | undefined>(props.filters.status || undefined);
const type = ref<string | undefined>(props.filters.type || undefined);
const year = ref(props.filters.year || '');
const sortBy = ref(props.filters.sort_by || 'display_order');
const sortOrder = ref(props.filters.sort_order || 'asc');
const showFilters = ref(false);

const applyFilters = () => {
    router.get('/projects', {
        search: search.value || undefined,
        status: status.value || undefined,
        type: type.value || undefined,
        year: year.value || undefined,
        sort_by: sortBy.value,
        sort_order: sortOrder.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilters = () => {
    search.value = '';
    status.value = undefined;
    type.value = undefined;
    year.value = '';
    sortBy.value = 'display_order';
    sortOrder.value = 'asc';
    router.get('/projects', {}, {
        preserveState: true,
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Projets',
        href: '/projects',
    },
];

const reorder = (projectId: number, direction: 'up' | 'down') => {
    router.post(`/projects/${projectId}/reorder`, {
        direction,
    }, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Projets" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Projets</h1>
                <div class="flex gap-2">
                    <Button @click="showFilters = !showFilters" variant="outline">
                        <ChevronDown v-if="!showFilters" class="h-4 w-4 mr-2" />
                        <ChevronUp v-if="showFilters" class="h-4 w-4 mr-2" />
                        {{ showFilters ? 'Masquer les filtres' : 'Afficher les filtres' }}
                    </Button>
                    <Button as-child>
                        <Link href="/projects/create">
                            Nouveau projet
                        </Link>
                    </Button>
                </div>
            </div>

            <!-- Filters -->
            <Card v-show="showFilters">
                <CardContent class="pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="space-y-2">
                            <Label for="search">Rechercher</Label>
                            <Input
                                id="search"
                                v-model="search"
                                placeholder="Titre ou description..."
                                @keyup.enter="applyFilters"
                            />
                        </div>

                        <!-- Status -->
                        <div class="space-y-2">
                            <Label for="status">Statut</Label>
                            <Select :modelValue="status || 'all'" @update:modelValue="(value) => status = value === 'all' ? undefined : value">
                                <SelectTrigger>
                                    <SelectValue placeholder="Tous les statuts" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">Tous</SelectItem>
                                    <SelectItem value="draft">Brouillon</SelectItem>
                                    <SelectItem value="published">Publié</SelectItem>
                                    <SelectItem value="archived">Archivé</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Type -->
                        <div class="space-y-2">
                            <Label for="type">Type</Label>
                            <Select :modelValue="type || 'all'" @update:modelValue="(value) => type = value === 'all' ? undefined : value">
                                <SelectTrigger>
                                    <SelectValue placeholder="Tous les types" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">Tous</SelectItem>
                                    <SelectItem value="website">Site Web</SelectItem>
                                    <SelectItem value="mobile_app">Application Mobile</SelectItem>
                                    <SelectItem value="api">API</SelectItem>
                                    <SelectItem value="design">Design</SelectItem>
                                    <SelectItem value="other">Autre</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Year -->
                        <div class="space-y-2">
                            <Label for="year">Année</Label>
                            <Input
                                id="year"
                                v-model="year"
                                type="number"
                                placeholder="2024"
                                min="2000"
                                :max="new Date().getFullYear()"
                            />
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="space-y-2">
                            <Label for="sort_by">Trier par</Label>
                            <Select :modelValue="sortBy" @update:modelValue="(value) => sortBy = value">
                                <SelectTrigger>
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="display_order">Ordre d'affichage</SelectItem>
                                    <SelectItem value="title">Titre</SelectItem>
                                    <SelectItem value="completion_date">Date de réalisation</SelectItem>
                                    <SelectItem value="created_at">Date de création</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div class="space-y-2">
                            <Label for="sort_order">Ordre</Label>
                            <Select :modelValue="sortOrder" @update:modelValue="(value) => sortOrder = value">
                                <SelectTrigger>
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="asc">Croissant</SelectItem>
                                    <SelectItem value="desc">Décroissant</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 mt-4">
                        <Button @click="applyFilters">
                            Appliquer les filtres
                        </Button>
                        <Button @click="resetFilters" variant="outline">
                            Réinitialiser
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Results count -->
            <div class="text-sm text-muted-foreground">
                {{ projects.total }} projet{{ projects.total > 1 ? 's' : '' }} trouvé{{ projects.total > 1 ? 's' : '' }}
            </div>

            <div class="rounded-lg border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-20">Ordre</TableHead>
                            <TableHead>Titre</TableHead>
                            <TableHead>Type</TableHead>
                            <TableHead>Statut</TableHead>
                            <TableHead>Date</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="(project, index) in projects.data"
                            :key="project.id"
                        >
                            <TableCell class="w-20">
                                <div class="flex flex-col gap-1">
                                    <Button
                                        size="sm"
                                        variant="ghost"
                                        @click="reorder(project.id, 'up')"
                                        :disabled="index === 0"
                                        class="h-6 px-2"
                                    >
                                        ↑
                                    </Button>
                                    <Button
                                        size="sm"
                                        variant="ghost"
                                        @click="reorder(project.id, 'down')"
                                        :disabled="index === projects.data.length - 1"
                                        class="h-6 px-2"
                                    >
                                        ↓
                                    </Button>
                                </div>
                            </TableCell>
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
