<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(15);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['projects', 'experiences', 'formations', 'information']);

        return Inertia::render('Users/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        $user->load('information');

        return Inertia::render('Users/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        // Handle information update
        $informationData = $request->validate([
            'information.last_name' => 'nullable|string|max:255',
            'information.first_name' => 'nullable|string|max:255',
            'information.professional_title' => 'nullable|string|max:255',
            'information.info_email' => 'nullable|email|max:255',
            'information.phone' => 'nullable|string|max:255',
            'information.location' => 'nullable|string|max:255',
            'information.availability' => 'nullable|string',
            'information.bio' => 'nullable|string',
            'information.picture_path' => 'nullable|file|image|max:5120',
            'information.cv_path' => 'nullable|file|mimes:pdf|max:10240',
            'information.social_links' => 'nullable|array',
            'information.theme_color' => 'nullable|string|regex:/^#([A-Fa-f0-9]{6})$/',
        ]);

        if (isset($informationData['information'])) {
            $information = $user->information ?? new Information(['user_id' => $user->id]);

            // Handle picture upload
            if ($request->hasFile('information.picture_path')) {
                if ($information->picture_path) {
                    Storage::delete($information->picture_path);
                }
                $informationData['information']['picture_path'] = $request->file('information.picture_path')->store('pictures');
            } else {
                unset($informationData['information']['picture_path']);
            }

            // Handle CV upload
            if ($request->hasFile('information.cv_path')) {
                if ($information->cv_path) {
                    Storage::delete($information->cv_path);
                }
                $informationData['information']['cv_path'] = $request->file('information.cv_path')->store('cvs');
            } else {
                unset($informationData['information']['cv_path']);
            }

            // Rename info_email to email for information table
            if (isset($informationData['information']['info_email'])) {
                $informationData['information']['email'] = $informationData['information']['info_email'];
                unset($informationData['information']['info_email']);
            }

            if ($information->exists) {
                $information->update($informationData['information']);
            } else {
                $information->fill($informationData['information']);
                $information->save();
            }
        }

        return redirect()->route('users.edit', $user)
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
