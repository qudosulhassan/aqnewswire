<?php

namespace App\Routing;

use App\Models\Article;
use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;

class CustomUrlGenerator extends BaseUrlGenerator
{
    /**
     * Get the URL to a named route.
     *
     * @param  \BackedEnum|string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    public function route($name, $parameters = [], $absolute = true)
    {
        if ($name === 'articles.show') {
            $parameters = $this->formatArticleParameters($parameters);
        }

        return parent::route($name, $parameters, $absolute);
    }

    /**
     * Normalize parameters for articles.show to always provide category and slug.
     *
     * @param  mixed  $parameters
     * @return mixed
     */
    protected function formatArticleParameters($parameters)
    {
        // 1. Single Article model instance passed: route('articles.show', $article)
        if ($parameters instanceof Article) {
            return [
                'category' => $parameters->category?->slug ?? 'news',
                'slug' => $parameters->slug,
            ];
        }

        // 2. Single string slug passed: route('articles.show', 'some-slug')
        if (is_string($parameters) || is_int($parameters)) {
            $article = Article::where('slug', (string) $parameters)->with('category')->first();
            return [
                'category' => $article?->category?->slug ?? 'news',
                'slug' => (string) $parameters,
            ];
        }

        // 3. Array passed
        if (is_array($parameters)) {
            // Case: ['some-slug'] (numeric array with 1 item)
            if (count($parameters) === 1 && array_key_exists(0, $parameters)) {
                if ($parameters[0] instanceof Article) {
                    return [
                        'category' => $parameters[0]->category?->slug ?? 'news',
                        'slug' => $parameters[0]->slug,
                    ];
                } elseif (is_string($parameters[0])) {
                    $article = Article::where('slug', $parameters[0])->with('category')->first();
                    return [
                        'category' => $article?->category?->slug ?? 'news',
                        'slug' => $parameters[0],
                    ];
                }
            }

            // Case: ['slug' => 'some-slug'] without category
            if (isset($parameters['slug']) && !isset($parameters['category'])) {
                $article = Article::where('slug', $parameters['slug'])->with('category')->first();
                $parameters['category'] = $article?->category?->slug ?? 'news';
                return $parameters;
            }

            // Case: [$category, $articleInstance]
            if (isset($parameters[1]) && $parameters[1] instanceof Article) {
                return [
                    'category' => is_string($parameters[0]) ? $parameters[0] : ($parameters[1]->category?->slug ?? 'news'),
                    'slug' => $parameters[1]->slug,
                ];
            }
        }

        return $parameters;
    }
}
