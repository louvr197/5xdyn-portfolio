<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { Code, Search, ChevronDown, ChevronUp } from 'lucide-vue-next';
import { ref } from 'vue';

interface Technology {
    id: number;
    name: string;
    logo_path?: string;
    color_code?: string;
}

interface Technique {
    id: number;
    name: string;
}

interface Skill {
    id: string;
    name: string;
    type: 'technology' | 'technique';
}

interface Project {
    id: number;
    title: string;
    slug: string;
    short_description: string;
    type: string;
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
    links: Array<{ url: string | null; label: string; active: boolean }>;
}

interface Information {
    first_name: string;
    last_name: string;
    professional_title: string;
}

interface User {
    id: number;
    slug: string;
}

interface Filters {
    search?: string;
    skill?: string;
    year?: string;
}

interface Props {
    user: User;
    information: Information;
    projects: PaginatedProjects;
    skills: Skill[];
    filters: Filters;
}

const props = defineProps<Props>();

const search = ref(props.filters.search || '');
const skill = ref<string | undefined>(props.filters.skill || undefined);
const year = ref(props.filters.year || '');
const showFilters = ref(false);

const applyFilters = () => {
    router.get(`/portfolio/${props.user.slug}/projets`, {
        search: search.value || undefined,
        skill: skill.value || undefined,
        year: year.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilters = () => {
    search.value = '';
    skill.value = undefined;
    year.value = '';
    router.get(`/portfolio/${props.user.slug}/projets`, {}, {
        preserveState: true,
    });
};

const getTypeLabel = (projectType: string) => {
    const labels: Record<string, string> = {
        website: 'Site Web',
        mobile_app: 'Application Mobile',
        api: 'API',
        design: 'Design',
        other: 'Autre',
    };
    return labels[projectType] || projectType;
};
</script>

<template>
    <Head :title="`Projets - ${information.first_name} ${information.last_name}`" />

    <PublicLayout>
        <div class="min-h-screen" :style="{ backgroundColor: `${information.theme_color}26` }">
        <!-- Header -->
        <section class="border-b bg-gradient-to-r from-primary/10 via-primary/5 to-background py-12">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold mb-4">Projets</h1>
                <p class="text-lg text-muted-foreground">
                    Découvrez mes {{ projects.total }} projet{{ projects.total > 1 ? 's' : '' }} réalisé{{ projects.total > 1 ? 's' : '' }}
                </p>
            </div>
        </section>

        <!-- Filters -->
        <section class="border-b py-6" aria-label="Filtres de recherche">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Filtres</h2>
                    <Button @click="showFilters = !showFilters" variant="ghost" size="sm" :aria-expanded="showFilters" aria-controls="filters-panel">
                        <span v-if="showFilters">Masquer les filtres</span>
                        <span v-else>Afficher les filtres</span>
                        <ChevronDown v-if="!showFilters" class="ml-2 h-4 w-4" aria-hidden="true" />
                        <ChevronUp v-if="showFilters" class="ml-2 h-4 w-4" aria-hidden="true" />
                    </Button>
                </div>
                <Card v-show="showFilters" id="filters-panel" role="search" aria-label="Formulaire de recherche de projets">
                    <CardContent class="pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div class="space-y-2">
                                <Label for="search">Rechercher</Label>
                                <div class="relative">
                                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" aria-hidden="true" />
                                    <Input
                                        id="search"
                                        v-model="search"
                                        placeholder="Titre ou description..."
                                        class="pl-9"
                                        @keydown.enter="applyFilters"
                                        aria-label="Rechercher par titre ou description"
                                    />
                                </div>
                            </div>

                            <!-- Skill (Technology or Technique) -->
                            <div class="space-y-2">
                                <Label for="skill">Compétence</Label>
                                <Select :modelValue="skill || 'all'" @update:modelValue="(value) => skill = value === 'all' ? undefined : value">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Toutes" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">Toutes</SelectItem>
                                        <SelectItem v-for="item in skills" :key="item.id" :value="item.id">
                                            {{ item.name }}
                                        </SelectItem>
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

                            <!-- Actions -->
                            <div class="space-y-2">
                                <Label>&nbsp;</Label>
                                <div class="flex gap-2">
                                    <Button @click="applyFilters" class="flex-1" aria-label="Appliquer les filtres de recherche">
                                        Filtrer
                                    </Button>
                                    <Button @click="resetFilters" variant="outline" aria-label="Réinitialiser tous les filtres">
                                        Reset
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </section>

        <!-- Projects Grid -->
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div v-if="projects.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Card v-for="project in projects.data" :key="project.id" class="overflow-hidden hover:shadow-lg transition-shadow">
                        <Link :href="`/portfolio/${user.slug}/projets/${project.slug}`">
                            <div class="aspect-video bg-muted relative overflow-hidden">
                                <img
                                    v-if="project.main_image"
                                    :src="`/storage/${project.main_image}`"
                                    :alt="project.title"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center">
                                    <Code class="h-12 w-12 text-muted-foreground" />
                                </div>
                            </div>
                        </Link>
                        <CardHeader>
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <CardTitle class="line-clamp-1">
                                    <Link :href="`/portfolio/${user.slug}/projets/${project.slug}`" class="hover:text-primary transition-colors">
                                        {{ project.title }}
                                    </Link>
                                </CardTitle>
                                <Badge variant="secondary" class="shrink-0">
                                    {{ getTypeLabel(project.type) }}
                                </Badge>
                            </div>
                            <CardDescription class="line-clamp-2">
                                {{ project.short_description }}
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div v-if="project.technologies.length > 0" class="flex flex-wrap gap-2 mb-4">
                                <Badge
                                    v-for="tech in project.technologies.slice(0, 4)"
                                    :key="tech.id"
                                    variant="outline"
                                    class="text-xs"
                                >
                                    {{ tech.name }}
                                </Badge>
                                <Badge v-if="project.technologies.length > 4" variant="outline" class="text-xs">
                                    +{{ project.technologies.length - 4 }}
                                </Badge>
                            </div>
                            <Button as-child class="w-full">
                                <Link :href="`/portfolio/${user.slug}/projets/${project.slug}`">
                                    Voir le projet
                                </Link>
                            </Button>
                        </CardContent>
                    </Card>
                </div>

                <div v-else class="text-center py-12">
                    <Code class="mx-auto h-12 w-12 text-muted-foreground mb-4" />
                    <h3 class="text-lg font-semibold mb-2">Aucun projet trouvé</h3>
                    <p class="text-muted-foreground mb-4">Aucun projet ne correspond à vos critères de recherche.</p>
                    <Button @click="resetFilters" variant="outline">
                        Réinitialiser les filtres
                    </Button>
                </div>

                <!-- Pagination -->
                <div v-if="projects.last_page > 1" class="mt-8 flex justify-center gap-2">
                    <Button
                        v-for="link in projects.links"
                        :key="link.label"
                        :variant="link.active ? 'default' : 'outline'"
                        :disabled="!link.url"
                        @click="link.url && router.get(link.url)"
                        size="sm"
                        v-html="link.label"
                    />
                </div>
            </div>
        </section>
        </div>
    </PublicLayout>
</template>
