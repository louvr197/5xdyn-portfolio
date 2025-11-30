<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
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
    display_order: number | null;
}

const props = defineProps<{
    technology: Technology;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Technologies',
        href: '/technologies',
    },
    {
        title: props.technology.name,
        href: `/technologies/${props.technology.id}`,
    },
];

const categoryLabels: Record<string, string> = {
    backend: 'Backend',
    frontend: 'Frontend',
    database: 'Base de données',
    devops: 'DevOps',
    design: 'Design',
    other: 'Autre',
};

const proficiencyLabels: Record<string, string> = {
    beginner: 'Débutant',
    intermediate: 'Intermédiaire',
    advanced: 'Avancé',
    expert: 'Expert',
};

const deleteTechnology = () => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette technologie ?')) {
        router.delete(`/technologies/${props.technology.id}`);
    }
};
</script>

<template>
    <Head :title="technology.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ technology.name }}</h1>
                <div class="flex gap-2">
                    <Button as-child variant="outline">
                        <Link :href="`/technologies/${technology.id}/edit`">Modifier</Link>
                    </Button>
                    <Button variant="destructive" @click="deleteTechnology">Supprimer</Button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <!-- Logo and Basic Info -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informations générales</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div v-if="technology.logo_path" class="flex justify-center">
                            <img
                                :src="`/storage/${technology.logo_path}`"
                                :alt="technology.name"
                                class="h-32 w-32 object-contain"
                            />
                        </div>
                        <div
                            v-else-if="technology.color_code"
                            class="flex justify-center"
                        >
                            <div
                                class="h-32 w-32 rounded-lg flex items-center justify-center text-4xl font-bold text-white"
                                :style="{ backgroundColor: technology.color_code }"
                            >
                                {{ technology.name.substring(0, 2).toUpperCase() }}
                            </div>
                        </div>

                        <div>
                            <span class="font-medium">Nom:</span>
                            <span class="ml-2">{{ technology.name }}</span>
                        </div>

                        <div>
                            <span class="font-medium">Catégorie:</span>
                            <span class="ml-2">{{ categoryLabels[technology.category] }}</span>
                        </div>

                        <div>
                            <span class="font-medium">Niveau de compétence:</span>
                            <Badge class="ml-2">
                                {{ proficiencyLabels[technology.proficiency_level] }}
                            </Badge>
                        </div>

                        <div v-if="technology.color_code">
                            <span class="font-medium">Code couleur:</span>
                            <div class="flex items-center gap-2 mt-1">
                                <div
                                    class="h-6 w-6 rounded border"
                                    :style="{ backgroundColor: technology.color_code }"
                                ></div>
                                <span class="text-sm">{{ technology.color_code }}</span>
                            </div>
                        </div>

                        <div v-if="technology.display_order !== null">
                            <span class="font-medium">Ordre d'affichage:</span>
                            <span class="ml-2">{{ technology.display_order }}</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- Projects using this technology -->
                <Card>
                    <CardHeader>
                        <CardTitle>Utilisation</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm text-muted-foreground">
                            Cette technologie peut être utilisée dans vos projets.
                        </p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
