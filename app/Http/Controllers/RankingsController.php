<?php

namespace App\Http\Controllers;

use App\Models\RankingsList;
use Illuminate\Http\Request;

class RankingsController extends Controller
{
    public function index()
    {
        $lists = RankingsList::where('status', 'published')
            ->withCount('items')
            ->orderByDesc('year')
            ->latest()
            ->paginate(12);

        return view('rankings.index', compact('lists'));
    }

    public function show(string $slug, Request $request)
    {
        $list = RankingsList::where('slug', $slug)->firstOrFail();

        $query = $list->items();

        // Optional filtering by industry or country
        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('company', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->paginate(20)->withQueryString();

        $industries = $list->items()->whereNotNull('industry')->distinct()->pluck('industry');
        $countries = $list->items()->whereNotNull('country')->distinct()->pluck('country');

        return view('rankings.show', compact('list', 'items', 'industries', 'countries'));
    }
}
