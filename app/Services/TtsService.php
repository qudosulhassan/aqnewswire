<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TtsService
{
    /**
     * Check if external cloud TTS provider (e.g., Polly, ElevenLabs) is configured.
     */
    public function isExternalProviderConfigured(): bool
    {
        return !empty(config('services.tts.key'));
    }

    /**
     * Generate or fetch audio stream URL for an article.
     */
    public function getAudioUrlForArticle(Article $article): ?string
    {
        if (!empty($article->audio_url)) {
            return $article->audio_url;
        }

        return null;
    }

    /**
     * Set synthesized audio track for an article.
     */
    public function setArticleAudio(Article $article, string $url, ?string $duration = null): Article
    {
        $article->update([
            'audio_url' => $url,
            'audio_duration' => $duration ?? ($article->reading_time_minutes . ':00'),
            'audio_generated_at' => now(),
        ]);

        return $article;
    }
}
