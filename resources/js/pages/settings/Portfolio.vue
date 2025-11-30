<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Palette } from 'lucide-vue-next';

interface Information {
    id: number;
    theme_color: string;
}

interface Props {
    information: Information;
}

const props = defineProps<Props>();

const form = useForm({
    theme_color: props.information?.theme_color || '#0ea5e9',
});

const submit = () => {
    form.patch('/settings/portfolio');
};

// Couleurs prédéfinies
const presetColors = [
    { name: 'Sky Blue', value: '#0ea5e9' },
    { name: 'Purple', value: '#8b5cf6' },
    { name: 'Pink', value: '#ec4899' },
    { name: 'Rose', value: '#f43f5e' },
    { name: 'Orange', value: '#f97316' },
    { name: 'Amber', value: '#f59e0b' },
    { name: 'Emerald', value: '#10b981' },
    { name: 'Teal', value: '#14b8a6' },
    { name: 'Cyan', value: '#06b6d4' },
    { name: 'Indigo', value: '#6366f1' },
    { name: 'Violet', value: '#a855f7' },
    { name: 'Fuchsia', value: '#d946ef' },
];
</script>

<template>
    <Head title="Personnalisation du Portfolio" />

    <AppLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold">Personnalisation du Portfolio</h1>
                <p class="text-muted-foreground mt-1">
                    Choisissez la couleur principale de votre portfolio public
                </p>
            </div>

            <!-- Form -->
            <Card>
                <CardHeader>
                    <CardTitle>Couleur du thème</CardTitle>
                    <CardDescription>Cette couleur sera utilisée pour les accents et les éléments interactifs de votre portfolio</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Color Input -->
                        <div class="space-y-2">
                            <Label for="theme_color">Couleur principale</Label>
                            <div class="flex items-center gap-4">
                                <Input
                                    id="theme_color"
                                    v-model="form.theme_color"
                                    type="color"
                                    class="w-24 h-12 cursor-pointer"
                                    :class="{ 'border-destructive': form.errors.theme_color }"
                                />
                                <Input
                                    v-model="form.theme_color"
                                    type="text"
                                    placeholder="#0ea5e9"
                                    class="flex-1 font-mono"
                                    :class="{ 'border-destructive': form.errors.theme_color }"
                                />
                            </div>
                            <p v-if="form.errors.theme_color" class="text-sm text-destructive">
                                {{ form.errors.theme_color }}
                            </p>
                        </div>

                        <!-- Preset Colors -->
                        <div class="space-y-3">
                            <Label>Couleurs prédéfinies</Label>
                            <div class="grid grid-cols-6 gap-3">
                                <button
                                    v-for="color in presetColors"
                                    :key="color.value"
                                    type="button"
                                    @click="form.theme_color = color.value"
                                    :style="{ backgroundColor: color.value }"
                                    :class="[
                                        'h-12 rounded-md transition-all duration-200',
                                        form.theme_color === color.value
                                            ? 'ring-2 ring-offset-2 ring-offset-background scale-110'
                                            : 'hover:scale-105',
                                    ]"
                                    :title="color.name"
                                />
                            </div>
                        </div>

                        <!-- Preview -->
                        <div class="space-y-3">
                            <Label>Aperçu</Label>
                            <div class="p-6 border rounded-lg space-y-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-16 w-16 rounded-full flex items-center justify-center text-white"
                                        :style="{ backgroundColor: form.theme_color }"
                                    >
                                        <Palette class="h-8 w-8" />
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-lg">Exemple de titre</h3>
                                        <p class="text-muted-foreground">Texte descriptif avec la couleur d'accent</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <Button
                                        type="button"
                                        class="pointer-events-none"
                                        :style="{
                                            backgroundColor: form.theme_color,
                                            borderColor: form.theme_color,
                                        }"
                                    >
                                        Bouton principal
                                    </Button>
                                    <Button
                                        type="button"
                                        variant="outline"
                                        class="pointer-events-none"
                                        :style="{
                                            borderColor: form.theme_color,
                                            color: form.theme_color,
                                        }"
                                    >
                                        Bouton secondaire
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
                            </Button>
                            <Button
                                type="button"
                                variant="outline"
                                @click="form.theme_color = '#0ea5e9'"
                            >
                                Réinitialiser
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
