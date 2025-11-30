<?php

namespace App\Http\Controllers;

use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TechnologyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $technologies = Technology::where('user_id', auth()->id())
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15);

        return Inertia::render('Technologies/Index', [
            'technologies' => $technologies,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Technologies/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:backend,frontend,database,devops,design,other',
            'proficiency_level' => 'required|in:beginner,intermediate,advanced,expert',
            'logo_path' => 'nullable|file|image|max:2048',
            'color_code' => 'nullable|string|max:7',
            'display_order' => 'nullable|integer',
        ]);

        $validated['user_id'] = auth()->id();

        // Handle logo upload
        if ($request->hasFile('logo_path')) {
            $validated['logo_path'] = $request->file('logo_path')->store('technology-logos');
        }

        Technology::create($validated);

        return redirect()->route('technologies.index')
            ->with('success', 'Technologie créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Technology $technology)
    {
        Gate::authorize('view', $technology);

        return Inertia::render('Technologies/Show', [
            'technology' => $technology,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Technology $technology)
    {
        Gate::authorize('update', $technology);

        return Inertia::render('Technologies/Edit', [
            'technology' => $technology,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Technology $technology)
    {
        Gate::authorize('update', $technology);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:backend,frontend,database,devops,design,other',
            'proficiency_level' => 'required|in:beginner,intermediate,advanced,expert',
            'logo_path' => 'nullable|file|image|max:2048',
            'color_code' => 'nullable|string|max:7',
            'display_order' => 'nullable|integer',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo_path')) {
            if ($technology->logo_path) {
                Storage::delete($technology->logo_path);
            }
            $validated['logo_path'] = $request->file('logo_path')->store('technology-logos');
        } else {
            unset($validated['logo_path']);
        }

        $technology->update($validated);

        return redirect()->route('technologies.index')
            ->with('success', 'Technologie modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technology $technology)
    {
        Gate::authorize('delete', $technology);

        if ($technology->logo_path) {
            Storage::delete($technology->logo_path);
        }

        $technology->delete();

        return redirect()->route('technologies.index')
            ->with('success', 'Technologie supprimée avec succès.');
    }

    /**
     * Reorder a technology (move up or down).
     */
    public function reorder(Request $request, Technology $technology)
    {
        Gate::authorize('update', $technology);

        $validated = $request->validate([
            'direction' => 'required|in:up,down',
        ]);

        $direction = $validated['direction'];
        $currentOrder = $technology->display_order ?? 0;

        if ($direction === 'up') {
            $previous = Technology::where('user_id', auth()->id())
                ->where('display_order', '<', $currentOrder)
                ->orderBy('display_order', 'desc')
                ->first();

            if ($previous) {
                $tempOrder = $technology->display_order;
                $technology->display_order = $previous->display_order;
                $previous->display_order = $tempOrder;
                $technology->save();
                $previous->save();
            }
        } else {
            $next = Technology::where('user_id', auth()->id())
                ->where('display_order', '>', $currentOrder)
                ->orderBy('display_order', 'asc')
                ->first();

            if ($next) {
                $tempOrder = $technology->display_order;
                $technology->display_order = $next->display_order;
                $next->display_order = $tempOrder;
                $technology->save();
                $next->save();
            }
        }

        return back();
    }
}
