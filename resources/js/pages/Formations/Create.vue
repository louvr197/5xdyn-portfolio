<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import TiptapEditor from '@/components/TiptapEditor.vue';

const form = useForm({
    degree: '',
    institution: '',
    location: '',
    start_date: '',
    end_date: '',
    description: '',
    display_order: null as number | null,
});

const submit = () => {
    form.post('/formations');
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Formations',
        href: '/formations',
    },
    {
        title: 'Nouvelle formation',
        href: '/formations/create',
    },
];
</script>

<template>
    <Head title="Nouvelle formation" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Nouvelle formation</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Card 1: Informations principales -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informations principales</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <!-- Degree -->
                        <div class="space-y-2">
                            <Label for="degree">Diplôme / Titre *</Label>
                            <Input
                                id="degree"
                                v-model="form.degree"
                                type="text"
                                required
                                placeholder="Ex: Master Informatique"
                                :class="{ 'border-red-500': form.errors.degree }"
                            />
                            <p v-if="form.errors.degree" class="text-sm text-red-500">
                                {{ form.errors.degree }}
                            </p>
                        </div>

                        <!-- Institution -->
                        <div class="space-y-2">
                            <Label for="institution">Établissement *</Label>
                            <Input
                                id="institution"
                                v-model="form.institution"
                                type="text"
                                required
                                placeholder="Ex: Université Paris-Saclay"
                                :class="{ 'border-red-500': form.errors.institution }"
                            />
                            <p v-if="form.errors.institution" class="text-sm text-red-500">
                                {{ form.errors.institution }}
                            </p>
                        </div>

                        <!-- Location -->
                        <div class="space-y-2">
                            <Label for="location">Lieu</Label>
                            <Input
                                id="location"
                                v-model="form.location"
                                type="text"
                                placeholder="Ex: Paris, France"
                                :class="{ 'border-red-500': form.errors.location }"
                            />
                            <p v-if="form.errors.location" class="text-sm text-red-500">
                                {{ form.errors.location }}
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <TiptapEditor
                                v-model="form.description"
                                placeholder="Décrivez le programme, les compétences acquises..."
                            />
                            <p v-if="form.errors.description" class="text-sm text-red-500">
                                {{ form.errors.description }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Card 2: Dates et ordre -->
                <Card>
                    <CardHeader>
                        <CardTitle>Période et affichage</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid gap-4 md:grid-cols-2">
                            <!-- Start Date -->
                            <div class="space-y-2">
                                <Label for="start_date">Date de début *</Label>
                                <Input
                                    id="start_date"
                                    v-model="form.start_date"
                                    type="date"
                                    required
                                    :class="{ 'border-red-500': form.errors.start_date }"
                                />
                                <p v-if="form.errors.start_date" class="text-sm text-red-500">
                                    {{ form.errors.start_date }}
                                </p>
                            </div>

                            <!-- End Date -->
                            <div class="space-y-2">
                                <Label for="end_date">Date de fin</Label>
                                <Input
                                    id="end_date"
                                    v-model="form.end_date"
                                    type="date"
                                    :class="{ 'border-red-500': form.errors.end_date }"
                                />
                                <p v-if="form.errors.end_date" class="text-sm text-red-500">
                                    {{ form.errors.end_date }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Laissez vide si en cours
                                </p>
                            </div>
                        </div>

                        <!-- Display Order -->
                        <div class="space-y-2">
                            <Label for="display_order">Ordre d'affichage</Label>
                            <Input
                                id="display_order"
                                v-model="form.display_order"
                                type="number"
                                placeholder="Ex: 1, 2, 3..."
                                :class="{ 'border-red-500': form.errors.display_order }"
                            />
                            <p v-if="form.errors.display_order" class="text-sm text-red-500">
                                {{ form.errors.display_order }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Utilisé pour trier les formations (optionnel)
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Submit Buttons -->
                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Création...' : 'Créer la formation' }}
                    </Button>
                    <Button type="button" variant="outline" as-child>
                        <a href="/formations">Annuler</a>
                    </Button>
                </div>

                <p v-if="form.recentlySuccessful" class="text-sm text-green-600">
                    Formation créée avec succès.
                </p>
            </form>
        </div>
    </AppLayout>
</template>
