<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

interface Technique {
    id: number;
    name: string;
    description: string | null;
    proficiency_level: string;
    display_order: number | null;
}

const props = defineProps<{
    technique: Technique;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Techniques',
        href: '/techniques',
    },
    {
        title: props.technique.name,
        href: `/techniques/${props.technique.id}`,
    },
];

const proficiencyLabels: Record<string, string> = {
    beginner: 'Débutant',
    intermediate: 'Intermédiaire',
    advanced: 'Avancé',
    expert: 'Expert',
};

const deleteTechnique = () => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette technique ?')) {
        router.delete(`/techniques/${props.technique.id}`);
    }
};
</script>

<template>
    <Head :title="technique.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ technique.name }}</h1>
                <div class="flex gap-2">
                    <Button as-child variant="outline">
                        <Link :href="`/techniques/${technique.id}/edit`">Modifier</Link>
                    </Button>
                    <Button variant="destructive" @click="deleteTechnique">Supprimer</Button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <!-- Basic Info -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informations générales</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <span class="font-medium">Nom:</span>
                            <span class="ml-2">{{ technique.name }}</span>
                        </div>

                        <div>
                            <span class="font-medium">Niveau de compétence:</span>
                            <Badge class="ml-2">
                                {{ proficiencyLabels[technique.proficiency_level] }}
                            </Badge>
                        </div>

                        <div v-if="technique.display_order !== null">
                            <span class="font-medium">Ordre d'affichage:</span>
                            <span class="ml-2">{{ technique.display_order }}</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- Description -->
                <Card v-if="technique.description">
                    <CardHeader>
                        <CardTitle>Description</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm">{{ technique.description }}</p>
                    </CardContent>
                </Card>

                <!-- Projects using this technique -->
                <Card>
                    <CardHeader>
                        <CardTitle>Utilisation</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm text-muted-foreground">
                            Cette technique peut être utilisée dans vos projets.
                        </p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
