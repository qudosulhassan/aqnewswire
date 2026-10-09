<?php

namespace App\Services;

use App\Models\SeoRedirect;

class SeoRedirectService
{
    /**
     * Validate a prospective redirect rule for cycles, chains, and invalid paths.
     */
    public function validateRedirect(string $sourcePath, string $destinationUrl, ?int $excludeId = null): array
    {
        $source = '/' . ltrim(trim($sourcePath), '/');
        $destination = trim($destinationUrl);

        // 1. Self-redirect check
        if ($source === $destination || $source === parse_url($destination, PHP_URL_PATH)) {
            return [
                'valid' => false,
                'error' => 'Self-redirect detected: source path and destination URL cannot be identical.',
                'warning' => null,
            ];
        }

        // 2. Fetch all active redirects for cycle/chain path tracing
        $query = SeoRedirect::where('is_active', true);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        $redirects = $query->get()->keyBy(function ($r) {
            return '/' . ltrim($r->source_path, '/');
        });

        // 3. Cycle / Loop Detection using path traversal
        $visited = [$source => true];
        $current = $this->extractPath($destination);

        while ($current) {
            if (isset($visited[$current])) {
                return [
                    'valid' => false,
                    'error' => "Redirect loop detected: {$source} leads into a cycle through {$current}.",
                    'warning' => null,
                ];
            }
            if (!isset($redirects[$current])) {
                break;
            }
            $visited[$current] = true;
            $current = $this->extractPath($redirects[$current]->destination_url);
        }

        // 4. Chain Detection warning (A -> B -> C)
        $warning = null;
        $destPath = $this->extractPath($destination);
        if ($destPath && isset($redirects[$destPath])) {
            $ultimateTarget = $redirects[$destPath]->destination_url;
            $warning = "Redirect chain detected: '{$destination}' redirects further to '{$ultimateTarget}'. Consider redirecting directly to '{$ultimateTarget}' for optimal crawl efficiency.";
        }

        return [
            'valid' => true,
            'error' => null,
            'warning' => $warning,
            'normalized_source' => $source,
            'normalized_destination' => $destination,
        ];
    }

    /**
     * Scan the entire database of active redirects for chains, loops, and broken targets.
     */
    public function auditRedirectNetwork(): array
    {
        $redirects = SeoRedirect::where('is_active', true)->get();
        $map = $redirects->keyBy(function ($r) {
            return '/' . ltrim($r->source_path, '/');
        });

        $chains = [];
        $loops = [];
        $total301 = 0;
        $total302 = 0;
        $totalHits = 0;

        foreach ($redirects as $r) {
            if ($r->status_code === 301) $total301++;
            if ($r->status_code === 302) $total302++;
            $totalHits += (int) $r->hit_count;

            $source = '/' . ltrim($r->source_path, '/');
            $destPath = $this->extractPath($r->destination_url);

            // Check loop
            if ($destPath) {
                $visited = [$source => true];
                $curr = $destPath;
                $pathTrace = [$source];

                while ($curr && isset($map[$curr])) {
                    $pathTrace[] = $curr;
                    if (isset($visited[$curr])) {
                        $pathTrace[] = $curr;
                        $loops[] = [
                            'rule_id' => $r->id,
                            'source' => $source,
                            'destination' => $r->destination_url,
                            'trace' => implode(' → ', $pathTrace),
                        ];
                        break;
                    }
                    $visited[$curr] = true;
                    $curr = $this->extractPath($map[$curr]->destination_url);
                }

                // Check chain if not a loop
                if (isset($map[$destPath]) && !isset($visited[$destPath])) {
                    $chains[] = [
                        'rule_id' => $r->id,
                        'source' => $source,
                        'intermediate' => $destPath,
                        'ultimate' => $map[$destPath]->destination_url,
                    ];
                }
            }
        }

        return [
            'total_active' => $redirects->count(),
            'total_301' => $total301,
            'total_302' => $total302,
            'total_hits' => $totalHits,
            'chains' => $chains,
            'loops' => $loops,
            'has_anomalies' => count($chains) > 0 || count($loops) > 0,
        ];
    }

    /**
     * Extract internal path from a URL, or null if external.
     */
    protected function extractPath(string $url): ?string
    {
        $parsed = parse_url($url);
        if (isset($parsed['host'])) {
            $appHost = parse_url(config('app.url'), PHP_URL_HOST);
            if ($appHost && strtolower($parsed['host']) === strtolower($appHost)) {
                return '/' . ltrim($parsed['path'] ?? '', '/');
            }
            return null; // External domain
        }
        return '/' . ltrim($parsed['path'] ?? $url, '/');
    }
}
