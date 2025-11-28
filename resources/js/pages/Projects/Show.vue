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
}

interface Technique {
    id: number;
    name: string;
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
                        <div class="flex flex-wrap gap-2">
                            <Badge
                                v-for="tech in project.technologies"
                                :key="tech.id"
                                variant="secondary"
                            >
                                {{ tech.name }}
                            </Badge>
                        </div>
                    </CardContent>
                </Card>

                <Card v-if="project.techniques.length > 0">
                    <CardHeader>
                        <CardTitle>Techniques</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-wrap gap-2">
                            <Badge
                                v-for="technique in project.techniques"
                                :key="technique.id"
                                variant="secondary"
                            >
                                {{ technique.name }}
                            </Badge>
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
