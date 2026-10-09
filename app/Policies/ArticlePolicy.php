<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessAdmin() || $user->isContributor();
    }

    public function view(User $user, Article $article): bool
    {
        return $article->status === 'published' 
            || $article->user_id === $user->id 
            || $user->canAccessAdmin();
    }

    public function create(User $user): bool
    {
        return $user->canAccessAdmin() || $user->isContributor();
    }

    public function update(User $user, Article $article): bool
    {
        if ($user->isEditor() || $user->isAdmin()) {
            return true;
        }

        // Contributors can only update their own articles if not published yet
        return $article->user_id === $user->id && in_array($article->status, ['draft', 'submitted']);
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    public function publish(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }
}
