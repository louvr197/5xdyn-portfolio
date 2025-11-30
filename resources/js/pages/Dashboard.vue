<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

// Génère l'URL du portfolio public
const portfolioUrl = computed(() => {
    return `/portfolio/${user.value.slug}`;
});

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
                <!-- Public Portfolio Card -->
                <Card class="border-primary/50">
                    <CardHeader>
                        <CardTitle>Mon Portfolio Public</CardTitle>
                        <CardDescription>Voir votre portfolio tel que les visiteurs le voient</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button as-child class="w-full" variant="default">
                            <Link :href="portfolioUrl">
                                Voir mon portfolio
                            </Link>
                        </Button>
                    </CardContent>
                </Card>

                <!-- Projects Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Projets</CardTitle>
                        <CardDescription>Gérer vos projets de portfolio</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button as-child class="w-full">
                            <Link href="/projects">Voir les projets</Link>
                        </Button>
                    </CardContent>
                </Card>

                <!-- Experiences Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Expériences</CardTitle>
                        <CardDescription>Gérer vos expériences professionnelles</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button as-child class="w-full">
                            <Link href="/experiences">Voir les expériences</Link>
                        </Button>
                    </CardContent>
                </Card>

                <!-- Formations Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Formations</CardTitle>
                        <CardDescription>Gérer vos formations et diplômes</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button as-child class="w-full">
                            <Link href="/formations">Voir les formations</Link>
                        </Button>
                    </CardContent>
                </Card>

                <!-- Technologies Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Technologies</CardTitle>
                        <CardDescription>Gérer vos compétences techniques</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button as-child class="w-full">
                            <Link href="/technologies">Voir les technologies</Link>
                        </Button>
                    </CardContent>
                </Card>

                <!-- Techniques Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Techniques</CardTitle>
                        <CardDescription>Gérer vos techniques et méthodes</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button as-child class="w-full">
                            <Link href="/techniques">Voir les techniques</Link>
                        </Button>
                    </CardContent>
                </Card>

                <!-- Galleries Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Galeries</CardTitle>
                        <CardDescription>Gérer les galeries d'images</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button as-child class="w-full">
                            <Link href="/galleries">Voir les galeries</Link>
                        </Button>
                    </CardContent>
                </Card>

                <!-- Mon profil Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Mon profil</CardTitle>
                        <CardDescription>Modifier vos informations personnelles</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button as-child class="w-full">
                            <Link :href="`/users/${$page.props.auth.user.id}/edit`">
                                Modifier mon profil
                            </Link>
                        </Button>
                    </CardContent>
                </Card>

                <!-- All Portfolios Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Tous les Portfolios</CardTitle>
                        <CardDescription>Découvrir les portfolios de la communauté</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button as-child class="w-full" variant="outline">
                            <Link href="/portfolios">
                                Explorer les portfolios
                            </Link>
                        </Button>
                    </CardContent>
                </Card>

                <!-- Settings Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Paramètres</CardTitle>
                        <CardDescription>Gérer vos préférences et sécurité</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button as-child class="w-full" variant="outline">
                            <Link href="/settings/profile">Paramètres</Link>
                        </Button>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
