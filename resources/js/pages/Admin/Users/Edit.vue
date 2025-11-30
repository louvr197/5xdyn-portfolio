<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import { ArrowLeft } from 'lucide-vue-next';

interface User {
    id: number;
    name: string;
    slug: string;
    email: string;
    role: 'user' | 'admin';
}

interface Props {
    user: User;
}

const props = defineProps<Props>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
});

const passwordForm = useForm({
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put(`/admin/users/${props.user.id}`);
};

const updatePassword = () => {
    passwordForm.post(`/admin/users/${props.user.id}/password`, {
        onSuccess: () => passwordForm.reset(),
    });
};
</script>

<template>
    <Head :title="`Modifier ${user.name}`" />

    <AppLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <Button as-child variant="ghost" size="icon">
                    <Link href="/admin/users">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <div>
                    <h1 class="text-3xl font-bold">Modifier {{ user.name }}</h1>
                    <p class="text-muted-foreground mt-1">
                        Mettre à jour les informations de l'utilisateur
                    </p>
                </div>
            </div>

            <!-- User Info Form -->
            <Card>
                <CardHeader>
                    <CardTitle>Informations générales</CardTitle>
                    <CardDescription>Modifier le nom, l'email et le rôle</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Name -->
                        <div class="space-y-2">
                            <Label for="name">Nom complet *</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Ex: Jean Dupont"
                                :class="{ 'border-destructive': form.errors.name }"
                            />
                            <p v-if="form.errors.name" class="text-sm text-destructive">
                                {{ form.errors.name }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Le slug sera mis à jour automatiquement : @{{ user.slug }}
                            </p>
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <Label for="email">Email *</Label>
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="jean.dupont@example.com"
                                :class="{ 'border-destructive': form.errors.email }"
                            />
                            <p v-if="form.errors.email" class="text-sm text-destructive">
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Role -->
                        <div class="space-y-2">
                            <Label for="role">Rôle *</Label>
                            <Select v-model="form.role">
                                <SelectTrigger :class="{ 'border-destructive': form.errors.role }">
                                    <SelectValue placeholder="Sélectionner un rôle" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="user">Utilisateur</SelectItem>
                                    <SelectItem value="admin">Administrateur</SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.role" class="text-sm text-destructive">
                                {{ form.errors.role }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Enregistrement...' : 'Enregistrer les modifications' }}
                            </Button>
                            <Button as-child variant="outline">
                                <Link href="/admin/users">Annuler</Link>
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>

            <!-- Password Form -->
            <Card>
                <CardHeader>
                    <CardTitle>Changer le mot de passe</CardTitle>
                    <CardDescription>Définir un nouveau mot de passe pour cet utilisateur</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="updatePassword" class="space-y-6">
                        <!-- Password -->
                        <div class="space-y-2">
                            <Label for="password">Nouveau mot de passe *</Label>
                            <Input
                                id="password"
                                v-model="passwordForm.password"
                                type="password"
                                placeholder="••••••••"
                                :class="{ 'border-destructive': passwordForm.errors.password }"
                            />
                            <p v-if="passwordForm.errors.password" class="text-sm text-destructive">
                                {{ passwordForm.errors.password }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Minimum 8 caractères
                            </p>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="space-y-2">
                            <Label for="password_confirmation">Confirmer le nouveau mot de passe *</Label>
                            <Input
                                id="password_confirmation"
                                v-model="passwordForm.password_confirmation"
                                type="password"
                                placeholder="••••••••"
                                :class="{ 'border-destructive': passwordForm.errors.password_confirmation }"
                            />
                            <p v-if="passwordForm.errors.password_confirmation" class="text-sm text-destructive">
                                {{ passwordForm.errors.password_confirmation }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <Button type="submit" :disabled="passwordForm.processing">
                            {{ passwordForm.processing ? 'Mise à jour...' : 'Mettre à jour le mot de passe' }}
                        </Button>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
