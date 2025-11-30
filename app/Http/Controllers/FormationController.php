<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formations = Formation::where('user_id', auth()->id())
            ->orderBy('start_date', 'desc')
            ->paginate(15);

        return Inertia::render('Formations/Index', [
            'formations' => $formations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Formations/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'degree' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer',
        ]);

        $validated['user_id'] = auth()->id();

        Formation::create($validated);

        return redirect()->route('formations.index')
            ->with('success', 'Formation créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Formation $formation)
    {
        Gate::authorize('view', $formation);

        return Inertia::render('Formations/Show', [
            'formation' => $formation->load('user'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formation $formation)
    {
        Gate::authorize('update', $formation);

        return Inertia::render('Formations/Edit', [
            'formation' => $formation,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Formation $formation)
    {
        Gate::authorize('update', $formation);

        $validated = $request->validate([
            'degree' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer',
        ]);

        $formation->update($validated);

        return redirect()->route('formations.index')
            ->with('success', 'Formation modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Formation $formation)
    {
        Gate::authorize('delete', $formation);

        $formation->delete();

        return redirect()->route('formations.index')
            ->with('success', 'Formation supprimée avec succès.');
    }

    /**
     * Reorder a formation (move up or down).
     */
    public function reorder(Request $request, Formation $formation)
    {
        Gate::authorize('update', $formation);

        $validated = $request->validate([
            'direction' => 'required|in:up,down',
        ]);

        $direction = $validated['direction'];
        $currentOrder = $formation->display_order ?? 0;

        if ($direction === 'up') {
            $previous = Formation::where('user_id', auth()->id())
                ->where('display_order', '<', $currentOrder)
                ->orderBy('display_order', 'desc')
                ->first();

            if ($previous) {
                $tempOrder = $formation->display_order;
                $formation->display_order = $previous->display_order;
                $previous->display_order = $tempOrder;
                $formation->save();
                $previous->save();
            }
        } else {
            $next = Formation::where('user_id', auth()->id())
                ->where('display_order', '>', $currentOrder)
                ->orderBy('display_order', 'asc')
                ->first();

            if ($next) {
                $tempOrder = $formation->display_order;
                $formation->display_order = $next->display_order;
                $next->display_order = $tempOrder;
                $formation->save();
                $next->save();
            }
        }

        return back();
    }
}
