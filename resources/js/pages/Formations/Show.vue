<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface User {
    id: number;
    name: string;
}

interface Formation {
    id: number;
    degree: string;
    institution: string;
    location: string | null;
    start_date: string;
    end_date: string | null;
    description: string | null;
    display_order: number | null;
    user: User;
}

const props = defineProps<{
    formation: Formation;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Formations',
        href: '/formations',
    },
    {
        title: props.formation.degree,
        href: `/formations/${props.formation.id}`,
    },
];

const formatDate = (date: string | null) => {
    if (!date) return 'En cours';
    return new Date(date).toLocaleDateString('fr-FR', {
        month: 'long',
        year: 'numeric',
    });
};
</script>

<template>
    <Head :title="formation.degree" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ formation.degree }}</h1>
                <Button as-child>
                    <Link :href="`/formations/${formation.id}/edit`">Modifier</Link>
                </Button>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <!-- Informations générales -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informations générales</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-2">
                        <div>
                            <span class="font-semibold">Établissement:</span>
                            {{ formation.institution }}
                        </div>
                        <div v-if="formation.location">
                            <span class="font-semibold">Lieu:</span>
                            {{ formation.location }}
                        </div>
                        <div>
                            <span class="font-semibold">Période:</span>
                            {{ formatDate(formation.start_date) }} -
                            {{ formatDate(formation.end_date) }}
                        </div>
                        <div v-if="formation.display_order !== null">
                            <span class="font-semibold">Ordre d'affichage:</span>
                            {{ formation.display_order }}
                        </div>
                    </CardContent>
                </Card>

                <!-- Description -->
                <Card v-if="formation.description">
                    <CardHeader>
                        <CardTitle>Description</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-html="formation.description" class="prose prose-sm max-w-none"></div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
