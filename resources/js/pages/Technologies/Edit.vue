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

const form = useForm({
    name: props.technology.name,
    category: props.technology.category,
    proficiency_level: props.technology.proficiency_level,
    logo_path: null as File | null,
    color_code: props.technology.color_code || '',
    display_order: props.technology.display_order || 0,
});

const submit = () => {
    form.put(`/technologies/${props.technology.id}`, {
        preserveScroll: true,
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Technologies',
        href: '/technologies',
    },
    {
        title: props.technology.name,
        href: `/technologies/${props.technology.id}`,
    },
    {
        title: 'Modifier',
        href: `/technologies/${props.technology.id}/edit`,
    },
];
</script>

<template>
    <Head :title="`Modifier ${technology.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Modifier {{ technology.name }}</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <Card>
                    <CardHeader>
                        <CardTitle>Informations de la technologie</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <!-- Current Logo -->
                        <div v-if="technology.logo_path" class="space-y-2">
                            <Label>Logo actuel</Label>
                            <img
                                :src="`/storage/${technology.logo_path}`"
                                :alt="technology.name"
                                class="h-16 w-16 object-contain"
                            />
                        </div>

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
                            <p class="text-sm text-muted-foreground">
                                Laissez vide pour conserver le logo actuel
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
                        {{ form.processing ? 'Modification...' : 'Modifier' }}
                    </Button>
                    <Button type="button" variant="outline" as-child>
                        <a :href="`/technologies/${technology.id}`">Annuler</a>
                    </Button>
                </div>

                <p v-if="form.recentlySuccessful" class="text-sm text-green-600">
                    Technologie modifiée avec succès.
                </p>
            </form>
        </div>
    </AppLayout>
</template>
