<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Project;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::with(['project:id,title,slug', 'images', 'parent', 'children'])
            ->whereHas('project', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Galleries/Index', [
            'galleries' => $galleries,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::where('user_id', auth()->id())
            ->orderBy('title')
            ->get();

        $galleries = Gallery::with('project')
            ->whereHas('project', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('title')
            ->get(['id', 'title', 'project_id']);

        return Inertia::render('Galleries/Create', [
            'projects' => $projects,
            'galleries' => $galleries,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'parent_id' => 'nullable|exists:galleries,id',
        ]);

        // Check that the project belongs to the authenticated user
        $project = Project::findOrFail($validated['project_id']);
        Gate::authorize('update', $project);

        // If parent_id is provided, check that it belongs to the same project
        if (isset($validated['parent_id'])) {
            $parent = Gallery::findOrFail($validated['parent_id']);
            if ($parent->project_id !== $validated['project_id']) {
                return back()->withErrors(['parent_id' => 'La galerie parente doit appartenir au même projet.']);
            }
        }

        $gallery = Gallery::create($validated);

        return redirect()->route('galleries.show', $gallery)
            ->with('success', 'Galerie créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        Gate::authorize('view', $gallery);

        $gallery->load([
            'project',
            'parent',
            'children.images',
            'images' => function ($query) {
                $query->orderBy('display_order')->orderBy('id');
            }
        ]);

        return Inertia::render('Galleries/Show', [
            'gallery' => $gallery,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        Gate::authorize('update', $gallery);

        $gallery->load([
            'project',
            'parent',
            'children',
            'images' => function ($query) {
                $query->orderBy('display_order')->orderBy('id');
            }
        ]);

        $projects = Project::where('user_id', auth()->id())
            ->orderBy('title')
            ->get();

        $galleries = Gallery::whereHas('project', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('id', '!=', $gallery->id) // Exclude self
            ->orderBy('title')
            ->get(['id', 'title', 'project_id']);

        return Inertia::render('Galleries/Edit', [
            'gallery' => $gallery,
            'projects' => $projects,
            'galleries' => $galleries,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        Gate::authorize('update', $gallery);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'parent_id' => 'nullable|exists:galleries,id',
        ]);

        // Check that the new project belongs to the authenticated user
        $project = Project::findOrFail($validated['project_id']);
        Gate::authorize('update', $project);

        // If parent_id is provided, check that it belongs to the same project and isn't self
        if (isset($validated['parent_id'])) {
            if ($validated['parent_id'] == $gallery->id) {
                return back()->withErrors(['parent_id' => 'Une galerie ne peut pas être sa propre parente.']);
            }
            $parent = Gallery::findOrFail($validated['parent_id']);
            if ($parent->project_id !== $validated['project_id']) {
                return back()->withErrors(['parent_id' => 'La galerie parente doit appartenir au même projet.']);
            }
        }

        $gallery->update($validated);

        return redirect()->route('galleries.show', $gallery)
            ->with('success', 'Galerie mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        Gate::authorize('delete', $gallery);

        // Delete all images and their files
        foreach ($gallery->images as $image) {
            if ($image->image_path) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        $gallery->delete();

        return redirect()->route('galleries.index')
            ->with('success', 'Galerie supprimée avec succès.');
    }

    /**
     * Add images to a gallery.
     */
    public function addImages(Request $request, Gallery $gallery)
    {
        Gate::authorize('update', $gallery);

        $validated = $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|max:5120', // 5MB max per image
            'alt_texts' => 'nullable|array',
            'alt_texts.*' => 'nullable|string|max:255',
            'descriptions' => 'nullable|array',
            'descriptions.*' => 'nullable|string',
        ]);

        $maxOrder = $gallery->images()->max('display_order') ?? 0;

        foreach ($request->file('images') as $index => $file) {
            $path = $file->store('gallery-images', 'public');

            Image::create([
                'gallery_id' => $gallery->id,
                'image_path' => $path,
                'alt_text' => $validated['alt_texts'][$index] ?? null,
                'description' => $validated['descriptions'][$index] ?? null,
                'display_order' => ++$maxOrder,
            ]);
        }

        return back()->with('success', 'Images ajoutées avec succès.');
    }

    /**
     * Delete an image from a gallery.
     */
    public function deleteImage(Gallery $gallery, Image $image)
    {
        Gate::authorize('update', $gallery);

        if ($image->gallery_id !== $gallery->id) {
            abort(403, 'Cette image n\'appartient pas à cette galerie.');
        }

        if ($image->image_path) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return back()->with('success', 'Image supprimée avec succès.');
    }

    /**
     * Reorder an image within a gallery (move up or down).
     */
    public function reorderImage(Request $request, Gallery $gallery, Image $image)
    {
        Gate::authorize('update', $gallery);

        if ($image->gallery_id !== $gallery->id) {
            abort(403, 'Cette image n\'appartient pas à cette galerie.');
        }

        $validated = $request->validate([
            'direction' => 'required|in:up,down',
        ]);

        $direction = $validated['direction'];
        $currentOrder = $image->display_order ?? 0;

        if ($direction === 'up') {
            $previous = Image::where('gallery_id', $gallery->id)
                ->where('display_order', '<', $currentOrder)
                ->orderBy('display_order', 'desc')
                ->first();

            if ($previous) {
                $tempOrder = $image->display_order;
                $image->display_order = $previous->display_order;
                $previous->display_order = $tempOrder;
                $image->save();
                $previous->save();
            }
        } else {
            $next = Image::where('gallery_id', $gallery->id)
                ->where('display_order', '>', $currentOrder)
                ->orderBy('display_order', 'asc')
                ->first();

            if ($next) {
                $tempOrder = $image->display_order;
                $image->display_order = $next->display_order;
                $next->display_order = $tempOrder;
                $image->save();
                $next->save();
            }
        }

        return back();
    }
}
