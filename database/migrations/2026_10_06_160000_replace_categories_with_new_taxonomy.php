<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\SeoRedirect;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Define the exact new hierarchical category taxonomy
        $taxonomy = [
            'Business' => [
                'slug' => 'business',
                'color' => '#1e3a8a',
                'order' => 1,
                'children' => [
                    'Economy' => 'economy',
                    'Finance' => 'finance',
                    'Media' => 'media',
                    'Real Estate' => 'real-estate',
                    'Energy' => 'energy',
                    'Climate' => 'climate',
                    'Transportation' => 'transportation',
                    'Investigations' => 'investigations',
                    'Industrials' => 'industrials',
                    'Retail' => 'retail',
                    'Wealth' => 'wealth',
                    'Sports' => 'sports',
                    'Life' => 'life',
                    'Small Business' => 'small-business',
                ],
            ],
            'Markets' => [
                'slug' => 'markets',
                'color' => '#059669',
                'order' => 2,
                'children' => [
                    'Pre-Markets' => 'pre-markets',
                    'U.S. Markets' => 'us-markets',
                    'Europe Markets' => 'europe-markets',
                    'China Markets' => 'china-markets',
                    'Asia Markets' => 'asia-markets',
                    'World Markets' => 'world-markets',
                    'Currencies' => 'currencies',
                    'Prediction Markets' => 'prediction-markets',
                    'Cryptocurrency' => 'cryptocurrency',
                    'Futures & Commodities' => 'futures-commodities',
                    'Bonds' => 'bonds',
                ],
            ],
            'Investing' => [
                'slug' => 'investing',
                'color' => '#0284c7',
                'order' => 3,
                'children' => [
                    'Personal Finance' => 'personal-finance',
                    'Fintech' => 'fintech',
                    'Financial Advisors' => 'financial-advisors',
                    'Options Action' => 'options-action',
                    'ETF Street' => 'etf-street',
                    'Earnings' => 'earnings',
                    'Trader Talk' => 'trader-talk',
                ],
            ],
            'Tech' => [
                'slug' => 'tech',
                'color' => '#7c3aed',
                'order' => 4,
                'children' => [
                    'AI' => 'ai',
                    'Enterprise' => 'enterprise',
                    'Internet' => 'internet',
                    'Media' => 'tech-media',
                    'Mobile' => 'mobile',
                    'Social Media' => 'social-media',
                    'Tech Guide' => 'tech-guide',
                    'Cybersecurity' => 'cybersecurity',
                ],
            ],
            'Politics' => [
                'slug' => 'politics',
                'color' => '#dc2626',
                'order' => 5,
                'children' => [
                    'White House' => 'white-house',
                    'Defense' => 'defense',
                    'Congress' => 'congress',
                    'Expanding Opportunity' => 'expanding-opportunity',
                    'Europe Politics' => 'europe-politics',
                    'China Politics' => 'china-politics',
                    'Asia Politics' => 'asia-politics',
                    'World Politics' => 'world-politics',
                ],
            ],
            'LifeStyle' => [
                'slug' => 'lifestyle',
                'color' => '#d97706',
                'order' => 6,
                'children' => [
                    'Careers' => 'careers',
                    'Cars' => 'cars',
                    'Fitness' => 'fitness',
                    'Food & Cooking' => 'food-cooking',
                    'Relationships' => 'relationships',
                    'Travel' => 'travel',
                    'Workplace' => 'workplace',
                ],
            ],
            'Health' => [
                'slug' => 'health',
                'color' => '#0d9488',
                'order' => 7,
                'children' => [
                    'Mental Health' => 'mental-health',
                    'Supplements' => 'supplements',
                    'Hearing Health' => 'hearing-health',
                    'Medical Devices' => 'medical-devices',
                    'Diet' => 'diet',
                ],
            ],
        ];

        $newCategoryMap = [];
        $validNewSlugs = [];

        // 1. Create/Update Parent and Child Categories
        foreach ($taxonomy as $parentName => $parentData) {
            $parent = Category::firstOrNew(['slug' => $parentData['slug']]);
            $parent->name = $parentName;
            $parent->color = $parentData['color'];
            $parent->order = $parentData['order'];
            $parent->parent_id = null;
            $parent->is_featured = true;
            $parent->is_nav_visible = true;
            $parent->save();

            $newCategoryMap[$parentData['slug']] = $parent->id;
            $validNewSlugs[] = $parentData['slug'];

            $childOrder = 1;
            foreach ($parentData['children'] as $childName => $childSlug) {
                $child = Category::firstOrNew(['slug' => $childSlug]);
                $child->name = $childName;
                $child->color = $parentData['color'];
                $child->order = $childOrder++;
                $child->parent_id = $parent->id;
                $child->is_featured = false;
                $child->is_nav_visible = true;
                $child->save();

                $newCategoryMap[$childSlug] = $child->id;
                $validNewSlugs[] = $childSlug;
            }
        }

        // 2. Remap Existing Published Articles by Slug
        $articleMapping = [
            'inside-the-next-frontier-autonomous-enterprise-ai' => 'ai',
            'sovereign-debt-realities-central-banks-face-liquidity-paradox' => 'bonds',
            'the-silicon-renaissance-cleanroom-race-1nm-architecture' => 'enterprise',
            'venture-capital-discards-growth-at-all-costs-playbook' => 'finance',
            'the-reallocation-how-top-billionaires-shifted-80-billion' => 'energy',
            'the-hybrid-executive-why-ceos-redesign-corporate-headquarters' => 'workplace',
        ];

        foreach ($articleMapping as $artSlug => $targetCatSlug) {
            if (isset($newCategoryMap[$targetCatSlug])) {
                Article::where('slug', $artSlug)->update([
                    'category_id' => $newCategoryMap[$targetCatSlug],
                ]);
            }
        }

        // Ensure any remaining articles pointing to retired categories are safely moved
        $fallbackBusinessId = $newCategoryMap['business'];
        $validCategoryIds = array_values($newCategoryMap);
        Article::whereNotIn('category_id', $validCategoryIds)->update([
            'category_id' => $fallbackBusinessId,
        ]);

        // 3. Remove Obsolete / Retired Categories
        Category::whereNotIn('slug', $validNewSlugs)->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert is unnecessary as new taxonomy replaces the legacy schema
    }
};
