<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Category;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use App\Models\RankingItem;
use App\Models\RankingsList;
use App\Models\SeoRedirect;
use App\Models\Tag;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Team & Contributors
        $admin = User::firstOrCreate(['email' => 'admin@aqnewswire.com'], [
            'name' => 'Alexander Vance',
            'slug' => 'alexander-vance',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'title' => 'Editor-in-Chief & Founder',
            'bio' => 'Covering global macroeconomics, venture capital, and tech disruption for over 18 years. Former senior financial correspondent.',
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
            'website' => 'https://apexmedia.test',
            'twitter' => '@alexandervance',
            'linkedin' => 'alexander-vance',
            'is_verified' => true,
        ]);

        $editor = User::firstOrCreate(['email' => 'sophia@apexmedia.com'], [
            'name' => 'Sophia Thorne',
            'slug' => 'sophia-thorne',
            'password' => Hash::make('password123'),
            'role' => 'editor',
            'title' => 'Executive Editor, Technology & AI',
            'bio' => 'Award-winning investigative journalist tracking artificial general intelligence, quantum chips, and Silicon Valley ecosystems.',
            'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
            'website' => 'https://apexmedia.test',
            'twitter' => '@sophiathorne',
            'linkedin' => 'sophia-thorne',
            'is_verified' => true,
        ]);

        $writer1 = User::firstOrCreate(['email' => 'marcus@apexmedia.com'], [
            'name' => 'Marcus Sterling',
            'slug' => 'marcus-sterling',
            'password' => Hash::make('password123'),
            'role' => 'writer',
            'title' => 'Senior Markets & Crypto Correspondent',
            'bio' => 'Former Wall Street quantitative analyst reporting on central bank policies, sovereign bond markets, and decentralized finance.',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
            'twitter' => '@msterling_markets',
            'is_verified' => true,
        ]);

        $contributor = User::firstOrCreate(['email' => 'elena@apexmedia.com'], [
            'name' => 'Dr. Elena Rostova',
            'slug' => 'dr-elena-rostova',
            'password' => Hash::make('password123'),
            'role' => 'contributor',
            'title' => 'Contributor | Partner at Horizon Ventures',
            'bio' => 'Venture capitalist focusing on deeptech, biotechnology, and clean energy transition. Forbes & AQ NEWSWIRE contributor.',
            'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
            'linkedin' => 'elena-rostova',
            'is_verified' => true,
        ]);

        // 2. Categories Taxonomy (7 Parents + 60 Subcategories)
        $taxonomy = [
            'Business' => [
                'slug' => 'business',
                'color' => '#1e3a8a',
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
                ]
            ],
            'Markets' => [
                'slug' => 'markets',
                'color' => '#059669',
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
                ]
            ],
            'Investing' => [
                'slug' => 'investing',
                'color' => '#d97706',
                'children' => [
                    'Personal Finance' => 'personal-finance',
                    'Fintech' => 'fintech',
                    'Financial Advisors' => 'financial-advisors',
                    'Options Action' => 'options-action',
                    'ETF Street' => 'etf-street',
                    'Earnings' => 'earnings',
                    'Trader Talk' => 'trader-talk',
                ]
            ],
            'Tech' => [
                'slug' => 'tech',
                'color' => '#0284c7',
                'children' => [
                    'AI' => 'ai',
                    'Enterprise' => 'enterprise',
                    'Internet' => 'internet',
                    'Media' => 'tech-media',
                    'Mobile' => 'mobile',
                    'Social Media' => 'social-media',
                    'Tech Guide' => 'tech-guide',
                    'Cybersecurity' => 'cybersecurity',
                ]
            ],
            'Politics' => [
                'slug' => 'politics',
                'color' => '#dc2626',
                'children' => [
                    'White House' => 'white-house',
                    'Defense' => 'defense',
                    'Congress' => 'congress',
                    'Expanding Opportunity' => 'expanding-opportunity',
                    'Europe Politics' => 'europe-politics',
                    'China Politics' => 'china-politics',
                    'Asia Politics' => 'asia-politics',
                    'World Politics' => 'world-politics',
                ]
            ],
            'LifeStyle' => [
                'slug' => 'lifestyle',
                'color' => '#db2777',
                'children' => [
                    'Careers' => 'careers',
                    'Cars' => 'cars',
                    'Fitness' => 'fitness',
                    'Food & Cooking' => 'food-cooking',
                    'Relationships' => 'relationships',
                    'Travel' => 'travel',
                    'Workplace' => 'workplace',
                ]
            ],
            'Health' => [
                'slug' => 'health',
                'color' => '#10b981',
                'children' => [
                    'Mental Health' => 'mental-health',
                    'Supplements' => 'supplements',
                    'Hearing Health' => 'hearing-health',
                    'Medical Devices' => 'medical-devices',
                    'Diet' => 'diet',
                ]
            ],
        ];

        $categoryModels = [];
        $order = 1;
        foreach ($taxonomy as $parentName => $data) {
            $parent = Category::firstOrCreate(['slug' => $data['slug']], [
                'name' => $parentName,
                'slug' => $data['slug'],
                'color' => $data['color'],
                'order' => $order++,
                'parent_id' => null,
                'is_featured' => true,
                'is_nav_visible' => true,
            ]);
            $categoryModels[$data['slug']] = $parent;

            $childOrder = 1;
            foreach ($data['children'] as $childName => $childSlug) {
                $child = Category::firstOrCreate(['slug' => $childSlug], [
                    'name' => $childName,
                    'slug' => $childSlug,
                    'color' => $data['color'],
                    'order' => $childOrder++,
                    'parent_id' => $parent->id,
                    'is_featured' => false,
                    'is_nav_visible' => true,
                ]);
                $categoryModels[$childSlug] = $child;
            }
        }

        // 3. Tags
        $tagsList = ['Artificial Intelligence', 'Federal Reserve', 'Wall Street', 'Silicon Valley', 'Quantum Computing', 'Venture Capital', 'Clean Energy', 'Autonomous Vehicles'];
        $tagModels = [];
        foreach ($tagsList as $t) {
            $tagModels[] = Tag::firstOrCreate(['name' => $t]);
        }

        // 4. Articles
        $articles = [
            [
                'user_id' => $admin->id,
                'category_id' => $categoryModels['ai']->id,
                'title' => 'Inside The Next Frontier: Autonomous Enterprise AI Is Reshaping $4 Trillion In Global Labor',
                'slug' => 'inside-the-next-frontier-autonomous-enterprise-ai',
                'subtitle' => 'How Fortune 500 boardrooms are turning autonomous agents into core business infrastructure.',
                'excerpt' => 'As foundation models transition into self-directed agents, corporate executive suites face the most rapid organizational shift in half a century.',
                'content' => '<p class="lead">Across corporate boardrooms from Zurich to Manhattan, the narrative surrounding artificial intelligence has pivoted dramatically over the last six months. What began as experimental deployments of text generation has rapidly matured into autonomous orchestration systems managing end-to-end treasury operations, supply chain logistics, and software architectures.</p>
                <p>According to our proprietary analysis of Fortune 500 strategic capital expenditures, enterprise investments into specialized multi-agent platforms grew by 184% year-over-year. Chief Executive Officers are no longer asking if generative systems can boost individual white-collar productivity—they are restructuring entire business units to operate around autonomous algorithmic cores.</p>
                <h2>The Collapse of Traditional Enterprise SaaS Stacks</h2>
                <p>For two decades, corporate software was defined by siloed dashboards and seat-based licenses. Today, autonomous reasoning engines bypass traditional user interfaces altogether, executing database transactions, negotiating vendor contracts, and predicting inventory deficits in real time.</p>
                <blockquote>"We are witnessing the transformation of software from passive instruments that workers use, into active agents that execute tasks autonomously," notes Dr. Elena Rostova, partner at Horizon Ventures.</blockquote>
                <p>As enterprise adoption accelerates, the question of regulatory governance, latency assurances, and verifiable audits remains the pivotal barrier separating cautious observers from aggressive early movers.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1400&q=85',
                'featured_image_caption' => 'Autonomous computational nodes inside a high-density hyperscale data center.',
                'status' => 'published',
                'published_at' => now()->subHours(2),
                'is_breaking' => true,
                'is_featured' => true,
                'is_trending' => true,
                'is_editors_pick' => true,
                'reading_time_minutes' => 6,
                'view_count' => 14250,
                'meta_title' => 'Autonomous Enterprise AI Reshapes Global Labor | AQ NEWSWIRE',
                'meta_description' => 'Comprehensive investigation into how Fortune 500 boardrooms deploy autonomous agents to automate enterprise operations.',
            ],
            [
                'user_id' => $writer1->id,
                'category_id' => $categoryModels['bonds']->id,
                'title' => 'Sovereign Debt Realities: Central Banks Face A Trillion-Dollar Liquidity Paradox',
                'slug' => 'sovereign-debt-realities-central-banks-face-liquidity-paradox',
                'subtitle' => 'Global bond yields fluctuate as treasury departments navigate heavy debt issuance cycles.',
                'excerpt' => 'With trillions in government debt refinancing due within 18 months, monetary policy committees are walking an unprecedented tightrope.',
                'content' => '<p>Global financial institutions woke up this morning to heightened volatility across benchmark 10-year yield curves. As sovereign debt issuances hit historical records, international asset managers are recalibrating their duration profiles to absorb secondary supply without triggering credit spreads.</p>
                <p>Private equity sponsors and institutional pension funds are simultaneously pivoting capital into high-grade commercial credit and inflation-shielded treasury instruments.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?auto=format&fit=crop&w=1200&q=80',
                'featured_image_caption' => 'Floor traders analyzing live international ticker data.',
                'status' => 'published',
                'published_at' => now()->subHours(5),
                'is_breaking' => false,
                'is_featured' => true,
                'is_trending' => true,
                'is_editors_pick' => false,
                'reading_time_minutes' => 4,
                'view_count' => 8910,
            ],
            [
                'user_id' => $editor->id,
                'category_id' => $categoryModels['enterprise']->id,
                'title' => 'The Silicon Renaissance: Inside The Cleanroom Race For 1-Nanometer Silicon Architecture',
                'slug' => 'the-silicon-renaissance-cleanroom-race-1nm-architecture',
                'subtitle' => 'Extreme ultraviolet lithography and atomic-level precision define the next era of supercomputing.',
                'excerpt' => 'Semiconductor manufacturers are investing upwards of $100 billion to shatter physical barriers and reach sub-nanometer node fabrication.',
                'content' => '<p>The fabrication of silicon microprocessors has reached a milestone previously believed to violate quantum tunneling thresholds. Advanced extreme ultraviolet (EUV) photolithography techniques are enabling fabrication laboratories to print transistor gates measured in atomic angstroms.</p>
                <p>These architectural breakthroughs promise to power the next generation of artificial intelligence superclusters while slashing energy consumption per token by over 40%.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80',
                'featured_image_caption' => 'High-precision wafer fabrication inside a Class 1 cleanroom environment.',
                'status' => 'published',
                'published_at' => now()->subHours(8),
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => true,
                'is_editors_pick' => true,
                'reading_time_minutes' => 5,
                'view_count' => 11200,
            ],
            [
                'user_id' => $contributor->id,
                'category_id' => $categoryModels['finance']->id,
                'title' => 'Venture Capital Discards The "Growth At All Costs" Playbook For Resilient Cash Flows',
                'slug' => 'venture-capital-discards-growth-at-all-costs-playbook',
                'subtitle' => 'How Series B founders are building sustainable unit economics in a disciplined macroeconomic climate.',
                'excerpt' => 'Venture firms are rewarding founders who demonstrate clear capital efficiency, disciplined customer acquisition costs, and early EBITDA profitability.',
                'content' => '<p>The era of subsidizing hyper-growth with cheap venture capital has firmly closed. Today’s premier technology founders are celebrated not for vanity user metrics, but for robust contribution margins and capital-efficient runway management.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1200&q=80',
                'featured_image_caption' => 'Early-stage founders pitching to institutional investors during a private demo day.',
                'status' => 'published',
                'published_at' => now()->subHours(12),
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => false,
                'is_editors_pick' => true,
                'reading_time_minutes' => 3,
                'view_count' => 4560,
            ],
            [
                'user_id' => $admin->id,
                'category_id' => $categoryModels['energy']->id,
                'title' => 'The Reallocation: How The World’s 10 Richest Individuals Shifted $80 Billion Into Clean Energy',
                'slug' => 'the-reallocation-how-top-billionaires-shifted-80-billion',
                'subtitle' => 'From next-generation nuclear fusion to orbital solar grids, private mega-fortunes are funding climate tech.',
                'excerpt' => 'A forensic look into the philanthropic vehicles and private offices of the world’s wealthiest titans reveals an unprecedented pivot toward green infrastructure.',
                'content' => '<p>Private family offices managing multi-billion dollar dynastic wealth have historically favored commercial real estate and public equities. However, over the past 24 months, direct allocations into grid-scale battery storage, small modular nuclear reactors, and direct air carbon capture have skyrocketed.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'featured_image_caption' => 'Financial district towers in downtown London.',
                'status' => 'published',
                'published_at' => now()->subDay(),
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => true,
                'is_editors_pick' => true,
                'reading_time_minutes' => 5,
                'view_count' => 19800,
            ],
            [
                'user_id' => $editor->id,
                'category_id' => $categoryModels['workplace']->id,
                'title' => 'The Hybrid Executive: Why The World’s Top CEOs Are Redesigning Corporate Headquarters',
                'slug' => 'the-hybrid-executive-why-ceos-redesign-corporate-headquarters',
                'subtitle' => 'Physical workspaces are transforming into high-touch collaborative hubs rather than standard cubicle farms.',
                'excerpt' => 'Executive leaders find that attracting elite engineering and creative talent requires transforming office campuses into experiential innovation hubs.',
                'content' => '<p>Corporate real estate strategies are undergoing an intentional architectural overhaul. Companies are shedding square footage while significantly increasing capital investment into acoustic pods, collaboration labs, and wellness facilities.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
                'featured_image_caption' => 'Modern corporate atrium designed for cross-functional collaboration.',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => false,
                'is_editors_pick' => false,
                'reading_time_minutes' => 4,
                'view_count' => 3210,
            ],
        ];

        foreach ($articles as $artData) {
            $art = Article::firstOrCreate(['slug' => $artData['slug']], $artData);
            // attach random tags
            $art->tags()->sync([$tagModels[0]->id, $tagModels[1]->id]);
        }

        // 5. Forbes-Style Rankings & Lists
        $list1 = RankingsList::firstOrCreate(['slug' => 'worlds-top-10-technology-leaders-2026'], [
            'title' => "The World's Top 10 Technology Leaders",
            'year' => 2026,
            'subtitle' => 'The definitive ranking of the executives, researchers, and founders shaping global computation.',
            'description' => 'Our annual proprietary assessment of global tech leaders evaluates market valuation, technological breakthrough significance, patent velocity, and systemic industry influence.',
            'cover_image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80',
            'status' => 'published',
            'is_featured' => true,
        ]);

        $rankingItems = [
            [
                'rank' => 1,
                'name' => 'Dr. Aris Thorne',
                'title_or_role' => 'Founder & CEO',
                'company' => 'Aether Quantum Systems',
                'net_worth_or_metric' => '$42.5 Billion',
                'industry' => 'Quantum Computing',
                'country' => 'United States',
                'bio' => 'Pioneered fault-tolerant topological qubits, achieving quantum supremacy across commercial cryptographic protocols.',
                'photo_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
            ],
            [
                'rank' => 2,
                'name' => 'Claire Chen-Holloway',
                'title_or_role' => 'Chief Executive Officer',
                'company' => 'Synapse Neuromorphic Labs',
                'net_worth_or_metric' => '$38.2 Billion',
                'industry' => 'Semiconductors & AI',
                'country' => 'Taiwan / USA',
                'bio' => 'Led the breakthrough in 1.2nm bio-hybrid neural processors, powering 65% of global autonomous cloud infrastructure.',
                'photo_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80',
            ],
            [
                'rank' => 3,
                'name' => 'Mateo Benitez',
                'title_or_role' => 'Co-Founder & Chief Scientist',
                'company' => 'Helios Stellar Energy',
                'net_worth_or_metric' => '$29.4 Billion',
                'industry' => 'Clean Fusion Energy',
                'country' => 'Spain',
                'bio' => 'Constructed the first commercially viable net-positive compact stellarator reactor supplying baseline grid power to southern Europe.',
                'photo_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
            ],
            [
                'rank' => 4,
                'name' => 'Kavita Ramanujan',
                'title_or_role' => 'Executive Chairwoman',
                'company' => 'OmniBio Therapeutics',
                'net_worth_or_metric' => '$24.8 Billion',
                'industry' => 'Generative Biotechnology',
                'country' => 'India',
                'bio' => 'Spearheaded AI-designed mRNA protein synthesis engines that compressed vaccine discovery cycles from 7 years down to 4 weeks.',
                'photo_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=300&q=80',
            ],
            [
                'rank' => 5,
                'name' => 'Julian Kincaid',
                'title_or_role' => 'Founder & Managing Director',
                'company' => 'Stratos Aerospace',
                'net_worth_or_metric' => '$21.0 Billion',
                'industry' => 'Orbital Logistics',
                'country' => 'United Kingdom',
                'bio' => 'Standardized reusable heavy-lift orbital space freight, reducing payload cost-to-orbit to under $150 per kilogram.',
                'photo_url' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=300&q=80',
            ],
        ];

        foreach ($rankingItems as $item) {
            $item['rankings_list_id'] = $list1->id;
            RankingItem::firstOrCreate([
                'rankings_list_id' => $list1->id,
                'rank' => $item['rank'],
            ], $item);
        }

        // 6. Advertising placements
        Advertisement::firstOrCreate(['title' => 'AQ NEWSWIRE Global Private Wealth Summit 2026'], [
            'zone' => 'billboard',
            'image_url' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=1200&q=80',
            'link_url' => '#',
            'is_active' => true,
        ]);

        Advertisement::firstOrCreate(['title' => 'Horizon Enterprise Cloud - 99.999% SLA'], [
            'zone' => 'sidebar',
            'image_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=600&q=80',
            'link_url' => '#',
            'is_active' => true,
        ]);

        // 7. Multimedia Network (Phase 2)
        Video::firstOrCreate(['slug' => 'keynote-sovereign-compute-race-nuclear-energy'], [
            'title' => 'Keynote: The Sovereign Compute Race & Next-Gen Nuclear Energy',
            'description' => 'Inside the global summit where tech chiefs and energy ministers mapped out dedicated fusion and fission power stations for gigawatt-scale AI campuses.',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'provider' => 'youtube',
            'duration_seconds' => 1420,
            'category_id' => $categoryModels['ai']->id,
            'user_id' => $admin->id,
            'is_featured' => true,
            'published_at' => now()->subDay(),
            'view_count' => 18450,
        ]);

        $podcast = Podcast::firstOrCreate(['slug' => 'the-apex-boardroom-executive-conversations'], [
            'title' => 'The AQ NEWSWIRE Boardroom: Executive Conversations',
            'description' => 'Weekly in-depth discussions with Fortune 500 CEOs, sovereign wealth chairs, and deeptech researchers breaking down global markets.',
            'cover_image' => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=800&q=80',
            'host_name' => 'Alexander Vance',
            'user_id' => $admin->id,
        ]);

        PodcastEpisode::firstOrCreate(['slug' => 'episode-01-structuring-50b-infrastructure-vehicles'], [
            'podcast_id' => $podcast->id,
            'title' => 'Episode 01: Structuring $50B Infrastructure Vehicles for Sovereign AI',
            'episode_number' => 1,
            'season' => 1,
            'audio_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
            'duration' => '42:15',
            'description' => 'Alexander Vance sits down with sovereign fund partners to dissect how national treasuries are financing dedicated compute grids.',
            'transcript' => 'Welcome to the premiere edition of The AQ NEWSWIRE Boardroom. Today we explore the intersection of sovereign finance and next-generation compute...',
            'published_at' => now()->subDays(3),
        ]);

        // 8. SEO 301 Redirect Rule (Phase 2)
        SeoRedirect::firstOrCreate(['source_path' => '/legacy/tech-report-2025'], [
            'destination_url' => '/category/tech',
            'status_code' => 301,
            'is_active' => true,
        ]);
    }
}
