<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { User, Briefcase } from 'lucide-vue-next';

interface UserPortfolio {
    id: number;
    name: string;
    slug: string;
    first_name: string;
    last_name: string;
    professional_title: string;
    picture_path: string | null;
    projects_count: number;
}

interface Props {
    users: UserPortfolio[];
}

const props = defineProps<Props>();
</script>

<template>
    <Head title="Tous les portfolios" />

    <div class="min-h-screen bg-gradient-to-br from-background via-background to-primary/5">
        <!-- Header -->
        <header class="border-b bg-white/50 backdrop-blur-sm">
            <div class="container mx-auto px-4 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-primary to-primary/60 bg-clip-text text-transparent">Portfolios</h1>
                        <p class="text-muted-foreground">Découvrez tous les portfolios publics</p>
                    </div>
                    <Button as-child variant="outline" class="border-primary/50 hover:bg-primary hover:text-primary-foreground">
                        <Link href="/login">
                            Connexion
                        </Link>
                    </Button>
                </div>
            </div>
        </header>

        <!-- Content -->
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div v-if="users.length > 0" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <Card v-for="user in users" :key="user.id" class="hover:shadow-xl hover:border-primary/50 transition-all duration-300 hover:-translate-y-1 bg-white/80 backdrop-blur-sm">
                        <CardHeader>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div v-if="user.picture_path" class="h-16 w-16 rounded-full overflow-hidden">
                                        <img
                                            :src="`/storage/${user.picture_path}`"
                                            :alt="`${user.first_name} ${user.last_name}`"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                    <div v-else class="h-16 w-16 rounded-full bg-muted flex items-center justify-center">
                                        <User class="h-8 w-8 text-muted-foreground" />
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <CardTitle class="text-lg truncate">
                                        {{ user.first_name }} {{ user.last_name }}
                                    </CardTitle>
                                    <CardDescription class="truncate">
                                        {{ user.professional_title }}
                                    </CardDescription>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="flex items-center gap-2 text-sm text-muted-foreground mb-4">
                                <Briefcase class="h-4 w-4" />
                                <span>{{ user.projects_count }} projet{{ user.projects_count > 1 ? 's' : '' }}</span>
                            </div>
                            <Button as-child class="w-full bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary/70">
                                <Link :href="`/portfolio/${user.slug}`">
                                    Voir le portfolio
                                </Link>
                            </Button>
                        </CardContent>
                    </Card>
                </div>

                <div v-else class="text-center py-12">
                    <User class="mx-auto h-12 w-12 text-muted-foreground mb-4" />
                    <h3 class="text-lg font-semibold mb-2">Aucun portfolio disponible</h3>
                    <p class="text-muted-foreground">Il n'y a pas encore de portfolios publics à afficher.</p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t bg-muted/50 mt-12">
            <div class="container mx-auto px-4 py-8">
                <div class="text-center text-sm text-muted-foreground">
                    <p>© {{ new Date().getFullYear() }} Plateforme Portfolio. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
