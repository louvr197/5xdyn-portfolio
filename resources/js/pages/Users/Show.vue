<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    projects: Array<{
        id: number;
        title: string;
        slug: string;
    }>;
    experiences: Array<{
        id: number;
        title: string;
        company: string;
    }>;
    formations: Array<{
        id: number;
        title: string;
        institution: string;
    }>;
    information: {
        bio: string | null;
        phone: string | null;
        address: string | null;
        website: string | null;
        cv_path: string | null;
        social_links: Record<string, string> | null;
    } | null;
}

const props = defineProps<{
    user: User;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Utilisateurs',
        href: '/users',
    },
    {
        title: props.user.name,
        href: `/users/${props.user.id}`,
    },
];
</script>

<template>
    <Head :title="user.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ user.name }}</h1>
                <Button as-child variant="outline">
                    <Link :href="`/users/${user.id}/edit`">
                        Modifier
                    </Link>
                </Button>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Informations générales</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-2">
                        <div>
                            <span class="font-medium">Email:</span>
                            {{ user.email }}
                        </div>
                        <div v-if="user.email_verified_at">
                            <span class="font-medium">Vérifié le:</span>
                            {{ new Date(user.email_verified_at).toLocaleDateString('fr-FR') }}
                        </div>
                        <div>
                            <span class="font-medium">Inscrit le:</span>
                            {{ new Date(user.created_at).toLocaleDateString('fr-FR') }}
                        </div>
                    </CardContent>
                </Card>

                <Card v-if="user.information">
                    <CardHeader>
                        <CardTitle>Coordonnées</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-2">
                        <div v-if="user.information.phone">
                            <span class="font-medium">Téléphone:</span>
                            {{ user.information.phone }}
                        </div>
                        <div v-if="user.information.address">
                            <span class="font-medium">Adresse:</span>
                            {{ user.information.address }}
                        </div>
                        <div v-if="user.information.website">
                            <span class="font-medium">Site web:</span>
                            <a :href="user.information.website" target="_blank" class="text-blue-600 hover:underline">
                                {{ user.information.website }}
                            </a>
                        </div>
                        <div v-if="user.information.social_links">
                            <span class="font-medium">Réseaux sociaux:</span>
                            <div class="flex gap-2 mt-1">
                                <a
                                    v-for="(url, platform) in user.information.social_links"
                                    :key="platform"
                                    :href="url"
                                    target="_blank"
                                    class="text-blue-600 hover:underline capitalize"
                                >
                                    {{ platform }}
                                </a>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <Card v-if="user.information?.bio">
                <CardHeader>
                    <CardTitle>Biographie</CardTitle>
                </CardHeader>
                <CardContent>
                    <p class="whitespace-pre-wrap">{{ user.information.bio }}</p>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Projets ({{ user.projects.length }})</CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="user.projects.length > 0" class="space-y-2">
                        <div
                            v-for="project in user.projects"
                            :key="project.id"
                            class="flex items-center justify-between border-b pb-2 last:border-0"
                        >
                            <span>{{ project.title }}</span>
                            <Button as-child variant="ghost" size="sm">
                                <Link :href="`/projects/${project.slug}`">
                                    Voir
                                </Link>
                            </Button>
                        </div>
                    </div>
                    <p v-else class="text-muted-foreground">Aucun projet</p>
                </CardContent>
            </Card>

            <div class="grid gap-4 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Expériences ({{ user.experiences.length }})</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="user.experiences.length > 0" class="space-y-2">
                            <div
                                v-for="experience in user.experiences"
                                :key="experience.id"
                                class="border-b pb-2 last:border-0"
                            >
                                <div class="font-medium">{{ experience.title }}</div>
                                <div class="text-sm text-muted-foreground">{{ experience.company }}</div>
                            </div>
                        </div>
                        <p v-else class="text-muted-foreground">Aucune expérience</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Formations ({{ user.formations.length }})</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="user.formations.length > 0" class="space-y-2">
                            <div
                                v-for="formation in user.formations"
                                :key="formation.id"
                                class="border-b pb-2 last:border-0"
                            >
                                <div class="font-medium">{{ formation.title }}</div>
                                <div class="text-sm text-muted-foreground">{{ formation.institution }}</div>
                            </div>
                        </div>
                        <p v-else class="text-muted-foreground">Aucune formation</p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
