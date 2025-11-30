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

const form = useForm({
    name: props.technique.name,
    description: props.technique.description || '',
    proficiency_level: props.technique.proficiency_level,
    display_order: props.technique.display_order || 0,
});

const submit = () => {
    form.put(`/techniques/${props.technique.id}`, {
        preserveScroll: true,
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Techniques',
        href: '/techniques',
    },
    {
        title: props.technique.name,
        href: `/techniques/${props.technique.id}`,
    },
    {
        title: 'Modifier',
        href: `/techniques/${props.technique.id}/edit`,
    },
];
</script>

<template>
    <Head :title="`Modifier ${technique.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Modifier {{ technique.name }}</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <Card>
                    <CardHeader>
                        <CardTitle>Informations de la technique</CardTitle>
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

                        <!-- Description -->
                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                :class="{ 'border-red-500': form.errors.description }"
                            />
                            <p v-if="form.errors.description" class="text-sm text-red-500">
                                {{ form.errors.description }}
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
                        <a :href="`/techniques/${technique.id}`">Annuler</a>
                    </Button>
                </div>

                <p v-if="form.recentlySuccessful" class="text-sm text-green-600">
                    Technique modifiée avec succès.
                </p>
            </form>
        </div>
    </AppLayout>
</template>
