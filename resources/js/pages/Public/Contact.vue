<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Mail, Phone, MapPin, Send } from 'lucide-vue-next';
import TipTapEditor from '@/components/TipTapEditor.vue';

interface User {
    id: number;
    name: string;
    email: string;
    slug: string;
}

interface Information {
    title: string;
    bio: string | null;
    phone: string | null;
    email: string;
    location: string | null;
    website: string | null;
    linkedin: string | null;
    github: string | null;
}

interface Props {
    user: User;
    information: Information;
}

const props = defineProps<Props>();

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
    honeypot: '', // Champ honeypot pour anti-spam
});

const submitForm = () => {
    form.post(`/portfolio/${props.user.slug}/contact`, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head :title="`Contact - ${user.name}`" />

    <PublicLayout>
        <div class="min-h-screen py-12" :style="{ backgroundColor: `${information.theme_color}26` }">
        <div class="container mx-auto px-4 max-w-6xl">
            <!-- Header Section -->
            <div class="mb-12 text-center">
                <h1 class="text-4xl font-bold mb-4">Me contacter</h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto">
                    Vous avez un projet en tête ou souhaitez simplement échanger ? N'hésitez pas à me contacter.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Contact Information -->
                <div class="lg:col-span-1">
                    <Card>
                        <CardHeader>
                            <CardTitle>Informations</CardTitle>
                            <CardDescription>Retrouvez mes coordonnées</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div v-if="information.email" class="flex items-start gap-3">
                                <Mail class="w-5 h-5 text-primary mt-0.5" />
                                <div>
                                    <p class="font-semibold mb-1">Email</p>
                                    <a
                                        :href="`mailto:${information.email}`"
                                        class="text-muted-foreground hover:text-primary transition-colors"
                                    >
                                        {{ information.email }}
                                    </a>
                                </div>
                            </div>

                            <div v-if="information.phone" class="flex items-start gap-3">
                                <Phone class="w-5 h-5 text-primary mt-0.5" />
                                <div>
                                    <p class="font-semibold mb-1">Téléphone</p>
                                    <a
                                        :href="`tel:${information.phone}`"
                                        class="text-muted-foreground hover:text-primary transition-colors"
                                    >
                                        {{ information.phone }}
                                    </a>
                                </div>
                            </div>

                            <div v-if="information.location" class="flex items-start gap-3">
                                <MapPin class="w-5 h-5 text-primary mt-0.5" />
                                <div>
                                    <p class="font-semibold mb-1">Localisation</p>
                                    <p class="text-muted-foreground">{{ information.location }}</p>
                                </div>
                            </div>

                            <!-- Social Links -->
                            <div v-if="information.linkedin || information.github || information.website" class="pt-4 border-t">
                                <p class="font-semibold mb-3">Réseaux sociaux</p>
                                <div class="flex flex-col gap-2">
                                    <a
                                        v-if="information.linkedin"
                                        :href="information.linkedin"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-sm text-muted-foreground hover:text-primary transition-colors"
                                    >
                                        LinkedIn
                                    </a>
                                    <a
                                        v-if="information.github"
                                        :href="information.github"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-sm text-muted-foreground hover:text-primary transition-colors"
                                    >
                                        GitHub
                                    </a>
                                    <a
                                        v-if="information.website"
                                        :href="information.website"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-sm text-muted-foreground hover:text-primary transition-colors"
                                    >
                                        Site web
                                    </a>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Response Time -->
                    <Card class="mt-6">
                        <CardContent class="pt-6">
                            <p class="text-sm text-muted-foreground">
                                Je m'efforce de répondre à tous les messages dans les 24-48 heures.
                            </p>
                        </CardContent>
                    </Card>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <Card>
                        <CardHeader>
                            <CardTitle>Envoyez-moi un message</CardTitle>
                            <CardDescription>Remplissez le formulaire ci-dessous et je vous répondrai rapidement</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <!-- Success Message -->
                            <Alert v-if="$page.props.flash?.success" class="mb-6 border-green-500 bg-green-50 text-green-800">
                                <AlertDescription>
                                    {{ $page.props.flash.success }}
                                </AlertDescription>
                            </Alert>

                            <!-- Error Message -->
                            <Alert v-if="$page.props.flash?.error" class="mb-6 border-red-500 bg-red-50 text-red-800">
                                <AlertDescription>
                                    {{ $page.props.flash.error }}
                                </AlertDescription>
                            </Alert>

                            <form @submit.prevent="submitForm" class="space-y-6">
                                <!-- Honeypot field (hidden) -->
                                <input
                                    type="text"
                                    v-model="form.honeypot"
                                    name="honeypot"
                                    class="hidden"
                                    tabindex="-1"
                                    autocomplete="off"
                                />

                                <!-- Name -->
                                <div class="space-y-2">
                                    <Label for="name">Nom complet <span class="text-red-500" aria-label="requis">*</span></Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        placeholder="Votre nom"
                                        required
                                        aria-required="true"
                                        :aria-invalid="!!form.errors.name"
                                        :aria-describedby="form.errors.name ? 'name-error' : undefined"
                                        :class="{ 'border-red-500': form.errors.name }"
                                    />
                                    <p v-if="form.errors.name" id="name-error" class="text-sm text-red-500" role="alert">
                                        {{ form.errors.name }}
                                    </p>
                                </div>

                                <!-- Email -->
                                <div class="space-y-2">
                                    <Label for="email">Email <span class="text-red-500" aria-label="requis">*</span></Label>
                                    <Input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        placeholder="votre.email@exemple.com"
                                        required
                                        aria-required="true"
                                        :aria-invalid="!!form.errors.email"
                                        :aria-describedby="form.errors.email ? 'email-error' : undefined"
                                        :class="{ 'border-red-500': form.errors.email }"
                                    />
                                    <p v-if="form.errors.email" id="email-error" class="text-sm text-red-500" role="alert">
                                        {{ form.errors.email }}
                                    </p>
                                </div>

                                <!-- Subject -->
                                <div class="space-y-2">
                                    <Label for="subject">Sujet <span class="text-red-500" aria-label="requis">*</span></Label>
                                    <Input
                                        id="subject"
                                        v-model="form.subject"
                                        type="text"
                                        placeholder="L'objet de votre message"
                                        required
                                        aria-required="true"
                                        :aria-invalid="!!form.errors.subject"
                                        :aria-describedby="form.errors.subject ? 'subject-error' : undefined"
                                        :class="{ 'border-red-500': form.errors.subject }"
                                    />
                                    <p v-if="form.errors.subject" id="subject-error" class="text-sm text-red-500" role="alert">
                                        {{ form.errors.subject }}
                                    </p>
                                </div>

                                <!-- Message -->
                                <div class="space-y-2">
                                    <Label for="message">Message <span class="text-red-500" aria-label="requis">*</span></Label>
                                    <div :class="{ 'border border-red-500 rounded-md': form.errors.message }">
                                        <TipTapEditor
                                            v-model="form.message"
                                            placeholder="Écrivez votre message ici..."
                                            :aria-invalid="!!form.errors.message"
                                            :aria-describedby="form.errors.message ? 'message-error' : undefined"
                                        />
                                    </div>
                                    <p v-if="form.errors.message" id="message-error" class="text-sm text-red-500" role="alert">
                                        {{ form.errors.message }}
                                    </p>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex items-center gap-4">
                                    <Button type="submit" :disabled="form.processing" size="lg">
                                        <Send class="w-4 h-4 mr-2" />
                                        {{ form.processing ? 'Envoi en cours...' : 'Envoyer le message' }}
                                    </Button>
                                    <p v-if="form.processing" class="text-sm text-muted-foreground">
                                        Veuillez patienter...
                                    </p>
                                </div>
                            </form>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
        </div>
    </PublicLayout>
</template>
