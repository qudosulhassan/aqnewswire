<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('articles')->orderBy('order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|unique:categories,slug',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:500',
            'is_nav_visible' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'integer',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $validated['is_nav_visible'] = $request->boolean('is_nav_visible');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['order'] = $request->input('order', 0);

        Category::create($validated);

        return back()->with('success', 'Category created successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }
}
