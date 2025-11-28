<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { ref } from 'vue';

interface Technology {
    id: number;
    name: string;
}

interface Technique {
    id: number;
    name: string;
}

interface Project {
    id: number;
    title: string;
    slug: string;
    short_description: string;
    long_description: string | null;
    status: string;
    type: string;
    context: string | null;
    completion_date: string | null;
    main_image: string | null;
    technologies: Technology[];
    techniques: Technique[];
}

const props = defineProps<{
    project: Project;
    technologies: Technology[];
    techniques: Technique[];
}>();

const showTechnologies = ref(true);

const form = useForm({
    title: props.project.title,
    short_description: props.project.short_description,
    long_description: props.project.long_description || '',
    status: props.project.status,
    type: props.project.type,
    context: props.project.context || '',
    completion_date: props.project.completion_date || '',
    main_image: null as File | null,
    technology_ids: props.project.technologies.map(tech => tech.id),
    technique_ids: props.project.techniques.map(tech => tech.id),
});

const submit = () => {
    form.put(`/projects/${props.project.slug}`);
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Projets',
        href: '/projects',
    },
    {
        title: props.project.title,
        href: `/projects/${props.project.slug}`,
    },
    {
        title: 'Modifier',
        href: `/projects/${props.project.slug}/edit`,
    },
];
</script>

<template>
    <Head :title="`Modifier ${project.title}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Modifier {{ project.title }}</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Card 1: Informations de base -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informations de base</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <!-- Title -->
                        <div class="space-y-2">
                            <Label for="title">Titre *</Label>
                            <Input
                                id="title"
                                v-model="form.title"
                                type="text"
                                required
                                :class="{ 'border-red-500': form.errors.title }"
                            />
                            <p v-if="form.errors.title" class="text-sm text-red-500">
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <!-- Short Description -->
                        <div class="space-y-2">
                            <Label for="short_description">Description courte *</Label>
                            <Textarea
                                id="short_description"
                                v-model="form.short_description"
                                required
                                rows="3"
                                :class="{ 'border-red-500': form.errors.short_description }"
                            />
                            <p v-if="form.errors.short_description" class="text-sm text-red-500">
                                {{ form.errors.short_description }}
                            </p>
                        </div>

                        <!-- Long Description -->
                        <div class="space-y-2">
                            <Label for="long_description">Description détaillée</Label>
                            <Textarea
                                id="long_description"
                                v-model="form.long_description"
                                rows="8"
                                :class="{ 'border-red-500': form.errors.long_description }"
                            />
                            <p v-if="form.errors.long_description" class="text-sm text-red-500">
                                {{ form.errors.long_description }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Card 2: Détails du projet -->
                <Card>
                    <CardHeader>
                        <CardTitle>Détails du projet</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <!-- Type -->
                        <div class="space-y-2">
                            <Label for="type">Type *</Label>
                            <Select v-model="form.type">
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner un type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="website">Site Web</SelectItem>
                                    <SelectItem value="mobile_app">Application Mobile</SelectItem>
                                    <SelectItem value="api">API</SelectItem>
                                    <SelectItem value="design">Design</SelectItem>
                                    <SelectItem value="other">Autre</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Status -->
                        <div class="space-y-2">
                            <Label for="status">Statut *</Label>
                            <Select v-model="form.status">
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner un statut" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="draft">Brouillon</SelectItem>
                                    <SelectItem value="published">Publié</SelectItem>
                                    <SelectItem value="archived">Archivé</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Context -->
                        <div class="space-y-2">
                            <Label for="context">Contexte</Label>
                            <Select v-model="form.context">
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner un contexte" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="academic">Académique</SelectItem>
                                    <SelectItem value="internship">Stage</SelectItem>
                                    <SelectItem value="freelance">Freelance</SelectItem>
                                    <SelectItem value="personal">Personnel</SelectItem>
                                    <SelectItem value="professional">Professionnel</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Completion Date -->
                        <div class="space-y-2">
                            <Label for="completion_date">Date d'achèvement</Label>
                            <Input
                                id="completion_date"
                                v-model="form.completion_date"
                                type="date"
                                :class="{ 'border-red-500': form.errors.completion_date }"
                            />
                            <p v-if="form.errors.completion_date" class="text-sm text-red-500">
                                {{ form.errors.completion_date }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Card 3: Image principale -->
                <Card>
                    <CardHeader>
                        <CardTitle>Image principale</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <!-- Current Image Display -->
                        <div v-if="project.main_image" class="mb-4">
                            <Label class="mb-2 block">Image actuelle</Label>
                            <img
                                :src="`/storage/${project.main_image}`"
                                :alt="project.title"
                                class="h-48 w-auto object-cover rounded"
                            />
                        </div>

                        <!-- Image Upload -->
                        <div class="space-y-2">
                            <Label for="main_image">Nouvelle image (max 5MB)</Label>
                            <Input
                                id="main_image"
                                type="file"
                                accept="image/*"
                                @change="(e) => {
                                    const target = e.target as HTMLInputElement;
                                    form.main_image = target.files?.[0] || null;
                                }"
                                :class="{ 'border-red-500': form.errors.main_image }"
                            />
                            <p v-if="form.errors.main_image" class="text-sm text-red-500">
                                {{ form.errors.main_image }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Laissez vide pour conserver l'image actuelle
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Card 4: Technologies / Techniques -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <CardTitle>{{ showTechnologies ? 'Technologies' : 'Techniques' }}</CardTitle>
                        <Button
                            type="button"
                            variant="outline"
                            size="sm"
                            @click="showTechnologies = !showTechnologies"
                        >
                            Afficher {{ showTechnologies ? 'Techniques' : 'Technologies' }}
                        </Button>
                    </CardHeader>
                    <CardContent>
                        <!-- Technologies -->
                        <div v-if="showTechnologies" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div
                                v-for="tech in technologies"
                                :key="tech.id"
                                class="flex items-center space-x-2"
                            >
                                <Checkbox
                                    :id="`tech-${tech.id}`"
                                    :checked="form.technology_ids.includes(tech.id)"
                                    @update:checked="(checked) => {
                                        if (checked) {
                                            form.technology_ids.push(tech.id);
                                        } else {
                                            form.technology_ids = form.technology_ids.filter(id => id !== tech.id);
                                        }
                                    }"
                                />
                                <Label :for="`tech-${tech.id}`" class="cursor-pointer">
                                    {{ tech.name }}
                                </Label>
                            </div>
                        </div>

                        <!-- Techniques -->
                        <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div
                                v-for="technique in techniques"
                                :key="technique.id"
                                class="flex items-center space-x-2"
                            >
                                <Checkbox
                                    :id="`technique-${technique.id}`"
                                    :checked="form.technique_ids.includes(technique.id)"
                                    @update:checked="(checked) => {
                                        if (checked) {
                                            form.technique_ids.push(technique.id);
                                        } else {
                                            form.technique_ids = form.technique_ids.filter(id => id !== technique.id);
                                        }
                                    }"
                                />
                                <Label :for="`technique-${technique.id}`" class="cursor-pointer">
                                    {{ technique.name }}
                                </Label>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Submit Buttons -->
                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Modification...' : 'Modifier le projet' }}
                    </Button>
                    <Button type="button" variant="outline" as-child>
                        <a :href="`/projects/${project.slug}`">Annuler</a>
                    </Button>
                </div>

                <p v-if="form.recentlySuccessful" class="text-sm text-green-600">
                    Projet modifié avec succès.
                </p>
            </form>
        </div>
    </AppLayout>
</template>
