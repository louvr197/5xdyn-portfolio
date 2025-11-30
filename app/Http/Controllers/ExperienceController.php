<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $experiences = Experience::where('user_id', auth()->id())
            ->orderBy('start_date', 'desc')
            ->paginate(15);

        return Inertia::render('Experiences/Index', [
            'experiences' => $experiences,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Experiences/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'contract_type' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
        ]);

        $validated['user_id'] = auth()->id();

        Experience::create($validated);

        return redirect()->route('experiences.index')
            ->with('success', 'Expérience créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Experience $experience)
    {
        Gate::authorize('view', $experience);

        return Inertia::render('Experiences/Show', [
            'experience' => $experience->load('user'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Experience $experience)
    {
        Gate::authorize('update', $experience);

        return Inertia::render('Experiences/Edit', [
            'experience' => $experience,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Experience $experience)
    {
        Gate::authorize('update', $experience);

        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'contract_type' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
        ]);

        $experience->update($validated);

        return redirect()->route('experiences.index')
            ->with('success', 'Expérience modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Experience $experience)
    {
        Gate::authorize('delete', $experience);

        $experience->delete();

        return redirect()->route('experiences.index')
            ->with('success', 'Expérience supprimée avec succès.');
    }

    /**
     * Reorder an experience (move up or down).
     */
    public function reorder(Request $request, Experience $experience)
    {
        Gate::authorize('update', $experience);

        $validated = $request->validate([
            'direction' => 'required|in:up,down',
        ]);

        $direction = $validated['direction'];
        $currentOrder = $experience->display_order ?? 0;

        if ($direction === 'up') {
            $previous = Experience::where('user_id', auth()->id())
                ->where('display_order', '<', $currentOrder)
                ->orderBy('display_order', 'desc')
                ->first();

            if ($previous) {
                $tempOrder = $experience->display_order;
                $experience->display_order = $previous->display_order;
                $previous->display_order = $tempOrder;
                $experience->save();
                $previous->save();
            }
        } else {
            $next = Experience::where('user_id', auth()->id())
                ->where('display_order', '>', $currentOrder)
                ->orderBy('display_order', 'asc')
                ->first();

            if ($next) {
                $tempOrder = $experience->display_order;
                $experience->display_order = $next->display_order;
                $next->display_order = $tempOrder;
                $experience->save();
                $next->save();
            }
        }

        return back();
    }
}
