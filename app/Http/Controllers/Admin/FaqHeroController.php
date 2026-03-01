<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqHero;
use App\Models\Organization;
use App\Traits\RevalidatesNextJs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqHeroController extends Controller
{
    use RevalidatesNextJs;
    // Decide whether to create or edit
    public function faqhero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $faqhero = FaqHero::where('organization_id', $organization->id)->first();

        if ($faqhero) {
            return redirect()->route('faqhero.edit', [
                'slug' => $slug,
                'id'   => $faqhero->id
            ]);
        }

        return redirect()->route('faqhero.create', $slug);
    }


    // Show create form
    public function createfaqhero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.faqhero.faqhero', compact('organization'));
    }


    // Store new hero
    public function storefaqhero(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        FaqHero::create([
            'organization_id' => $organization->id,
            'title'           => $validated['title'],
            'content'         => $validated['content'] ?? null,
            'is_home'         => $request->boolean('is_home', false),
            'created_by'      => Auth::id(),
        ]);
        $this->revalidatePaths([
            '/',
            '/faq'
        ]);

        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', 'FAQ Hero created successfully.');
    }


    // Show edit form
    public function editfaqhero($slug, $id)
    {
        $faqhero = FaqHero::findOrFail($id);

        $organization = Organization::findOrFail($faqhero->organization_id);

        return view('admin.faqhero.faqheroedit', compact('organization', 'faqhero'));
    }


    // Update hero
    public function updatefaqhero(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $faqhero = FaqHero::where('organization_id', $organization->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        $faqhero->update([
            'title'      => $validated['title'],
            'content'    => $validated['content'] ?? null,
            'is_home'    => $request->boolean('is_home'),
            'updated_by' => Auth::id(),
        ]);

        $message = 'FAQ Hero updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => true,
                'message' => $message,
            ]);
        }
        $this->revalidatePaths([
            '/',
            '/faq'
        ]);

        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', $message);
    }
}
