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

interface Experience {
    id: number;
    position: string;
    company: string;
    location: string | null;
    start_date: string;
    end_date: string | null;
    description: string | null;
    contract_type: string | null;
    display_order: number | null;
    user: User;
}

const props = defineProps<{
    experience: Experience;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Expériences',
        href: '/experiences',
    },
    {
        title: props.experience.position,
        href: `/experiences/${props.experience.id}`,
    },
];

const formatDate = (date: string | null) => {
    if (!date) return "Aujourd'hui";
    return new Date(date).toLocaleDateString('fr-FR', {
        month: 'long',
        year: 'numeric',
    });
};
</script>

<template>
    <Head :title="experience.position" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ experience.position }}</h1>
                <Button as-child>
                    <Link :href="`/experiences/${experience.id}/edit`">Modifier</Link>
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
                            <span class="font-semibold">Entreprise:</span>
                            {{ experience.company }}
                        </div>
                        <div v-if="experience.location">
                            <span class="font-semibold">Lieu:</span>
                            {{ experience.location }}
                        </div>
                        <div v-if="experience.contract_type">
                            <span class="font-semibold">Type de contrat:</span>
                            {{ experience.contract_type }}
                        </div>
                        <div>
                            <span class="font-semibold">Période:</span>
                            {{ formatDate(experience.start_date) }} -
                            {{ formatDate(experience.end_date) }}
                        </div>
                        <div v-if="experience.display_order !== null">
                            <span class="font-semibold">Ordre d'affichage:</span>
                            {{ experience.display_order }}
                        </div>
                    </CardContent>
                </Card>

                <!-- Description -->
                <Card v-if="experience.description">
                    <CardHeader>
                        <CardTitle>Description</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-html="experience.description" class="prose prose-sm max-w-none"></div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
