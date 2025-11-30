<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ArrowRight, Code, Briefcase, GraduationCap } from 'lucide-vue-next';

interface Technology {
    id: number;
    name: string;
    logo_path?: string;
    color_code?: string;
}

interface Project {
    id: number;
    title: string;
    slug: string;
    short_description: string;
    type: string;
    main_image: string | null;
    technologies: Technology[];
}

interface Information {
    first_name: string;
    last_name: string;
    professional_title: string;
    picture_path: string | null;
}

interface User {
    id: number;
    slug: string;
}

interface Props {
    user: User;
    information: Information;
    featuredProjects: Project[];
    projectsCount: number;
    experiencesCount: number;
    formationsCount: number;
}

const props = defineProps<Props>();

const getTypeLabel = (type: string) => {
    const labels: Record<string, string> = {
        website: 'Site Web',
        mobile_app: 'Application Mobile',
        api: 'API',
        design: 'Design',
        other: 'Autre',
    };
    return labels[type] || type;
};
</script>

<template>
    <Head title="Accueil" />

    <PublicLayout>
        <div class="min-h-screen" :style="{ backgroundColor: `${information.theme_color}26` }">
        <!-- Hero Section -->
        <section class="relative py-20 md:py-32" aria-labelledby="intro-title">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center">
                    <!-- Profile Picture -->
                    <div v-if="information.picture_path" class="mb-8 flex justify-center">
                        <div class="h-32 w-32 rounded-full overflow-hidden ring-4 ring-primary/20 shadow-lg">
                            <img
                                :src="`/storage/${information.picture_path}`"
                                :alt="`Photo de profil de ${information.first_name} ${information.last_name}, ${information.professional_title}`"
                                class="h-full w-full object-cover"
                            />
                        </div>
                    </div>

                    <h1 id="intro-title" class="text-4xl md:text-6xl font-bold tracking-tight mb-6">
                        Bienvenue sur mon Portfolio
                    </h1>
                    <p class="text-xl text-muted-foreground mb-8">
                        Développeur passionné par la création d'expériences numériques innovantes
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Button as-child size="lg">
                            <Link :href="`/portfolio/${user.slug}/projets`" aria-label="Voir la liste de mes projets">
                                Voir mes projets
                                <ArrowRight class="ml-2 h-4 w-4" aria-hidden="true" />
                            </Link>
                        </Button>
                        <Button as-child variant="outline" size="lg">
                            <Link :href="`/portfolio/${user.slug}/contact`" aria-label="Accéder au formulaire de contact">
                                Me contacter
                            </Link>
                        </Button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-12 border-y bg-gradient-to-b from-primary/5 to-muted/30">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="flex flex-col items-center">
                        <div class="rounded-full bg-primary/10 p-4 mb-4">
                            <Code class="h-8 w-8 text-primary" />
                        </div>
                        <div class="text-3xl font-bold mb-1">{{ projectsCount }}</div>
                        <div class="text-muted-foreground">Projets réalisés</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="rounded-full bg-primary/10 p-4 mb-4">
                            <Briefcase class="h-8 w-8 text-primary" />
                        </div>
                        <div class="text-3xl font-bold mb-1">{{ experiencesCount }}</div>
                        <div class="text-muted-foreground">Expériences professionnelles</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="rounded-full bg-primary/10 p-4 mb-4">
                            <GraduationCap class="h-8 w-8 text-primary" />
                        </div>
                        <div class="text-3xl font-bold mb-1">{{ formationsCount }}</div>
                        <div class="text-muted-foreground">Formations</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Projects Section -->
        <section class="py-20 bg-gradient-to-b from-background to-muted/20">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Projets en vedette</h2>
                    <p class="text-lg text-muted-foreground max-w-2xl mx-auto">
                        Découvrez une sélection de mes réalisations récentes
                    </p>
                </div>

                <div v-if="featuredProjects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <Card v-for="project in featuredProjects" :key="project.id" class="overflow-hidden group hover:shadow-xl hover:border-primary/50 transition-all duration-300 hover:-translate-y-1">
                        <Link :href="`/portfolio/${user.slug}/projets/${project.slug}`">
                            <div class="aspect-video bg-gradient-to-br from-primary/10 to-muted relative overflow-hidden">
                                <img
                                    v-if="project.main_image"
                                    :src="`/storage/${project.main_image}`"
                                    :alt="project.title"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center">
                                    <Code class="h-12 w-12 text-primary/40" />
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
                                <Badge variant="secondary" class="shrink-0 bg-primary/10 text-primary hover:bg-primary/20">
                                    {{ getTypeLabel(project.type) }}
                                </Badge>
                            </div>
                            <CardDescription class="line-clamp-2">
                                {{ project.short_description }}
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div v-if="project.technologies.length > 0" class="flex flex-wrap gap-2">
                                <Badge
                                    v-for="tech in project.technologies.slice(0, 4)"
                                    :key="tech.id"
                                    variant="outline"
                                    class="text-xs hover:bg-primary/10 hover:text-primary hover:border-primary/50 transition-colors"
                                >
                                    {{ tech.name }}
                                </Badge>
                                <Badge v-if="project.technologies.length > 4" variant="outline" class="text-xs hover:bg-primary/10 hover:text-primary hover:border-primary/50 transition-colors">
                                    +{{ project.technologies.length - 4 }}
                                </Badge>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <div v-else class="text-center py-12">
                    <p class="text-muted-foreground">Aucun projet à afficher pour le moment.</p>
                </div>

                <div class="text-center">
                    <Button as-child variant="outline" size="lg">
                        <Link :href="`/portfolio/${user.slug}/projets`">
                            Voir tous les projets
                            <ArrowRight class="ml-2 h-4 w-4" />
                        </Link>
                    </Button>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-b from-muted/30 to-primary/5">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        Vous avez un projet en tête ?
                    </h2>
                    <p class="text-lg text-muted-foreground mb-8">
                        N'hésitez pas à me contacter pour discuter de vos besoins
                    </p>
                    <Button as-child size="lg" class="bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary/70 shadow-lg">
                        <Link :href="`/portfolio/${user.slug}/contact`">
                            Contactez-moi
                        </Link>
                    </Button>
                </div>
            </div>
        </section>
        </div>
    </PublicLayout>
</template>
