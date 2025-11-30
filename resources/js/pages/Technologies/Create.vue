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

const form = useForm({
    name: '',
    category: 'backend',
    proficiency_level: 'intermediate',
    logo_path: null as File | null,
    color_code: '#000000',
    display_order: 0,
});

const submit = () => {
    form.post('/technologies');
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Technologies',
        href: '/technologies',
    },
    {
        title: 'Nouvelle technologie',
        href: '/technologies/create',
    },
];
</script>

<template>
    <Head title="Nouvelle technologie" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Nouvelle technologie</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <Card>
                    <CardHeader>
                        <CardTitle>Informations de la technologie</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <!-- Name -->
                        <div class="space-y-2">
                            <Label for="name">Nom *</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                :class="{ 'border-red-500': form.errors.name }"
                            />
                            <p v-if="form.errors.name" class="text-sm text-red-500">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <!-- Category -->
                        <div class="space-y-2">
                            <Label for="category">Catégorie *</Label>
                            <Select v-model="form.category">
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner une catégorie" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="backend">Backend</SelectItem>
                                    <SelectItem value="frontend">Frontend</SelectItem>
                                    <SelectItem value="database">Base de données</SelectItem>
                                    <SelectItem value="devops">DevOps</SelectItem>
                                    <SelectItem value="design">Design</SelectItem>
                                    <SelectItem value="other">Autre</SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.category" class="text-sm text-red-500">
                                {{ form.errors.category }}
                            </p>
                        </div>

                        <!-- Proficiency Level -->
                        <div class="space-y-2">
                            <Label for="proficiency_level">Niveau de compétence *</Label>
                            <Select v-model="form.proficiency_level">
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner un niveau" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="beginner">Débutant</SelectItem>
                                    <SelectItem value="intermediate">Intermédiaire</SelectItem>
                                    <SelectItem value="advanced">Avancé</SelectItem>
                                    <SelectItem value="expert">Expert</SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.proficiency_level" class="text-sm text-red-500">
                                {{ form.errors.proficiency_level }}
                            </p>
                        </div>

                        <!-- Logo Upload -->
                        <div class="space-y-2">
                            <Label for="logo_path">Logo (max 2MB)</Label>
                            <Input
                                id="logo_path"
                                type="file"
                                accept="image/*"
                                @change="
                                    (e) => {
                                        const target = e.target as HTMLInputElement;
                                        form.logo_path = target.files?.[0] || null;
                                    }
                                "
                                :class="{ 'border-red-500': form.errors.logo_path }"
                            />
                            <p v-if="form.errors.logo_path" class="text-sm text-red-500">
                                {{ form.errors.logo_path }}
                            </p>
                        </div>

                        <!-- Color Code -->
                        <div class="space-y-2">
                            <Label for="color_code">Code couleur</Label>
                            <div class="flex gap-2">
                                <Input
                                    id="color_code"
                                    v-model="form.color_code"
                                    type="color"
                                    class="w-20 h-10 cursor-pointer"
                                />
                                <Input
                                    v-model="form.color_code"
                                    type="text"
                                    placeholder="#000000"
                                    :class="{ 'border-red-500': form.errors.color_code }"
                                />
                            </div>
                            <p v-if="form.errors.color_code" class="text-sm text-red-500">
                                {{ form.errors.color_code }}
                            </p>
                        </div>

                        <!-- Display Order -->
                        <div class="space-y-2">
                            <Label for="display_order">Ordre d'affichage</Label>
                            <Input
                                id="display_order"
                                v-model.number="form.display_order"
                                type="number"
                                :class="{ 'border-red-500': form.errors.display_order }"
                            />
                            <p v-if="form.errors.display_order" class="text-sm text-red-500">
                                {{ form.errors.display_order }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Submit Buttons -->
                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Création...' : 'Créer la technologie' }}
                    </Button>
                    <Button type="button" variant="outline" as-child>
                        <a href="/technologies">Annuler</a>
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
