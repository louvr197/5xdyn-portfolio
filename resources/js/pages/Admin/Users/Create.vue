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
import { ArrowLeft } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user',
});

const submit = () => {
    form.post('/admin/users', {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Créer un utilisateur" />

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
                    <h1 class="text-3xl font-bold">Créer un utilisateur</h1>
                    <p class="text-muted-foreground mt-1">
                        Ajouter un nouvel utilisateur à la plateforme
                    </p>
                </div>
            </div>

            <!-- Form -->
            <Card>
                <CardHeader>
                    <CardTitle>Informations de l'utilisateur</CardTitle>
                    <CardDescription>Remplissez tous les champs obligatoires</CardDescription>
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
                                Le slug sera généré automatiquement à partir du nom
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

                        <!-- Password -->
                        <div class="space-y-2">
                            <Label for="password">Mot de passe *</Label>
                            <Input
                                id="password"
                                v-model="form.password"
                                type="password"
                                placeholder="••••••••"
                                :class="{ 'border-destructive': form.errors.password }"
                            />
                            <p v-if="form.errors.password" class="text-sm text-destructive">
                                {{ form.errors.password }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Minimum 8 caractères
                            </p>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="space-y-2">
                            <Label for="password_confirmation">Confirmer le mot de passe *</Label>
                            <Input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                placeholder="••••••••"
                                :class="{ 'border-destructive': form.errors.password_confirmation }"
                            />
                            <p v-if="form.errors.password_confirmation" class="text-sm text-destructive">
                                {{ form.errors.password_confirmation }}
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
                            <p class="text-sm text-muted-foreground">
                                Les administrateurs peuvent créer et gérer les utilisateurs
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Création...' : 'Créer l\'utilisateur' }}
                            </Button>
                            <Button as-child variant="outline">
                                <Link href="/admin/users">Annuler</Link>
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
