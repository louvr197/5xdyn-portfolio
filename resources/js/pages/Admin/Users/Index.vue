<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { UserPlus, MoreVertical, Edit, Trash2, ShieldCheck, ShieldOff, FolderKanban, Briefcase, GraduationCap } from 'lucide-vue-next';
import { ref } from 'vue';

interface User {
    id: number;
    name: string;
    slug: string;
    email: string;
    role: 'user' | 'admin';
    projects_count: number;
    experiences_count: number;
    formations_count: number;
    created_at: string;
}

interface Props {
    users: {
        data: User[];
        links: any[];
        current_page: number;
        last_page: number;
    };
}

const props = defineProps<Props>();

const userToDelete = ref<User | null>(null);
const showDeleteDialog = ref(false);
const userToToggleRole = ref<User | null>(null);
const showToggleRoleDialog = ref(false);

const confirmDelete = (user: User) => {
    userToDelete.value = user;
    showDeleteDialog.value = true;
};

const deleteUser = () => {
    if (userToDelete.value) {
        router.delete(`/admin/users/${userToDelete.value.id}`, {
            onSuccess: () => {
                showDeleteDialog.value = false;
                userToDelete.value = null;
            },
        });
    }
};

const confirmToggleRole = (user: User) => {
    userToToggleRole.value = user;
    showToggleRoleDialog.value = true;
};

const toggleRole = () => {
    if (userToToggleRole.value) {
        router.post(`/admin/users/${userToToggleRole.value.id}/toggle-role`, {}, {
            onSuccess: () => {
                showToggleRoleDialog.value = false;
                userToToggleRole.value = null;
            },
        });
    }
};

const getRoleBadgeVariant = (role: string) => {
    return role === 'admin' ? 'default' : 'secondary';
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Gestion des utilisateurs" />

    <AppLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Gestion des utilisateurs</h1>
                    <p class="text-muted-foreground mt-1">
                        Créer, modifier et gérer les utilisateurs de la plateforme
                    </p>
                </div>
                <Button as-child>
                    <Link href="/admin/users/create">
                        <UserPlus class="mr-2 h-4 w-4" />
                        Nouvel utilisateur
                    </Link>
                </Button>
            </div>

            <!-- Users Table -->
            <Card>
                <CardHeader>
                    <CardTitle>Utilisateurs ({{ users.data.length }})</CardTitle>
                    <CardDescription>Liste de tous les utilisateurs enregistrés</CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nom</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Rôle</TableHead>
                                <TableHead class="text-center">Projets</TableHead>
                                <TableHead class="text-center">Expériences</TableHead>
                                <TableHead class="text-center">Formations</TableHead>
                                <TableHead>Date de création</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="user in users.data" :key="user.id">
                                <TableCell>
                                    <div>
                                        <div class="font-medium">{{ user.name }}</div>
                                        <div class="text-sm text-muted-foreground">@{{ user.slug }}</div>
                                    </div>
                                </TableCell>
                                <TableCell>{{ user.email }}</TableCell>
                                <TableCell>
                                    <Badge :variant="getRoleBadgeVariant(user.role)">
                                        {{ user.role === 'admin' ? 'Administrateur' : 'Utilisateur' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <FolderKanban class="h-4 w-4 text-muted-foreground" />
                                        <span>{{ user.projects_count }}</span>
                                    </div>
                                </TableCell>
                                <TableCell class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <Briefcase class="h-4 w-4 text-muted-foreground" />
                                        <span>{{ user.experiences_count }}</span>
                                    </div>
                                </TableCell>
                                <TableCell class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <GraduationCap class="h-4 w-4 text-muted-foreground" />
                                        <span>{{ user.formations_count }}</span>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <span class="text-sm text-muted-foreground">{{ formatDate(user.created_at) }}</span>
                                </TableCell>
                                <TableCell class="text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" size="icon">
                                                <MoreVertical class="h-4 w-4" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuLabel>Actions</DropdownMenuLabel>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem as-child>
                                                <Link :href="`/admin/users/${user.id}/edit`">
                                                    <Edit class="mr-2 h-4 w-4" />
                                                    Modifier
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="confirmToggleRole(user)">
                                                <ShieldCheck v-if="user.role === 'user'" class="mr-2 h-4 w-4" />
                                                <ShieldOff v-else class="mr-2 h-4 w-4" />
                                                {{ user.role === 'user' ? 'Promouvoir admin' : 'Rétrograder' }}
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem @click="confirmDelete(user)" class="text-destructive">
                                                <Trash2 class="mr-2 h-4 w-4" />
                                                Supprimer
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <!-- Pagination -->
                    <div v-if="users.last_page > 1" class="flex items-center justify-center gap-2 mt-4">
                        <Link
                            v-for="link in users.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            :class="[
                                'px-3 py-2 text-sm rounded-md',
                                link.active
                                    ? 'bg-primary text-primary-foreground'
                                    : 'bg-muted hover:bg-muted/80',
                                !link.url && 'opacity-50 cursor-not-allowed',
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Delete Dialog -->
        <Dialog v-model:open="showDeleteDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Êtes-vous sûr ?</DialogTitle>
                    <DialogDescription>
                        Cette action est irréversible. L'utilisateur <strong>{{ userToDelete?.name }}</strong> et toutes ses données seront définitivement supprimés.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="showDeleteDialog = false">Annuler</Button>
                    <Button @click="deleteUser" variant="destructive">
                        Supprimer
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Toggle Role Dialog -->
        <Dialog v-model:open="showToggleRoleDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Changer le rôle de l'utilisateur</DialogTitle>
                    <DialogDescription>
                        Voulez-vous vraiment {{ userToToggleRole?.role === 'user' ? 'promouvoir' : 'rétrograder' }}
                        <strong>{{ userToToggleRole?.name }}</strong>
                        {{ userToToggleRole?.role === 'user' ? 'en tant qu\'administrateur' : 'en tant qu\'utilisateur' }} ?
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="showToggleRoleDialog = false">Annuler</Button>
                    <Button @click="toggleRole">
                        Confirmer
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
