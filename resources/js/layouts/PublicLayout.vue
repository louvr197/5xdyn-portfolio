<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Menu, X } from 'lucide-vue-next';

const mobileMenuOpen = ref(false);
const page = usePage();

// Récupère le slug de l'utilisateur depuis les props de la page
const userSlug = computed(() => page.props.user?.slug || '');
</script>

<template>
    <div class="min-h-screen bg-background">
        <!-- Header / Navigation -->
        <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
            <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center gap-8">
                        <Link :href="`/portfolio/${userSlug}`" class="text-xl font-bold">
                            Portfolio
                        </Link>
                        <Link
                            href="/portfolios"
                            class="text-sm font-medium transition-colors hover:text-primary text-muted-foreground"
                        >
                            Tous les portfolios
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex md:items-center md:space-x-8">
                        <Link
                            :href="`/portfolio/${userSlug}`"
                            class="text-sm font-medium transition-colors hover:text-primary"
                            :class="$page.component === 'Public/Home' ? 'text-primary' : 'text-muted-foreground'"
                        >
                            Accueil
                        </Link>
                        <Link
                            :href="`/portfolio/${userSlug}/projets`"
                            class="text-sm font-medium transition-colors hover:text-primary"
                            :class="$page.component?.startsWith('Public/Projects') ? 'text-primary' : 'text-muted-foreground'"
                        >
                            Projets
                        </Link>
                        <Link
                            :href="`/portfolio/${userSlug}/a-propos`"
                            class="text-sm font-medium transition-colors hover:text-primary"
                            :class="$page.component === 'Public/About' ? 'text-primary' : 'text-muted-foreground'"
                        >
                            À Propos
                        </Link>
                        <Link
                            :href="`/portfolio/${userSlug}/contact`"
                            class="text-sm font-medium transition-colors hover:text-primary"
                            :class="$page.component === 'Public/Contact' ? 'text-primary' : 'text-muted-foreground'"
                        >
                            Contact
                        </Link>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex md:hidden">
                        <button
                            type="button"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="inline-flex items-center justify-center rounded-md p-2 text-muted-foreground hover:bg-accent hover:text-accent-foreground focus:outline-none"
                        >
                            <span class="sr-only">Ouvrir le menu</span>
                            <Menu v-if="!mobileMenuOpen" class="h-6 w-6" />
                            <X v-else class="h-6 w-6" />
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <div v-show="mobileMenuOpen" class="md:hidden pb-4">
                    <div class="space-y-1">
                        <Link
                            :href="`/portfolio/${userSlug}`"
                            class="block px-3 py-2 rounded-md text-base font-medium transition-colors hover:bg-accent"
                            :class="$page.component === 'Public/Home' ? 'text-primary bg-accent' : 'text-muted-foreground'"
                            @click="mobileMenuOpen = false"
                        >
                            Accueil
                        </Link>
                        <Link
                            :href="`/portfolio/${userSlug}/projets`"
                            class="block px-3 py-2 rounded-md text-base font-medium transition-colors hover:bg-accent"
                            :class="$page.component?.startsWith('Public/Projects') ? 'text-primary bg-accent' : 'text-muted-foreground'"
                            @click="mobileMenuOpen = false"
                        >
                            Projets
                        </Link>
                        <Link
                            :href="`/portfolio/${userSlug}/a-propos`"
                            class="block px-3 py-2 rounded-md text-base font-medium transition-colors hover:bg-accent"
                            :class="$page.component === 'Public/About' ? 'text-primary bg-accent' : 'text-muted-foreground'"
                            @click="mobileMenuOpen = false"
                        >
                            À Propos
                        </Link>
                        <Link
                            :href="`/portfolio/${userSlug}/contact`"
                            class="block px-3 py-2 rounded-md text-base font-medium transition-colors hover:bg-accent"
                            :class="$page.component === 'Public/Contact' ? 'text-primary bg-accent' : 'text-muted-foreground'"
                            @click="mobileMenuOpen = false"
                        >
                            Contact
                        </Link>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t bg-muted/50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- About Section -->
                    <div>
                        <h3 class="font-semibold mb-3">Portfolio</h3>
                        <p class="text-sm text-muted-foreground">
                            Découvrez mes projets et mon parcours professionnel.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="font-semibold mb-3">Navigation</h3>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <Link href="/" class="text-muted-foreground hover:text-primary transition-colors">
                                    Accueil
                                </Link>
                            </li>
                            <li>
                                <Link href="/projets" class="text-muted-foreground hover:text-primary transition-colors">
                                    Projets
                                </Link>
                            </li>
                            <li>
                                <Link href="/a-propos" class="text-muted-foreground hover:text-primary transition-colors">
                                    À Propos
                                </Link>
                            </li>
                            <li>
                                <Link href="/contact" class="text-muted-foreground hover:text-primary transition-colors">
                                    Contact
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Social / Legal -->
                    <div>
                        <h3 class="font-semibold mb-3">Légal</h3>
                        <p class="text-sm text-muted-foreground">
                            © {{ new Date().getFullYear() }} Tous droits réservés.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
