<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { computed, watch } from 'vue';

interface Project {
    id: number;
    title: string;
}

interface Gallery {
    id: number;
    title: string;
    project_id: number;
}

const props = defineProps<{
    projects: Project[];
    galleries: Gallery[];
}>();

const form = useForm({
    title: '',
    project_id: '',
    parent_id: '',
});

// Filter galleries by selected project
const availableParentGalleries = computed(() => {
    if (!form.project_id) return [];
    return props.galleries.filter(g => g.project_id === parseInt(form.project_id));
});

// Reset parent_id when project changes
watch(() => form.project_id, () => {
    form.parent_id = '';
});

const submit = () => {
    form.post('/galleries');
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Galeries',
        href: '/galleries',
    },
    {
        title: 'Nouvelle galerie',
        href: '/galleries/create',
    },
];
</script>

<template>
    <Head title="Nouvelle galerie" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Nouvelle galerie</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <Card>
                    <CardHeader>
                        <CardTitle>Informations de la galerie</CardTitle>
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

                        <!-- Project -->
                        <div class="space-y-2">
                            <Label for="project_id">Projet *</Label>
                            <Select v-model="form.project_id" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner un projet" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="project in projects"
                                        :key="project.id"
                                        :value="project.id.toString()"
                                    >
                                        {{ project.title }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.project_id" class="text-sm text-red-500">
                                {{ form.errors.project_id }}
                            </p>
                        </div>

                        <!-- Parent Gallery (optional) -->
                        <div class="space-y-2">
                            <Label for="parent_id">Galerie parente (optionnel)</Label>
                            <Select v-model="form.parent_id" :disabled="!form.project_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Aucune (galerie principale)" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="">Aucune (galerie principale)</SelectItem>
                                    <SelectItem
                                        v-for="gallery in availableParentGalleries"
                                        :key="gallery.id"
                                        :value="gallery.id.toString()"
                                    >
                                        {{ gallery.title }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.parent_id" class="text-sm text-red-500">
                                {{ form.errors.parent_id }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ form.project_id
                                    ? 'Sélectionnez une galerie parente pour créer une sous-galerie'
                                    : 'Sélectionnez d\'abord un projet' }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Submit Buttons -->
                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Création...' : 'Créer la galerie' }}
                    </Button>
                    <Button type="button" variant="outline" as-child>
                        <a href="/galleries">Annuler</a>
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
