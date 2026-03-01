<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsandnotices;
use App\Traits\RevalidatesNextJs;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsandnoticeController extends Controller
{
    use RevalidatesNextJs;
    public function newshero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $newshero = Newsandnotices::where('organization_id', $organization->id)->first();

        if ($newshero) {
            return redirect()->route('newshero.edit', [
                'slug' => $slug,
                'id' => $newshero->id
            ]);
        }

        return redirect()->route('newshero.create', $slug);
    }

    public function createnewshero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.newsandnotice.newshero', compact('organization'));
    }

    public function storenewshero(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        $newshero = Newsandnotices::create([
            'organization_id' => $organization->id,
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'is_home' => $request->boolean('is_home', false),
            'created_by' => Auth::id(),
        ]);
        $this->revalidatePaths([
            '/',
            'news-notices'
        ]);
        return redirect()->route('dashboard', $organization->slug)
            ->with('success', 'About Hero created successfully.');
    }

    public function editnewshero($slug, $id)
    {
        $newshero = Newsandnotices::findOrFail($id);

        $organization = Organization::findOrFail($newshero->organization_id);

        return view('admin.newsandnotice.newsheroedit', compact('organization', 'newshero'));
    }

    public function updatenewshero(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        $newshero = Newsandnotices::where('organization_id', $organization->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        $newshero->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'is_home' => $request->boolean('is_home'),
            'updated_by' => Auth::id(),
        ]);

        $message = 'About Hero updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

        $this->revalidatePaths([
                '/',
                'news-notices'
            ]);

        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', $message);
    }
}
