<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageHero;
use App\Traits\RevalidatesNextJs;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageHeroController extends Controller
{
    use RevalidatesNextJs;
    // Decide create or edit automatically
    public function messagehero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $messagehero = MessageHero::where('organization_id', $organization->id)->first();

        if ($messagehero) {
            return redirect()->route('messagehero.edit', [
                'slug' => $slug,
                'id' => $messagehero->id
            ]);
        }

        return redirect()->route('messagehero.create', $slug);
    }


    // Show create form
    public function createmessagehero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.messagehero.messagehero', compact('organization'));
    }


    // Store hero
    public function storemessagehero(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        MessageHero::create([
            'organization_id' => $organization->id,
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'is_home' => $request->boolean('is_home', false),
            'created_by' => Auth::id(),
        ]);
        $this->revalidatePaths([
            '/about/messages'
        ]);
        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', 'Message Hero created successfully.');
    }


    // Show edit form
    public function editmessagehero($slug, $id)
    {
        $messagehero = MessageHero::findOrFail($id);

        $organization = Organization::findOrFail($messagehero->organization_id);

        return view('admin.messagehero.messageheroedit', compact('organization', 'messagehero'));
    }


    // Update hero
    public function updatemessagehero(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $messagehero = MessageHero::where('organization_id', $organization->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        $messagehero->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'is_home' => $request->boolean('is_home'),
            'updated_by' => Auth::id(),
        ]);

        $message = 'Message Hero updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }
        $this->revalidatePaths([
            '/about/messages'
        ]);

        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', $message);
    }
}
