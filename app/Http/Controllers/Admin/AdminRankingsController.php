<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RankingItem;
use App\Models\RankingsList;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminRankingsController extends Controller
{
    public function index()
    {
        $lists = RankingsList::withCount('items')->latest()->paginate(15);
        return view('admin.rankings.index', compact('lists'));
    }

    public function create()
    {
        return view('admin.rankings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:2100',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title'] . ' ' . $validated['year']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['status'] = 'published';

        $list = RankingsList::create($validated);

        return redirect()->route('admin.rankings.edit', $list)->with('success', 'Rankings list created! You can now add ranking items.');
    }

    public function edit(RankingsList $ranking)
    {
        $ranking->load(['items' => fn($q) => $q->orderBy('rank')]);
        return view('admin.rankings.edit', ['list' => $ranking]);
    }

    public function storeItem(Request $request, RankingsList $ranking)
    {
        $validated = $request->validate([
            'rank' => 'required|integer',
            'name' => 'required|string|max:255',
            'title_or_role' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'net_worth_or_metric' => 'nullable|string|max:100',
            'industry' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'photo_url' => 'nullable|string|max:500',
        ]);

        $validated['rankings_list_id'] = $ranking->id;
        RankingItem::create($validated);

        return back()->with('success', 'Item added to ranking list!');
    }

    public function destroyItem(RankingItem $item)
    {
        $item->delete();
        return back()->with('success', 'Ranking item deleted.');
    }
}
