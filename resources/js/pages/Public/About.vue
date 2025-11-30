<script setup lang="ts">
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Calendar, Download, MapPin, Briefcase, GraduationCap, Github, Linkedin, Globe, Twitter, Instagram, Facebook, Youtube, Mail, Phone, ExternalLink } from 'lucide-vue-next';

interface User {
    id: number;
    name: string;
    email: string;
    slug: string;
}

interface Information {
    first_name: string;
    last_name: string;
    title: string;
    bio: string | null;
    phone: string | null;
    email: string;
    location: string | null;
    availability: string | null;
    website: string | null;
    linkedin: string | null;
    github: string | null;
    cv_path: string | null;
    picture_path: string | null;
    social_links: Record<string, string> | null;
}

interface Experience {
    id: number;
    company: string;
    position: string;
    start_date: string;
    end_date: string | null;
    description: string | null;
    location: string | null;
    display_order: number;
}

interface Formation {
    id: number;
    institution: string;
    degree: string;
    field: string | null;
    start_date: string;
    end_date: string | null;
    description: string | null;
    location: string | null;
    display_order: number;
}

interface Technology {
    id: number;
    name: string;
    category: string;
    proficiency_level: number | null;
    logo_path: string | null;
}

interface Props {
    user: User;
    information: Information;
    experiences: Experience[];
    formations: Formation[];
    technologies: Record<string, Technology[]>;
}

const props = defineProps<Props>();

const page = usePage();

const formatDate = (date: string | null): string => {
    if (!date) return 'Présent';
    const d = new Date(date);
    return d.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
};

const getCategoryLabel = (category: string): string => {
    const labels: Record<string, string> = {
        backend: 'Backend',
        frontend: 'Frontend',
        database: 'Base de données',
        devops: 'DevOps',
        design: 'Design',
        other: 'Autres',
    };
    return labels[category] || category;
};

const getProficiencyLabel = (level: number | null): string => {
    if (!level) return '';
    if (level >= 80) return 'Expert';
    if (level >= 60) return 'Avancé';
    if (level >= 40) return 'Intermédiaire';
    return 'Débutant';
};

const hasCv = computed(() => props.information.cv_path);

const totalExperience = computed(() => {
    let years = 0;
    props.experiences.forEach(exp => {
        const start = new Date(exp.start_date);
        const end = exp.end_date ? new Date(exp.end_date) : new Date();
        years += (end.getTime() - start.getTime()) / (1000 * 60 * 60 * 24 * 365);
    });
    return Math.round(years);
});

const allSocialLinks = computed(() => {
    const links = [];
    if (props.information.linkedin) links.push({ name: 'LinkedIn', url: props.information.linkedin, component: Linkedin });
    if (props.information.github) links.push({ name: 'GitHub', url: props.information.github, component: Github });
    if (props.information.website) links.push({ name: 'Site web', url: props.information.website, component: Globe });
    if (props.information.social_links) {
        Object.entries(props.information.social_links).forEach(([key, value]) => {
            if (value && !['linkedin', 'github'].includes(key.toLowerCase())) {
                const components: Record<string, any> = {
                    twitter: Twitter,
                    instagram: Instagram,
                    facebook: Facebook,
                    youtube: Youtube,
                    behance: Globe,
                    dribbble: Globe,
                };
                links.push({
                    name: key.charAt(0).toUpperCase() + key.slice(1),
                    url: value,
                    component: components[key.toLowerCase()] || Globe
                });
            }
        });
    }
    return links;
});
</script>

<template>
    <Head :title="`À propos - ${user.name}`" />

    <PublicLayout>
        <div class="min-h-screen" :style="{ backgroundColor: `${information.theme_color}26` }">
        <div class="container mx-auto px-4 py-12 max-w-6xl">
            <!-- Header Section -->
            <section class="mb-12" aria-labelledby="about-title">
                <div class="bg-gradient-to-br from-primary/10 via-primary/5 to-background rounded-2xl p-8 md:p-12">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                        <!-- Profile Picture -->
                        <div v-if="information.picture_path" class="flex-shrink-0">
                            <div class="h-40 w-40 rounded-full overflow-hidden ring-4 ring-primary/30 shadow-xl">
                                <img
                                    :src="`/storage/${information.picture_path}`"
                                    :alt="`${information.first_name} ${information.last_name}`"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 text-center md:text-left">
                            <h1 class="text-4xl md:text-5xl font-bold mb-2">
                                {{ information.first_name }} {{ information.last_name }}
                            </h1>
                            <p class="text-2xl text-primary font-semibold mb-4">
                                {{ information.title }}
                            </p>
                            <div v-if="information.bio" class="text-muted-foreground mb-6">
                                <p class="text-lg leading-relaxed">{{ information.bio }}</p>
                            </div>

                            <!-- Quick Stats -->
                            <div class="flex flex-wrap gap-4 justify-center md:justify-start mb-6">
                                <Badge v-if="totalExperience > 0" variant="secondary" class="text-sm px-4 py-2">
                                    {{ totalExperience }} an{{ totalExperience > 1 ? 's' : '' }} d'expérience
                                </Badge>
                                <Badge v-if="experiences.length > 0" variant="secondary" class="text-sm px-4 py-2">
                                    {{ experiences.length }} expérience{{ experiences.length > 1 ? 's' : '' }}
                                </Badge>
                                <Badge v-if="formations.length > 0" variant="secondary" class="text-sm px-4 py-2">
                                    {{ formations.length }} formation{{ formations.length > 1 ? 's' : '' }}
                                </Badge>
                                <Badge v-if="information.availability" variant="outline" class="text-sm px-4 py-2">
                                    {{ information.availability }}
                                </Badge>
                            </div>

                            <!-- Social Links -->
                            <div v-if="allSocialLinks.length > 0" class="flex flex-wrap gap-3 justify-center md:justify-start">
                                <Button
                                    v-for="link in allSocialLinks"
                                    :key="link.name"
                                    as-child
                                    variant="outline"
                                    size="sm"
                                >
                                    <a :href="link.url" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2">
                                        <component :is="link.component" class="w-4 h-4" />
                                        <span>{{ link.name }}</span>
                                    </a>
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold mb-6">Restons en contact</h2>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Contact Direct -->
                    <Card class="lg:col-span-2">
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Mail class="w-5 h-5" />
                                Coordonnées
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Email -->
                                <div v-if="information.email" class="space-y-2">
                                    <div class="flex items-center gap-2 text-muted-foreground text-sm">
                                        <Mail class="w-4 h-4" />
                                        <span class="font-medium">Email</span>
                                    </div>
                                    <a
                                        :href="`mailto:${information.email}`"
                                        class="text-lg text-primary hover:underline flex items-center gap-2 group"
                                    >
                                        {{ information.email }}
                                        <ExternalLink class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" />
                                    </a>
                                </div>

                                <!-- Phone -->
                                <div v-if="information.phone" class="space-y-2">
                                    <div class="flex items-center gap-2 text-muted-foreground text-sm">
                                        <Phone class="w-4 h-4" />
                                        <span class="font-medium">Téléphone</span>
                                    </div>
                                    <a
                                        :href="`tel:${information.phone}`"
                                        class="text-lg text-primary hover:underline flex items-center gap-2 group"
                                    >
                                        {{ information.phone }}
                                        <ExternalLink class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" />
                                    </a>
                                </div>

                                <!-- Location -->
                                <div v-if="information.location" class="space-y-2">
                                    <div class="flex items-center gap-2 text-muted-foreground text-sm">
                                        <MapPin class="w-4 h-4" />
                                        <span class="font-medium">Localisation</span>
                                    </div>
                                    <p class="text-lg">{{ information.location }}</p>
                                </div>

                                <!-- Website -->
                                <div v-if="information.website" class="space-y-2">
                                    <div class="flex items-center gap-2 text-muted-foreground text-sm">
                                        <Globe class="w-4 h-4" />
                                        <span class="font-medium">Site web</span>
                                    </div>
                                    <a
                                        :href="information.website"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-lg text-primary hover:underline flex items-center gap-2 group"
                                    >
                                        {{ information.website.replace(/^https?:\/\/(www\.)?/, '') }}
                                        <ExternalLink class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" />
                                    </a>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Quick Action -->
                    <Card class="flex flex-col">
                        <CardHeader>
                            <CardTitle>Actions rapides</CardTitle>
                        </CardHeader>
                        <CardContent class="flex-1 flex flex-col gap-4">
                            <Button as-child size="lg" class="w-full">
                                <a :href="`/portfolio/${user.slug}/contact`">
                                    <Mail class="w-4 h-4 mr-2" />
                                    Envoyer un message
                                </a>
                            </Button>

                            <Button v-if="hasCv" as-child variant="outline" size="lg" class="w-full">
                                <a :href="`/storage/${information.cv_path}`" download>
                                    <Download class="w-4 h-4 mr-2" />
                                    Télécharger le CV
                                </a>
                            </Button>

                            <!-- Professional Links -->
                            <div v-if="information.linkedin || information.github" class="pt-4 border-t space-y-3">
                                <p class="text-sm font-medium text-muted-foreground">Profils professionnels</p>
                                <div class="flex flex-col gap-2">
                                    <Button
                                        v-if="information.linkedin"
                                        as-child
                                        variant="ghost"
                                        size="sm"
                                        class="w-full justify-start"
                                    >
                                        <a :href="information.linkedin" target="_blank" rel="noopener noreferrer">
                                            <Linkedin class="w-4 h-4 mr-2" />
                                            LinkedIn
                                        </a>
                                    </Button>
                                    <Button
                                        v-if="information.github"
                                        as-child
                                        variant="ghost"
                                        size="sm"
                                        class="w-full justify-start"
                                    >
                                        <a :href="information.github" target="_blank" rel="noopener noreferrer">
                                            <Github class="w-4 h-4 mr-2" />
                                            GitHub
                                        </a>
                                    </Button>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Experiences Section -->
            <div v-if="experiences.length > 0" class="mb-12">
                <h2 class="text-3xl font-bold mb-6 flex items-center gap-2">
                    <Briefcase class="w-8 h-8" />
                    Expériences professionnelles
                </h2>
                <div class="space-y-6">
                    <Card v-for="experience in experiences" :key="experience.id">
                        <CardHeader>
                            <CardTitle>{{ experience.position }}</CardTitle>
                            <CardDescription class="flex flex-col gap-2 mt-2">
                                <span class="text-lg font-semibold text-foreground">{{ experience.company }}</span>
                                <div class="flex flex-wrap gap-4 text-sm">
                                    <span class="flex items-center gap-1">
                                        <Calendar class="w-4 h-4" />
                                        {{ formatDate(experience.start_date) }} - {{ formatDate(experience.end_date) }}
                                    </span>
                                    <span v-if="experience.location" class="flex items-center gap-1">
                                        <MapPin class="w-4 h-4" />
                                        {{ experience.location }}
                                    </span>
                                </div>
                            </CardDescription>
                        </CardHeader>
                        <CardContent v-if="experience.description">
                            <p class="text-muted-foreground">{{ experience.description }}</p>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Formations Section -->
            <div v-if="formations.length > 0" class="mb-12">
                <h2 class="text-3xl font-bold mb-6 flex items-center gap-2">
                    <GraduationCap class="w-8 h-8" />
                    Formation
                </h2>
                <div class="space-y-6">
                    <Card v-for="formation in formations" :key="formation.id">
                        <CardHeader>
                            <CardTitle>{{ formation.degree }}</CardTitle>
                            <CardDescription class="flex flex-col gap-2 mt-2">
                                <span class="text-lg font-semibold text-foreground">{{ formation.institution }}</span>
                                <div class="flex flex-wrap gap-4 text-sm">
                                    <span v-if="formation.field" class="font-medium text-foreground">
                                        {{ formation.field }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <Calendar class="w-4 h-4" />
                                        {{ formatDate(formation.start_date) }} - {{ formatDate(formation.end_date) }}
                                    </span>
                                    <span v-if="formation.location" class="flex items-center gap-1">
                                        <MapPin class="w-4 h-4" />
                                        {{ formation.location }}
                                    </span>
                                </div>
                            </CardDescription>
                        </CardHeader>
                        <CardContent v-if="formation.description">
                            <p class="text-muted-foreground">{{ formation.description }}</p>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Technologies Section -->
            <div v-if="Object.keys(technologies).length > 0" class="mb-12">
                <h2 class="text-3xl font-bold mb-6">Compétences techniques</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <Card v-for="(techs, category) in technologies" :key="category">
                        <CardHeader>
                            <CardTitle>{{ getCategoryLabel(category) }}</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div v-for="tech in techs" :key="tech.id" class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <img
                                                v-if="tech.logo_path"
                                                :src="`/storage/${tech.logo_path}`"
                                                :alt="tech.name"
                                                class="w-6 h-6 object-contain"
                                            />
                                            <span class="font-medium">{{ tech.name }}</span>
                                        </div>
                                        <Badge v-if="tech.proficiency_level" variant="secondary">
                                            {{ getProficiencyLabel(tech.proficiency_level) }}
                                        </Badge>
                                    </div>
                                    <div v-if="tech.proficiency_level" class="w-full bg-secondary h-2 rounded-full overflow-hidden">
                                        <div
                                            class="bg-primary h-full transition-all"
                                            :style="{ width: `${tech.proficiency_level}%` }"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- CTA Section -->
            <Card class="text-center">
                <CardContent class="pt-6">
                    <h3 class="text-2xl font-bold mb-4">Intéressé par mon profil ?</h3>
                    <p class="text-muted-foreground mb-6">
                        N'hésitez pas à me contacter pour discuter de vos projets ou opportunités.
                    </p>
                    <Button as-child size="lg">
                        <a :href="`/portfolio/${user.slug}/contact`">
                            Me contacter
                        </a>
                    </Button>
                </CardContent>
            </Card>
        </div>
        </div>
    </PublicLayout>
</template>
