<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Calendar, ExternalLink, ArrowLeft } from 'lucide-vue-next';

interface Technology {
    id: number;
    name: string;
    category: string;
    proficiency_level: number;
    logo_path: string | null;
    color_code: string | null;
}

interface Technique {
    id: number;
    name: string;
    description: string | null;
    proficiency_level: number;
}

interface Image {
    id: number;
    image_path: string;
    alt_text: string | null;
    description: string | null;
}

interface Gallery {
    id: number;
    name: string;
    description: string | null;
    images: Image[];
}

interface Project {
    id: number;
    title: string;
    slug: string;
    short_description: string;
    long_description: string | null;
    type: string;
    status: string;
    completion_date: string | null;
    project_url: string | null;
    github_url: string | null;
    main_image: string | null;
    technologies: Technology[];
    techniques: Technique[];
    galleries: Gallery[];
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

interface Props {
    user: User;
    information: Information;
    project: Project;
}

const props = defineProps<Props>();

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

const formatDate = (date: string | null) => {
    if (!date) return null;
    return new Date(date).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long'
    });
};
</script>

<template>
    <Head :title="`${project.title} - ${information.first_name} ${information.last_name}`" />

    <PublicLayout>
        <div class="min-h-screen" :style="{ backgroundColor: `${information.theme_color}26` }">
        <!-- Back Button -->
        <section class="border-b py-4">
            <div class="container mx-auto px-4">
                <Button as-child variant="ghost" size="sm">
                    <Link :href="`/portfolio/${user.slug}/projets`">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Retour aux projets
                    </Link>
                </Button>
            </div>
        </section>

        <!-- Project Header -->
        <section class="border-b bg-gradient-to-br from-primary/10 via-muted/50 to-background py-12">
            <div class="container mx-auto px-4">
                <div class="grid gap-8 md:grid-cols-2 items-center">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <Badge variant="secondary" class="bg-primary/10 text-primary hover:bg-primary/20">{{ getTypeLabel(project.type) }}</Badge>
                            <Badge v-if="project.completion_date" variant="outline" class="hover:bg-primary/10 hover:text-primary hover:border-primary/50 transition-colors">
                                <Calendar class="mr-1 h-3 w-3" />
                                {{ formatDate(project.completion_date) }}
                            </Badge>
                        </div>
                        <h1 class="text-4xl font-bold mb-4">{{ project.title }}</h1>
                        <p class="text-lg text-muted-foreground mb-6">{{ project.short_description }}</p>

                        <div class="flex flex-wrap gap-3">
                            <Button v-if="project.project_url" as-child size="lg" class="bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary/70 shadow-lg">
                                <a :href="project.project_url" target="_blank" rel="noopener noreferrer">
                                    Voir le projet
                                    <ExternalLink class="ml-2 h-4 w-4" />
                                </a>
                            </Button>
                            <Button v-if="project.github_url" as-child variant="outline" size="lg">
                                <a :href="project.github_url" target="_blank" rel="noopener noreferrer">
                                    Code source
                                    <ExternalLink class="ml-2 h-4 w-4" />
                                </a>
                            </Button>
                        </div>
                    </div>

                    <div v-if="project.main_image" class="order-first md:order-last">
                        <img
                            :src="`/storage/${project.main_image}`"
                            :alt="project.title"
                            class="w-full rounded-lg shadow-xl hover:shadow-2xl transition-shadow duration-300"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Description -->
        <section v-if="project.long_description" class="py-12 border-b">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-bold mb-6">Description détaillée</h2>
                    <div v-html="project.long_description" class="prose prose-slate max-w-none dark:prose-invert"></div>
                </div>
            </div>
        </section>

        <!-- Technologies & Techniques -->
        <section class="py-12 border-b bg-muted/30">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto grid gap-8" :class="{
                    'md:grid-cols-2': project.technologies.length > 0 && project.techniques.length > 0,
                    'md:grid-cols-1': project.technologies.length === 0 || project.techniques.length === 0
                }">
                    <!-- Technologies -->
                    <Card v-if="project.technologies.length > 0" class="hover:shadow-lg hover:border-primary/30 transition-all duration-300">
                        <CardHeader>
                            <CardTitle>Technologies utilisées</CardTitle>
                            <CardDescription>Stack technique du projet</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div v-for="tech in project.technologies" :key="tech.id" class="flex items-center gap-3">
                                    <div
                                        v-if="tech.logo_path"
                                        class="h-8 w-8 flex-shrink-0 rounded overflow-hidden"
                                        :style="{ backgroundColor: tech.color_code || '#e5e7eb' }"
                                    >
                                        <img :src="tech.logo_path" :alt="tech.name" class="h-full w-full object-contain p-1" />
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium">{{ tech.name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ tech.category }}</div>
                                    </div>
                                    <Badge variant="outline">{{ tech.proficiency_level }}/5</Badge>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Techniques -->
                    <Card v-if="project.techniques.length > 0" class="hover:shadow-lg hover:border-primary/30 transition-all duration-300">
                        <CardHeader>
                            <CardTitle>Techniques & Méthodes</CardTitle>
                            <CardDescription>Approches et méthodologies</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div v-for="technique in project.techniques" :key="technique.id" class="flex items-start gap-3">
                                    <div class="flex-1">
                                        <div class="font-medium">{{ technique.name }}</div>
                                        <div v-if="technique.description" class="text-sm text-muted-foreground">
                                            {{ technique.description }}
                                        </div>
                                    </div>
                                    <Badge variant="outline">{{ technique.proficiency_level }}/5</Badge>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </section>

        <!-- Galleries -->
        <section v-if="project.galleries.length > 0" class="py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <h2 class="text-2xl font-bold mb-8">Galeries d'images</h2>

                    <div class="space-y-12">
                        <div v-for="gallery in project.galleries" :key="gallery.id">
                            <h3 class="text-xl font-semibold mb-2">{{ gallery.name }}</h3>
                            <p v-if="gallery.description" class="text-muted-foreground mb-6">{{ gallery.description }}</p>

                            <div v-if="gallery.images.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div v-for="image in gallery.images" :key="image.id" class="group relative aspect-video overflow-hidden rounded-lg hover:shadow-xl hover:ring-2 hover:ring-primary/50 transition-all duration-300">
                                    <img
                                        :src="`/storage/${image.image_path}`"
                                        :alt="image.alt_text || gallery.name"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    />
                                    <div v-if="image.description" class="absolute bottom-0 left-0 right-0 bg-black/70 text-white p-2 text-sm">
                                        {{ image.description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-12 bg-muted/50">
            <div class="container mx-auto px-4">
                <div class="max-w-2xl mx-auto text-center">
                    <h2 class="text-2xl font-bold mb-4">Intéressé par mon travail ?</h2>
                    <p class="text-muted-foreground mb-6">N'hésitez pas à me contacter pour discuter de vos projets</p>
                    <Button as-child size="lg">
                        <Link :href="`/portfolio/${user.slug}/contact`">
                            Me contacter
                        </Link>
                    </Button>
                </div>
            </div>
        </section>
        </div>
    </PublicLayout>
</template>
