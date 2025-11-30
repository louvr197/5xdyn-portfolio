<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
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
import { ref, computed, watch } from 'vue';

interface Image {
    id: number;
    image_path: string;
    alt_text: string | null;
    description: string | null;
    display_order: number;
}

interface Project {
    id: number;
    title: string;
}

interface ParentGallery {
    id: number;
    title: string;
}

interface ChildGallery {
    id: number;
    title: string;
}

interface Gallery {
    id: number;
    title: string;
    project_id: number;
    project: Project;
    parent: ParentGallery | null;
    children: ChildGallery[];
    images: Image[];
}

const props = defineProps<{
    gallery: Gallery;
    projects: Project[];
    galleries: Gallery[];
}>();

const form = useForm({
    title: props.gallery.title,
    project_id: props.gallery.project.id.toString(),
    parent_id: props.gallery.parent?.id.toString() || '',
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

const imageForm = useForm({
    images: [] as File[],
});

const selectedFiles = ref<File[]>([]);

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files) {
        selectedFiles.value = Array.from(target.files);
        imageForm.images = selectedFiles.value;
    }
};

const submit = () => {
    form.put(`/galleries/${props.gallery.id}`, {
        preserveScroll: true,
    });
};

const uploadImages = () => {
    imageForm.post(`/galleries/${props.gallery.id}/images`, {
        preserveScroll: true,
        onSuccess: () => {
            selectedFiles.value = [];
            imageForm.images = [];
            // Reset file input
            const fileInput = document.getElementById('images') as HTMLInputElement;
            if (fileInput) fileInput.value = '';
        },
    });
};

const deleteImage = (imageId: number) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
        router.delete(`/galleries/${props.gallery.id}/images/${imageId}`, {
            preserveScroll: true,
        });
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Galeries',
        href: '/galleries',
    },
    {
        title: props.gallery.title,
        href: `/galleries/${props.gallery.id}`,
    },
    {
        title: 'Modifier',
        href: `/galleries/${props.gallery.id}/edit`,
    },
];
</script>

<template>
    <Head :title="`Modifier ${gallery.title}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Modifier {{ gallery.title }}</h1>

            <!-- Gallery Info Form -->
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

                        <!-- Parent Gallery -->
                        <div class="space-y-2">
                            <Label for="parent_id">Galerie parente (optionnel)</Label>
                            <Select v-model="form.parent_id" :disabled="!form.project_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Aucune (galerie principale)" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="">Aucune (galerie principale)</SelectItem>
                                    <SelectItem
                                        v-for="galleryOption in availableParentGalleries"
                                        :key="galleryOption.id"
                                        :value="galleryOption.id.toString()"
                                    >
                                        {{ galleryOption.title }}
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
                        {{ form.processing ? 'Modification...' : 'Modifier' }}
                    </Button>
                    <Button type="button" variant="outline" as-child>
                        <a :href="`/galleries/${gallery.id}`">Annuler</a>
                    </Button>
                </div>

                <p v-if="form.recentlySuccessful" class="text-sm text-green-600">
                    Galerie modifiée avec succès.
                </p>
            </form>

            <!-- Image Management -->
            <Card>
                <CardHeader>
                    <CardTitle>Ajouter des images</CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="space-y-2">
                        <Label for="images">Sélectionner des images (max 5MB chacune)</Label>
                        <Input
                            id="images"
                            type="file"
                            accept="image/*"
                            multiple
                            @change="handleFileChange"
                            :class="{ 'border-red-500': imageForm.errors.images }"
                        />
                        <p v-if="imageForm.errors.images" class="text-sm text-red-500">
                            {{ imageForm.errors.images }}
                        </p>
                        <p v-if="selectedFiles.length > 0" class="text-sm text-muted-foreground">
                            {{ selectedFiles.length }} image(s) sélectionnée(s)
                        </p>
                    </div>

                    <Button
                        type="button"
                        @click="uploadImages"
                        :disabled="selectedFiles.length === 0 || imageForm.processing"
                    >
                        {{ imageForm.processing ? 'Téléchargement...' : 'Ajouter les images' }}
                    </Button>
                </CardContent>
            </Card>

            <!-- Current Images -->
            <Card>
                <CardHeader>
                    <CardTitle>Images actuelles ({{ gallery.images.length }})</CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="gallery.images.length === 0" class="text-center py-8">
                        <p class="text-muted-foreground">Aucune image dans cette galerie</p>
                    </div>
                    <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <div
                            v-for="image in gallery.images"
                            :key="image.id"
                            class="relative group"
                        >
                            <img
                                :src="`/storage/${image.image_path}`"
                                :alt="image.alt_text || gallery.title"
                                class="w-full aspect-square object-cover rounded-lg"
                            />
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center"
                            >
                                <Button
                                    variant="destructive"
                                    size="sm"
                                    @click="deleteImage(image.id)"
                                >
                                    Supprimer
                                </Button>
                            </div>
                            <div v-if="image.alt_text || image.description" class="mt-2">
                                <p v-if="image.alt_text" class="text-sm font-medium">
                                    {{ image.alt_text }}
                                </p>
                                <p v-if="image.description" class="text-xs text-muted-foreground">
                                    {{ image.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
