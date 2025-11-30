<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

interface Technology {
    id: number;
    name: string;
    category: string;
    proficiency_level: string;
    logo_path: string | null;
    color_code: string | null;
}

interface Technique {
    id: number;
    name: string;
    description: string | null;
    proficiency_level: string;
}

interface Image {
    id: number;
    path: string;
    caption: string | null;
}

interface Gallery {
    id: number;
    title: string;
    images: Image[];
}

interface Project {
    id: number;
    title: string;
    slug: string;
    short_description: string;
    long_description: string | null;
    status: string;
    type: string;
    context: string | null;
    completion_date: string | null;
    main_image: string | null;
    technologies: Technology[];
    techniques: Technique[];
    galleries: Gallery[];
    user: {
        id: number;
        name: string;
    };
}

const props = defineProps<{
    project: Project;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Projets',
        href: '/projects',
    },
    {
        title: props.project.title,
        href: `/projects/${props.project.slug}`,
    },
];
</script>

<template>
    <Head :title="project.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ project.title }}</h1>
                <Button as-child variant="outline">
                    <Link :href="`/projects/${project.slug}/edit`">
                        Modifier
                    </Link>
                </Button>
            </div>

            <!-- Main image if exists -->
            <div v-if="project.main_image" class="w-full">
                <img
                    :src="`/storage/${project.main_image}`"
                    :alt="project.title"
                    class="w-full h-96 object-cover rounded-lg"
                />
            </div>

            <!-- Project details grid -->
            <div class="grid gap-4 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Informations générales</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-2">
                        <div>
                            <span class="font-medium">Type:</span>
                            <span class="ml-2 capitalize">{{ project.type.replace('_', ' ') }}</span>
                        </div>
                        <div>
                            <span class="font-medium">Statut:</span>
                            <span
                                class="ml-2 capitalize"
                                :class="{
                                    'text-green-600': project.status === 'published',
                                    'text-yellow-600': project.status === 'draft',
                                    'text-gray-600': project.status === 'archived'
                                }"
                            >
                                {{ project.status === 'published' ? 'Publié' : project.status === 'draft' ? 'Brouillon' : 'Archivé' }}
                            </span>
                        </div>
                        <div v-if="project.context">
                            <span class="font-medium">Contexte:</span>
                            <span class="ml-2 capitalize">{{ project.context }}</span>
                        </div>
                        <div v-if="project.completion_date">
                            <span class="font-medium">Date:</span>
                            <span class="ml-2">{{ new Date(project.completion_date).toLocaleDateString('fr-FR') }}</span>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Description courte</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p>{{ project.short_description }}</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Long description if exists -->
            <Card v-if="project.long_description">
                <CardHeader>
                    <CardTitle>Description détaillée</CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-html="project.long_description" class="prose max-w-none"></div>
                </CardContent>
            </Card>

            <!-- Technologies and Techniques -->
            <div class="grid gap-4 md:grid-cols-2">
                <Card v-if="project.technologies.length > 0">
                    <CardHeader>
                        <CardTitle>Technologies</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-3">
                            <div
                                v-for="tech in project.technologies"
                                :key="tech.id"
                                class="flex items-center gap-3 p-2 rounded border"
                            >
                                <img
                                    v-if="tech.logo_path"
                                    :src="`/storage/${tech.logo_path}`"
                                    :alt="tech.name"
                                    class="w-8 h-8 object-contain"
                                />
                                <div
                                    v-else
                                    class="w-8 h-8 rounded flex items-center justify-center text-xs font-bold text-white"
                                    :style="{ backgroundColor: tech.color_code || '#666' }"
                                >
                                    {{ tech.name.substring(0, 2).toUpperCase() }}
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">{{ tech.name }}</div>
                                    <div class="text-sm text-muted-foreground">
                                        {{ tech.category === 'backend' ? 'Backend' :
                                           tech.category === 'frontend' ? 'Frontend' :
                                           tech.category === 'database' ? 'Base de données' :
                                           tech.category === 'devops' ? 'DevOps' :
                                           tech.category === 'design' ? 'Design' : 'Autre' }}
                                    </div>
                                </div>
                                <Badge
                                    :class="{
                                        'bg-gray-500': tech.proficiency_level === 'beginner',
                                        'bg-blue-500': tech.proficiency_level === 'intermediate',
                                        'bg-green-500': tech.proficiency_level === 'advanced',
                                        'bg-purple-500': tech.proficiency_level === 'expert'
                                    }"
                                >
                                    {{ tech.proficiency_level === 'beginner' ? 'Débutant' :
                                       tech.proficiency_level === 'intermediate' ? 'Intermédiaire' :
                                       tech.proficiency_level === 'advanced' ? 'Avancé' : 'Expert' }}
                                </Badge>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card v-if="project.techniques.length > 0">
                    <CardHeader>
                        <CardTitle>Techniques</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-3">
                            <div
                                v-for="technique in project.techniques"
                                :key="technique.id"
                                class="p-3 rounded border"
                            >
                                <div class="flex items-center justify-between mb-1">
                                    <div class="font-medium">{{ technique.name }}</div>
                                    <Badge
                                        :class="{
                                            'bg-gray-500': technique.proficiency_level === 'beginner',
                                            'bg-blue-500': technique.proficiency_level === 'intermediate',
                                            'bg-green-500': technique.proficiency_level === 'advanced',
                                            'bg-purple-500': technique.proficiency_level === 'expert'
                                        }"
                                    >
                                        {{ technique.proficiency_level === 'beginner' ? 'Débutant' :
                                           technique.proficiency_level === 'intermediate' ? 'Intermédiaire' :
                                           technique.proficiency_level === 'advanced' ? 'Avancé' : 'Expert' }}
                                    </Badge>
                                </div>
                                <p v-if="technique.description" class="text-sm text-muted-foreground">
                                    {{ technique.description }}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Galleries -->
            <div v-if="project.galleries.length > 0" class="space-y-4">
                <Card v-for="gallery in project.galleries" :key="gallery.id">
                    <CardHeader>
                        <CardTitle>{{ gallery.title }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div
                                v-for="image in gallery.images"
                                :key="image.id"
                                class="relative aspect-square"
                            >
                                <img
                                    :src="`/storage/${image.path}`"
                                    :alt="image.caption || gallery.title"
                                    class="w-full h-full object-cover rounded-lg"
                                />
                                <p v-if="image.caption" class="text-sm text-muted-foreground mt-1">
                                    {{ image.caption }}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
