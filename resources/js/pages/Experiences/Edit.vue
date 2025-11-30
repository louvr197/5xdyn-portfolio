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
}

const props = defineProps<{
    experience: Experience;
}>();

const form = useForm({
    position: props.experience.position,
    company: props.experience.company,
    location: props.experience.location || '',
    contract_type: props.experience.contract_type || '',
    start_date: props.experience.start_date,
    end_date: props.experience.end_date || '',
    description: props.experience.description || '',
    display_order: props.experience.display_order,
});

const submit = () => {
    form.put(`/experiences/${props.experience.id}`);
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Expériences',
        href: '/experiences',
    },
    {
        title: props.experience.position,
        href: `/experiences/${props.experience.id}`,
    },
    {
        title: 'Modifier',
        href: `/experiences/${props.experience.id}/edit`,
    },
];
</script>

<template>
    <Head :title="`Modifier ${experience.position}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Modifier {{ experience.position }}</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Card 1: Informations principales -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informations principales</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <!-- Position -->
                        <div class="space-y-2">
                            <Label for="position">Poste *</Label>
                            <Input
                                id="position"
                                v-model="form.position"
                                type="text"
                                required
                                placeholder="Ex: Développeur Full Stack"
                                :class="{ 'border-red-500': form.errors.position }"
                            />
                            <p v-if="form.errors.position" class="text-sm text-red-500">
                                {{ form.errors.position }}
                            </p>
                        </div>

                        <!-- Company -->
                        <div class="space-y-2">
                            <Label for="company">Entreprise *</Label>
                            <Input
                                id="company"
                                v-model="form.company"
                                type="text"
                                required
                                placeholder="Ex: Acme Corporation"
                                :class="{ 'border-red-500': form.errors.company }"
                            />
                            <p v-if="form.errors.company" class="text-sm text-red-500">
                                {{ form.errors.company }}
                            </p>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
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

                            <!-- Contract Type -->
                            <div class="space-y-2">
                                <Label for="contract_type">Type de contrat</Label>
                                <Input
                                    id="contract_type"
                                    v-model="form.contract_type"
                                    type="text"
                                    placeholder="Ex: CDI, CDD, Stage, Freelance"
                                    :class="{ 'border-red-500': form.errors.contract_type }"
                                />
                                <p v-if="form.errors.contract_type" class="text-sm text-red-500">
                                    {{ form.errors.contract_type }}
                                </p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <TiptapEditor
                                v-model="form.description"
                                placeholder="Décrivez vos missions, responsabilités, réalisations..."
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
                                Utilisé pour trier les expériences (optionnel)
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Submit Buttons -->
                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Modification...' : "Modifier l'expérience" }}
                    </Button>
                    <Button type="button" variant="outline" as-child>
                        <a :href="`/experiences/${experience.id}`">Annuler</a>
                    </Button>
                </div>

                <p v-if="form.recentlySuccessful" class="text-sm text-green-600">
                    Expérience modifiée avec succès.
                </p>
            </form>
        </div>
    </AppLayout>
</template>
