<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Experience;
use App\Models\Formation;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display all public portfolios.
     */
    public function index()
    {
        // Récupère tous les utilisateurs avec leurs informations
        $users = User::with('information')
            ->whereHas('information') // Seulement les utilisateurs qui ont des informations
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'slug' => $user->slug,
                    'first_name' => $user->information->first_name ?? '',
                    'last_name' => $user->information->last_name ?? '',
                    'professional_title' => $user->information->professional_title ?? '',
                    'picture_path' => $user->information->picture_path ?? null,
                    'projects_count' => Project::where('user_id', $user->id)->count(),
                ];
            });

        return Inertia::render('Public/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Display the public home page for a user.
     */
    public function home(string $slug)
    {
        // Trouve l'utilisateur par son slug
        $user = User::with('information')->where('slug', $slug)->firstOrFail();

        // Récupère les projets de cet utilisateur
        $featuredProjects = Project::with(['technologies', 'techniques'])
            ->where('user_id', $user->id)
            ->orderBy('display_order', 'asc')
            ->limit(6)
            ->get();

        // Statistiques de cet utilisateur
        $stats = [
            'projectsCount' => Project::where('user_id', $user->id)->count(),
            'experiencesCount' => Experience::where('user_id', $user->id)->count(),
            'formationsCount' => Formation::where('user_id', $user->id)->count(),
        ];

        return Inertia::render('Public/Home', [
            'user' => $user,
            'information' => $user->information,
            'featuredProjects' => $featuredProjects,
            'projectsCount' => Project::where('user_id', $user->id)->count(),
            'experiencesCount' => Experience::where('user_id', $user->id)->count(),
            'formationsCount' => Formation::where('user_id', $user->id)->count(),
        ]);
    }

    /**
     * Display the public projects list.
     */
    public function projects(string $slug, Request $request)
    {
        $user = User::with('information')->where('slug', $slug)->firstOrFail();

        // Récupère tous les projets avec filtres optionnels
        $query = Project::with(['technologies', 'techniques'])
            ->where('user_id', $user->id);

        // Filtre par compétence (technologie ou technique) si fourni
        if ($request->filled('skill')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('technologies', function ($techQuery) use ($request) {
                    $techQuery->where('technologies.id', $request->skill);
                })->orWhereHas('techniques', function ($techQuery) use ($request) {
                    $techQuery->where('techniques.id', $request->skill);
                });
            });
        }

        // Filtre par année si fourni
        if ($request->filled('year')) {
            $query->whereYear('completion_date', $request->year);
        }

        // Recherche dans titre et description
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }

        $projects = $query->orderBy('display_order', 'asc')
            ->paginate(12)
            ->withQueryString();

        // Récupère toutes les technologies et techniques de l'utilisateur pour les filtres
        $technologies = \App\Models\Technology::whereHas('projects', function ($q) use ($user) {
            $q->where('projects.user_id', $user->id);
        })->orderBy('name')->get();

        $techniques = \App\Models\Technique::whereHas('projects', function ($q) use ($user) {
            $q->where('projects.user_id', $user->id);
        })->orderBy('name')->get();

        // Combine technologies et techniques pour le filtre unique
        $skills = $technologies->map(function ($tech) {
            return [
                'id' => 'tech-' . $tech->id,
                'name' => $tech->name,
                'type' => 'technology'
            ];
        })->merge($techniques->map(function ($tech) {
            return [
                'id' => 'technique-' . $tech->id,
                'name' => $tech->name,
                'type' => 'technique'
            ];
        }))->sortBy('name')->values();

        return Inertia::render('Public/Projects/Index', [
            'user' => $user,
            'information' => $user->information,
            'projects' => $projects,
            'skills' => $skills,
            'filters' => $request->only(['search', 'skill', 'year']),
        ]);
    }

    /**
     * Display a single project.
     */
    public function projectShow(string $userSlug, string $projectSlug)
    {
        $user = User::with('information')->where('slug', $userSlug)->firstOrFail();

        // Récupère le projet avec toutes ses relations
        $project = Project::with([
            'technologies:id,name,category,proficiency_level,logo_path,color_code',
            'techniques:id,name,description,proficiency_level',
            'galleries.images',
        ])
            ->where('user_id', $user->id)
            ->where('slug', $projectSlug)
            ->firstOrFail();

        return Inertia::render('Public/Projects/Show', [
            'user' => $user,
            'information' => $user->information,
            'project' => $project,
        ]);
    }

    /**
     * Display the about page.
     */
    public function about(string $slug)
    {
        $user = User::with('information')->where('slug', $slug)->firstOrFail();

        // Récupère les expériences et formations
        $experiences = Experience::where('user_id', $user->id)
            ->orderBy('display_order', 'asc')
            ->get();

        $formations = Formation::where('user_id', $user->id)
            ->orderBy('display_order', 'asc')
            ->get();

        // Récupère les technologies avec niveau de maîtrise
        $technologies = $user->technologies()
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return Inertia::render('Public/About', [
            'user' => $user,
            'information' => $user->information,
            'experiences' => $experiences,
            'formations' => $formations,
            'technologies' => $technologies,
        ]);
    }

    /**
     * Display the contact page.
     */
    public function contact(string $slug)
    {
        $user = User::with('information')->where('slug', $slug)->firstOrFail();

        return Inertia::render('Public/Contact', [
            'user' => $user,
            'information' => $user->information,
        ]);
    }

    /**
     * Handle contact form submission.
     */
    public function contactSubmit(string $slug, Request $request)
    {
        $user = User::with('information')->where('slug', $slug)->firstOrFail();

        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'honeypot' => 'nullable|max:0', // Anti-spam: doit rester vide
        ]);

        // Vérifie le honeypot (anti-spam)
        if ($request->filled('honeypot')) {
            // Si le honeypot est rempli, c'est un bot - on fait semblant que ça a fonctionné
            return back()->with('success', 'Votre message a été envoyé avec succès.');
        }

        try {
            // Envoie l'email au propriétaire du portfolio
            \Mail::to($user->information->email)->send(
                new \App\Mail\ContactMail(
                    senderName: $validated['name'],
                    senderEmail: $validated['email'],
                    subject: $validated['subject'],
                    messageContent: $validated['message']
                )
            );

            return back()->with('success', 'Votre message a été envoyé avec succès. Je vous répondrai dans les plus brefs délais.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi du message de contact: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.');
        }
    }
}
