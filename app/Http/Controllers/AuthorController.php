<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function show(string $slug)
    {
        $author = User::where('slug', $slug)->firstOrFail();

        $articles = Article::published()
            ->where('user_id', $author->id)
            ->with('category')
            ->latest('published_at')
            ->paginate(10);

        return view('authors.show', compact('author', 'articles'));
    }
}
