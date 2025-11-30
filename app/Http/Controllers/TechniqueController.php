<?php

namespace App\Http\Controllers;

use App\Models\Technique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TechniqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $techniques = Technique::where('user_id', auth()->id())
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15);

        return Inertia::render('Techniques/Index', [
            'techniques' => $techniques,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Techniques/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'proficiency_level' => 'required|in:beginner,intermediate,advanced,expert',
            'display_order' => 'nullable|integer',
        ]);

        $validated['user_id'] = auth()->id();

        Technique::create($validated);

        return redirect()->route('techniques.index')
            ->with('success', 'Technique créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Technique $technique)
    {
        Gate::authorize('view', $technique);

        return Inertia::render('Techniques/Show', [
            'technique' => $technique,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Technique $technique)
    {
        Gate::authorize('update', $technique);

        return Inertia::render('Techniques/Edit', [
            'technique' => $technique,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Technique $technique)
    {
        Gate::authorize('update', $technique);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'proficiency_level' => 'required|in:beginner,intermediate,advanced,expert',
            'display_order' => 'nullable|integer',
        ]);

        $technique->update($validated);

        return redirect()->route('techniques.index')
            ->with('success', 'Technique modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technique $technique)
    {
        Gate::authorize('delete', $technique);

        $technique->delete();

        return redirect()->route('techniques.index')
            ->with('success', 'Technique supprimée avec succès.');
    }

    /**
     * Reorder a technique (move up or down).
     */
    public function reorder(Request $request, Technique $technique)
    {
        Gate::authorize('update', $technique);

        $validated = $request->validate([
            'direction' => 'required|in:up,down',
        ]);

        $direction = $validated['direction'];
        $currentOrder = $technique->display_order ?? 0;

        if ($direction === 'up') {
            $previous = Technique::where('user_id', auth()->id())
                ->where('display_order', '<', $currentOrder)
                ->orderBy('display_order', 'desc')
                ->first();

            if ($previous) {
                $tempOrder = $technique->display_order;
                $technique->display_order = $previous->display_order;
                $previous->display_order = $tempOrder;
                $technique->save();
                $previous->save();
            }
        } else {
            $next = Technique::where('user_id', auth()->id())
                ->where('display_order', '>', $currentOrder)
                ->orderBy('display_order', 'asc')
                ->first();

            if ($next) {
                $tempOrder = $technique->display_order;
                $technique->display_order = $next->display_order;
                $next->display_order = $tempOrder;
                $technique->save();
                $next->save();
            }
        }

        return back();
    }
}
