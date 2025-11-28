<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Tableau de bord</h1>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Card>
                    <CardHeader>
                        <CardTitle>Projets</CardTitle>
                        <CardDescription>Gérer vos projets de portfolio</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button as-child class="w-full">
                            <Link href="/projects">
                                Voir les projets
                            </Link>
                        </Button>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Utilisateurs</CardTitle>
                        <CardDescription>Gérer les utilisateurs de la plateforme</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button as-child class="w-full">
                            <Link href="/users">
                                Voir les utilisateurs
                            </Link>
                        </Button>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Paramètres</CardTitle>
                        <CardDescription>Gérer votre profil et vos préférences</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button as-child class="w-full" variant="outline">
                            <Link href="/settings/profile">
                                Paramètres
                            </Link>
                        </Button>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
