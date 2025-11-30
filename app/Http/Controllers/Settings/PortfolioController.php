<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    /**
     * Show the portfolio customization page.
     */
    public function edit(Request $request): Response
    {
        $information = $request->user()->information;

        return Inertia::render('settings/Portfolio', [
            'information' => $information,
        ]);
    }

    /**
     * Update the portfolio customization.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6})$/'],
        ]);

        $request->user()->information()->update($validated);

        return back()->with('success', 'Couleur du portfolio mise à jour avec succès.');
    }
}
