<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface Image {
    id: number;
    image_path: string;
    alt_text: string | null;
    description: string | null;
    display_order: number;
}

interface ChildGallery {
    id: number;
    title: string;
    images: Image[];
}

interface Project {
    id: number;
    title: string;
}

interface ParentGallery {
    id: number;
    title: string;
}

interface Gallery {
    id: number;
    title: string;
    project: Project;
    parent: ParentGallery | null;
    children: ChildGallery[];
    images: Image[];
}

const props = defineProps<{
    gallery: Gallery;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Galeries',
        href: '/galleries',
    },
    {
        title: props.gallery.title,
        href: `/galleries/${props.gallery.id}`,
    },
];

const deleteGallery = () => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette galerie et toutes ses images ?')) {
        router.delete(`/galleries/${props.gallery.id}`);
    }
};

const deleteImage = (imageId: number) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
        router.delete(`/galleries/${props.gallery.id}/images/${imageId}`, {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head :title="gallery.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ gallery.title }}</h1>
                <div class="flex gap-2">
                    <Button as-child variant="outline">
                        <Link :href="`/galleries/${gallery.id}/edit`">Modifier</Link>
                    </Button>
                    <Button variant="destructive" @click="deleteGallery">Supprimer</Button>
                </div>
            </div>

            <!-- Gallery Info -->
            <Card>
                <CardHeader>
                    <CardTitle>Informations</CardTitle>
                </CardHeader>
                <CardContent class="space-y-2">
                    <div>
                        <span class="font-medium">Projet:</span>
                        <Link
                            :href="`/projects/${gallery.project.id}`"
                            class="ml-2 text-primary hover:underline"
                        >
                            {{ gallery.project.title }}
                        </Link>
                    </div>
                    <div v-if="gallery.parent">
                        <span class="font-medium">Galerie parente:</span>
                        <Link
                            :href="`/galleries/${gallery.parent.id}`"
                            class="ml-2 text-primary hover:underline"
                        >
                            {{ gallery.parent.title }}
                        </Link>
                    </div>
                </CardContent>
            </Card>

            <!-- Child Galleries -->
            <Card v-if="gallery.children.length > 0">
                <CardHeader>
                    <CardTitle>Sous-galeries ({{ gallery.children.length }})</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <Link
                            v-for="child in gallery.children"
                            :key="child.id"
                            :href="`/galleries/${child.id}`"
                            class="border rounded-lg p-4 hover:bg-accent transition-colors"
                        >
                            <h3 class="font-medium mb-2">{{ child.title }}</h3>
                            <div class="text-sm text-muted-foreground">
                                {{ child.images.length }} image(s)
                            </div>
                            <div
                                v-if="child.images.length > 0"
                                class="mt-2 grid grid-cols-3 gap-1"
                            >
                                <img
                                    v-for="(image, index) in child.images.slice(0, 3)"
                                    :key="image.id"
                                    :src="`/storage/${image.image_path}`"
                                    :alt="image.alt_text || child.title"
                                    class="w-full aspect-square object-cover rounded"
                                />
                            </div>
                        </Link>
                    </div>
                </CardContent>
            </Card>

            <!-- Images -->
            <Card>
                <CardHeader>
                    <CardTitle>Images ({{ gallery.images.length }})</CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="gallery.images.length === 0" class="text-center py-8">
                        <p class="text-muted-foreground mb-4">Aucune image dans cette galerie</p>
                        <Button as-child>
                            <Link :href="`/galleries/${gallery.id}/edit`">
                                Ajouter des images
                            </Link>
                        </Button>
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
