<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ref } from 'vue';

interface Information {
    id?: number;
    last_name?: string;
    first_name?: string;
    professional_title?: string;
    email?: string;
    phone?: string;
    location?: string;
    availability?: string;
    bio?: string;
    picture_path?: string;
    cv_path?: string;
    social_links?: Record<string, string>;
    theme_color?: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    information?: Information;
}

const props = defineProps<{
    user: User;
}>();

const socialLinksArray = ref(
    props.user.information?.social_links
        ? Object.entries(props.user.information.social_links).map(([platform, url]) => ({
              platform,
              url,
          }))
        : [],
);

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    information: {
        last_name: props.user.information?.last_name || '',
        first_name: props.user.information?.first_name || '',
        professional_title: props.user.information?.professional_title || '',
        info_email: props.user.information?.email || props.user.email,
        phone: props.user.information?.phone || '',
        location: props.user.information?.location || '',
        availability: props.user.information?.availability || '',
        bio: props.user.information?.bio || '',
        picture_path: null as File | null,
        cv_path: null as File | null,
        social_links: props.user.information?.social_links || {},
        theme_color: props.user.information?.theme_color || '#0ea5e9',
    },
});

const addSocialLink = () => {
    socialLinksArray.value.push({ platform: '', url: '' });
};

const removeSocialLink = (index: number) => {
    socialLinksArray.value.splice(index, 1);
};

const submit = () => {
    // Convert social links array to object
    const socialLinksObject: Record<string, string> = {};
    socialLinksArray.value.forEach((link) => {
        if (link.platform && link.url) {
            socialLinksObject[link.platform] = link.url;
        }
    });
    form.information.social_links = socialLinksObject;

    form.put(`/users/${props.user.id}`, {
        preserveScroll: true,
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Utilisateurs',
        href: '/users',
    },
    {
        title: props.user.name,
        href: `/users/${props.user.id}`,
    },
    {
        title: 'Modifier',
        href: `/users/${props.user.id}/edit`,
    },
];
</script>

<template>
    <Head :title="`Modifier ${user.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Modifier l'utilisateur</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Card 1: Account -->
                <Card>
                    <CardHeader>
                        <CardTitle>Compte utilisateur</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <!-- Name -->
                        <div class="space-y-2">
                            <Label for="name">Nom d'utilisateur *</Label>
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

                        <!-- Email -->
                        <div class="space-y-2">
                            <Label for="email">Email de connexion *</Label>
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                :class="{ 'border-red-500': form.errors.email }"
                            />
                            <p v-if="form.errors.email" class="text-sm text-red-500">
                                {{ form.errors.email }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Card 2: Identity -->
                <Card>
                    <CardHeader>
                        <CardTitle>Identité</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <!-- Picture -->
                        <div class="space-y-2">
                            <div v-if="user.information?.picture_path" class="mb-2">
                                <Label class="mb-2 block">Photo actuelle</Label>
                                <img
                                    :src="`/storage/${user.information.picture_path}`"
                                    :alt="user.name"
                                    class="h-32 w-32 object-cover rounded-full"
                                />
                            </div>
                            <Label for="picture_path">Photo de profil (max 5MB)</Label>
                            <Input
                                id="picture_path"
                                type="file"
                                accept="image/*"
                                @change="
                                    (e) => {
                                        const target = e.target as HTMLInputElement;
                                        form.information.picture_path = target.files?.[0] || null;
                                    }
                                "
                                :class="{ 'border-red-500': form.errors['information.picture_path'] }"
                            />
                            <p
                                v-if="form.errors['information.picture_path']"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors['information.picture_path'] }}
                            </p>
                        </div>

                        <!-- First Name & Last Name -->
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="first_name">Prénom</Label>
                                <Input
                                    id="first_name"
                                    v-model="form.information.first_name"
                                    type="text"
                                    :class="{
                                        'border-red-500': form.errors['information.first_name'],
                                    }"
                                />
                                <p
                                    v-if="form.errors['information.first_name']"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors['information.first_name'] }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="last_name">Nom</Label>
                                <Input
                                    id="last_name"
                                    v-model="form.information.last_name"
                                    type="text"
                                    :class="{
                                        'border-red-500': form.errors['information.last_name'],
                                    }"
                                />
                                <p
                                    v-if="form.errors['information.last_name']"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors['information.last_name'] }}
                                </p>
                            </div>
                        </div>

                        <!-- Professional Title -->
                        <div class="space-y-2">
                            <Label for="professional_title">Titre professionnel</Label>
                            <Input
                                id="professional_title"
                                v-model="form.information.professional_title"
                                type="text"
                                placeholder="Ex: Développeur Full Stack"
                                :class="{
                                    'border-red-500': form.errors['information.professional_title'],
                                }"
                            />
                            <p
                                v-if="form.errors['information.professional_title']"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors['information.professional_title'] }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Card 3: Contact -->
                <Card>
                    <CardHeader>
                        <CardTitle>Contact</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <!-- Email -->
                        <div class="space-y-2">
                            <Label for="info_email">Email public</Label>
                            <Input
                                id="info_email"
                                v-model="form.information.info_email"
                                type="email"
                                placeholder="Email affiché publiquement"
                                :class="{ 'border-red-500': form.errors['information.info_email'] }"
                            />
                            <p
                                v-if="form.errors['information.info_email']"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors['information.info_email'] }}
                            </p>
                        </div>

                        <!-- Phone & Location -->
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="phone">Téléphone</Label>
                                <Input
                                    id="phone"
                                    v-model="form.information.phone"
                                    type="tel"
                                    placeholder="Ex: +33 6 12 34 56 78"
                                    :class="{ 'border-red-500': form.errors['information.phone'] }"
                                />
                                <p
                                    v-if="form.errors['information.phone']"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors['information.phone'] }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="location">Localisation</Label>
                                <Input
                                    id="location"
                                    v-model="form.information.location"
                                    type="text"
                                    placeholder="Ex: Paris, France"
                                    :class="{
                                        'border-red-500': form.errors['information.location'],
                                    }"
                                />
                                <p
                                    v-if="form.errors['information.location']"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors['information.location'] }}
                                </p>
                            </div>
                        </div>

                        <!-- Availability -->
                        <div class="space-y-2">
                            <Label for="availability">Disponibilité</Label>
                            <Input
                                id="availability"
                                v-model="form.information.availability"
                                type="text"
                                placeholder="Ex: Immédiate, À partir de janvier 2024"
                                :class="{
                                    'border-red-500': form.errors['information.availability'],
                                }"
                            />
                            <p
                                v-if="form.errors['information.availability']"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors['information.availability'] }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Card 4: Bio -->
                <Card>
                    <CardHeader>
                        <CardTitle>Biographie</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <Label for="bio">À propos</Label>
                            <Textarea
                                id="bio"
                                v-model="form.information.bio"
                                rows="8"
                                placeholder="Présentez-vous, votre parcours, vos compétences..."
                                :class="{ 'border-red-500': form.errors['information.bio'] }"
                            />
                            <p v-if="form.errors['information.bio']" class="text-sm text-red-500">
                                {{ form.errors['information.bio'] }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Card 5: CV -->
                <Card>
                    <CardHeader>
                        <CardTitle>CV</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div v-if="user.information?.cv_path" class="mb-2">
                                <Label class="mb-2 block">CV actuel</Label>
                                <a
                                    :href="`/storage/${user.information.cv_path}`"
                                    target="_blank"
                                    class="text-blue-600 hover:underline"
                                >
                                    Télécharger le CV
                                </a>
                            </div>
                            <Label for="cv_path">Téléverser un CV (PDF, max 10MB)</Label>
                            <Input
                                id="cv_path"
                                type="file"
                                accept=".pdf"
                                @change="
                                    (e) => {
                                        const target = e.target as HTMLInputElement;
                                        form.information.cv_path = target.files?.[0] || null;
                                    }
                                "
                                :class="{ 'border-red-500': form.errors['information.cv_path'] }"
                            />
                            <p
                                v-if="form.errors['information.cv_path']"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors['information.cv_path'] }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Card 6: Social Links -->
                <Card>
                    <CardHeader>
                        <CardTitle>Réseaux sociaux</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div
                            v-for="(link, index) in socialLinksArray"
                            :key="index"
                            class="flex gap-2"
                        >
                            <Input
                                v-model="link.platform"
                                placeholder="Plateforme (ex: LinkedIn)"
                                class="flex-1"
                            />
                            <Input v-model="link.url" placeholder="URL complète" class="flex-1" />
                            <Button
                                type="button"
                                variant="destructive"
                                size="sm"
                                @click="removeSocialLink(index)"
                            >
                                Supprimer
                            </Button>
                        </div>
                        <Button type="button" variant="outline" @click="addSocialLink">
                            Ajouter un réseau social
                        </Button>
                    </CardContent>
                </Card>

                <!-- Card 7: Theme Color -->
                <Card>
                    <CardHeader>
                        <CardTitle>Couleur du portfolio</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="theme_color">Couleur principale</Label>
                            <div class="flex items-center gap-4">
                                <Input
                                    id="theme_color"
                                    v-model="form.information.theme_color"
                                    type="color"
                                    class="w-24 h-12 cursor-pointer"
                                    :class="{ 'border-red-500': form.errors['information.theme_color'] }"
                                />
                                <Input
                                    v-model="form.information.theme_color"
                                    type="text"
                                    placeholder="#0ea5e9"
                                    class="flex-1 font-mono"
                                    :class="{ 'border-red-500': form.errors['information.theme_color'] }"
                                />
                            </div>
                            <p
                                v-if="form.errors['information.theme_color']"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors['information.theme_color'] }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Cette couleur sera utilisée pour les accents de votre portfolio public
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Submit Buttons -->
                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
                    </Button>
                    <Button type="button" variant="outline" as-child>
                        <a :href="`/users/${user.id}`">Annuler</a>
                    </Button>
                </div>

                <p v-if="form.recentlySuccessful" class="text-sm text-green-600">
                    Utilisateur mis à jour avec succès.
                </p>
            </form>
        </div>
    </AppLayout>
</template>
