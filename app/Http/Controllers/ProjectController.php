<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Technology;
use App\Models\Technique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::with(['technologies', 'techniques', 'user'])
            ->where('user_id', auth()->id());

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('year')) {
            $query->whereYear('completion_date', $request->year);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'display_order');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $projects = $query->paginate(15)->withQueryString();

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
            'filters' => $request->only(['search', 'status', 'type', 'year', 'sort_by', 'sort_order']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Projects/Create', [
            'technologies' => Technology::where('user_id', auth()->id())->orderBy('name')->get(),
            'techniques' => Technique::where('user_id', auth()->id())->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'nullable|string',
            'type' => 'required|in:website,mobile_app,api,design,other',
            'status' => 'required|in:draft,published,archived',
            'context' => 'required|in:academic,internship,freelance,personal,professional',
            'completion_date' => 'required|date',
            'client' => 'nullable|string|max:255',
            'duration_hours' => 'nullable|integer|min:1',
            'role' => 'required|string|max:255',
            'main_image' => 'nullable|image|max:5120',
            'github_link' => 'nullable|url',
            'site_link' => 'nullable|url',
            'display_order' => 'nullable|integer',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technologies,id',
            'techniques' => 'nullable|array',
            'techniques.*' => 'exists:techniques,id',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);

        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('projects', 'public');
        }

        $project = Project::create($validated);

        if ($request->filled('technologies')) {
            $project->technologies()->attach($request->technologies);
        }

        if ($request->filled('techniques')) {
            $project->techniques()->attach($request->techniques);
        }

        return redirect()->route('projects.index')
            ->with('success', 'Projet créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load([
            'technologies:id,name,category,proficiency_level,logo_path,color_code',
            'techniques:id,name,description,proficiency_level',
            'galleries.images',
            'user'
        ]);

        return Inertia::render('Projects/Show', [
            'project' => $project,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        Gate::authorize('update', $project);

        $project->load([
            'technologies:id,name,category,proficiency_level,logo_path,color_code',
            'techniques:id,name,description,proficiency_level'
        ]);

        return Inertia::render('Projects/Edit', [
            'project' => $project,
            'technologies' => Technology::where('user_id', auth()->id())->orderBy('name')->get(),
            'techniques' => Technique::where('user_id', auth()->id())->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'nullable|string',
            'type' => 'required|in:website,mobile_app,api,design,other',
            'status' => 'required|in:draft,published,archived',
            'context' => 'required|in:academic,internship,freelance,personal,professional',
            'completion_date' => 'required|date',
            'client' => 'nullable|string|max:255',
            'duration_hours' => 'nullable|integer|min:1',
            'role' => 'required|string|max:255',
            'main_image' => 'nullable|image|max:5120',
            'github_link' => 'nullable|url',
            'site_link' => 'nullable|url',
            'display_order' => 'nullable|integer',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technologies,id',
            'techniques' => 'nullable|array',
            'techniques.*' => 'exists:techniques,id',
        ]);

        if ($request->title !== $project->title) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);
        }

        if ($request->hasFile('main_image')) {
            if ($project->main_image) {
                Storage::disk('public')->delete($project->main_image);
            }
            $validated['main_image'] = $request->file('main_image')->store('projects', 'public');
        }

        $project->update($validated);

        if ($request->has('technologies')) {
            $project->technologies()->sync($request->technologies ?? []);
        }

        if ($request->has('techniques')) {
            $project->techniques()->sync($request->techniques ?? []);
        }

        return redirect()->route('projects.index')
            ->with('success', 'Projet mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);

        if ($project->main_image) {
            Storage::disk('public')->delete($project->main_image);
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Projet supprimé avec succès.');
    }

    /**
     * Reorder a project (move up or down).
     */
    public function reorder(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'direction' => 'required|in:up,down',
        ]);

        $direction = $validated['direction'];
        $currentOrder = $project->display_order ?? 0;

        if ($direction === 'up') {
            // Find the previous item with lower display_order
            $previous = Project::where('user_id', auth()->id())
                ->where('display_order', '<', $currentOrder)
                ->orderBy('display_order', 'desc')
                ->first();

            if ($previous) {
                $tempOrder = $project->display_order;
                $project->display_order = $previous->display_order;
                $previous->display_order = $tempOrder;
                $project->save();
                $previous->save();
            }
        } else {
            // Find the next item with higher display_order
            $next = Project::where('user_id', auth()->id())
                ->where('display_order', '>', $currentOrder)
                ->orderBy('display_order', 'asc')
                ->first();

            if ($next) {
                $tempOrder = $project->display_order;
                $project->display_order = $next->display_order;
                $next->display_order = $tempOrder;
                $project->save();
                $next->save();
            }
        }

        return back();
    }
}
