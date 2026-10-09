@extends('layouts.admin')

@section('title', 'Write New Story — Article Editor 2.0')

@section('content')
<div class="max-w-[1600px] mx-auto pb-16">

    <!-- Sticky Top Editor Control Bar -->
    <div class="sticky top-2 z-30 bg-white/95 backdrop-blur-md border border-slate-200/90 rounded-2xl px-5 py-3 shadow-md mb-6 flex flex-wrap items-center justify-between gap-4">
        
        <!-- Left: Navigation & Context -->
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.articles.index') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Discard & Back</span>
            </a>

            <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 ring-1 ring-blue-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    New Story Draft
                </span>
                <span class="text-xs text-slate-400 font-medium hidden sm:inline">&mdash; AQ NEWSWIRE Editorial Workspace</span>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2.5">
            <!-- Reading Time & Word Count Pills -->
            <div class="hidden xl:flex items-center gap-2 px-3 py-1 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600">
                <span>Words: <strong class="text-slate-900" id="topWordCount">0</strong></span>
                <span class="text-slate-300">&bull;</span>
                <span>Read: <strong class="text-slate-900" id="topReadingTime">1 min</strong></span>
            </div>

            <!-- Primary Form Submit Button -->
            <button type="submit" 
                    form="articleCreateForm"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-xl shadow-xs transition inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Save & Initialize Story</span>
            </button>
        </div>
    </div>

    <!-- Main Editorial Form -->
    <form id="articleCreateForm" action="{{ route('admin.articles.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Main Content Writing Canvas (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Headline, Subtitle & Excerpt Card -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                    
                    <!-- Headline -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                                Headline <span class="text-red-500">*</span>
                            </label>
                            <span id="headlineCounter" class="text-[11px] font-mono text-slate-400">
                                0 / 70 chars (recommended)
                            </span>
                        </div>
                        <input type="text" 
                               id="inputTitle"
                               name="title" 
                               value="{{ old('title') }}" 
                               required 
                               placeholder="Enter compelling business/tech headline..."
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-lg sm:text-xl font-bold font-serif text-slate-900 placeholder-slate-400 focus:outline-none focus:border-red-500 focus:bg-white transition">
                        @error('title')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug Preview & Customization -->
                    <div class="pt-1">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-slate-400 font-semibold">Slug:</span>
                            <span class="text-slate-400 font-mono">/article/</span>
                            <input type="text" 
                                   id="inputSlug"
                                   name="slug" 
                                   value="{{ old('slug') }}"
                                   placeholder="auto-generated-from-headline"
                                   class="flex-1 px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg font-mono text-xs text-slate-800 focus:outline-none focus:border-red-500 focus:bg-white transition">
                            <button type="button" 
                                    onclick="generateSlugFromTitle()" 
                                    class="text-xs text-blue-600 hover:text-blue-800 font-semibold px-2 py-1 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                Generate
                            </button>
                        </div>
                    </div>

                    <!-- Subtitle / Dek -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                                Subtitle / Dek
                            </label>
                            <span id="subtitleCounter" class="text-[11px] font-mono text-slate-400">
                                0 / 160 chars
                            </span>
                        </div>
                        <input type="text" 
                               id="inputSubtitle"
                               name="subtitle" 
                               value="{{ old('subtitle') }}" 
                               placeholder="Secondary headline establishing context and journalistic angle..."
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:border-red-500 focus:bg-white transition">
                    </div>

                    <!-- Lead Excerpt -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                                Short Excerpt / Lead Summary
                            </label>
                            <span id="excerptCounter" class="text-[11px] font-mono text-slate-400">
                                0 / 250 chars
                            </span>
                        </div>
                        <textarea id="inputExcerpt"
                                  name="excerpt" 
                                  rows="3" 
                                  placeholder="Concise summary used for homepage lead grids, social snippets, and RSS feeds..."
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-red-500 focus:bg-white transition">{{ old('excerpt') }}</textarea>
                    </div>

                </div>

                <!-- Rich Text Article Body Canvas -->
                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
                    
                    <!-- Editor Header & Toolbar -->
                    <div class="border-b border-slate-200 bg-slate-50/80 px-4 py-3 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-800">
                                Article Body <span class="text-red-500">*</span>
                            </label>
                            <span class="text-xs text-slate-400 hidden sm:inline">&mdash; Rich Text & HTML</span>
                        </div>

                        <!-- Source Toggle & Word Counters -->
                        <div class="flex items-center gap-3">
                            <div class="text-[11px] text-slate-500 flex items-center gap-2">
                                <span>Words: <strong class="text-slate-800" id="bodyWordCount">0</strong></span>
                                <span class="text-slate-300">|</span>
                                <span>~<strong class="text-slate-800" id="bodyReadingTime">1</strong> min read</span>
                            </div>

                            <button type="button" 
                                    id="btnSourceToggle"
                                    onclick="toggleSourceMode()" 
                                    class="px-2.5 py-1 text-xs font-mono font-semibold rounded-lg transition inline-flex items-center gap-1 bg-white text-slate-700 border border-slate-200 hover:bg-slate-100">
                                <span>&lt;&gt;</span>
                                <span id="sourceToggleText">HTML Source</span>
                            </button>
                        </div>
                    </div>

                    <!-- WYSIWYG Action Toolbar -->
                    <div id="wysiwygToolbar" class="bg-white border-b border-slate-200 p-2 flex flex-wrap items-center gap-1 text-slate-700 text-xs">
                        
                        <!-- Text Styles -->
                        <button type="button" onclick="formatDoc('bold')" title="Bold" class="p-1.5 hover:bg-slate-100 rounded font-bold w-7 h-7 flex items-center justify-center">B</button>
                        <button type="button" onclick="formatDoc('italic')" title="Italic" class="p-1.5 hover:bg-slate-100 rounded italic w-7 h-7 flex items-center justify-center">I</button>
                        <button type="button" onclick="formatDoc('underline')" title="Underline" class="p-1.5 hover:bg-slate-100 rounded underline w-7 h-7 flex items-center justify-center">U</button>
                        <button type="button" onclick="formatDoc('strikeThrough')" title="Strikethrough" class="p-1.5 hover:bg-slate-100 rounded line-through w-7 h-7 flex items-center justify-center">S</button>
                        
                        <div class="h-4 w-px bg-slate-200 mx-1"></div>

                        <!-- Headings -->
                        <button type="button" onclick="formatBlock('h2')" title="Heading 2" class="px-2 py-1 hover:bg-slate-100 rounded font-bold text-xs">H2</button>
                        <button type="button" onclick="formatBlock('h3')" title="Heading 3" class="px-2 py-1 hover:bg-slate-100 rounded font-semibold text-xs">H3</button>
                        <button type="button" onclick="formatBlock('p')" title="Paragraph" class="px-2 py-1 hover:bg-slate-100 rounded text-xs">P</button>

                        <div class="h-4 w-px bg-slate-200 mx-1"></div>

                        <!-- Quotes & Lists -->
                        <button type="button" onclick="formatBlock('blockquote')" title="Pull Quote" class="p-1.5 hover:bg-slate-100 rounded w-7 h-7 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        </button>
                        <button type="button" onclick="formatDoc('insertUnorderedList')" title="Bullet List" class="p-1.5 hover:bg-slate-100 rounded w-7 h-7 flex items-center justify-center">&bull; list</button>
                        <button type="button" onclick="formatDoc('insertOrderedList')" title="Numbered List" class="p-1.5 hover:bg-slate-100 rounded w-7 h-7 flex items-center justify-center">1. list</button>
                        <button type="button" onclick="formatDoc('insertHorizontalRule')" title="Divider" class="p-1.5 hover:bg-slate-100 rounded w-7 h-7 flex items-center justify-center">&mdash;</button>

                        <div class="h-4 w-px bg-slate-200 mx-1"></div>

                        <!-- Link & Media -->
                        <button type="button" onclick="insertLink()" title="Insert Link" class="px-2 py-1 hover:bg-slate-100 rounded text-xs inline-flex items-center gap-1 font-semibold text-blue-600">
                            <span>Link</span>
                        </button>
                        <button type="button" onclick="openMediaPicker('content')" title="Insert Image from Media Library" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 rounded text-xs inline-flex items-center gap-1 font-semibold text-slate-800">
                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Insert Image</span>
                        </button>
                        <button type="button" onclick="insertTable()" title="Insert 2x2 Data Table" class="px-2 py-1 hover:bg-slate-100 rounded text-xs text-slate-700">
                            Table
                        </button>

                        <div class="h-4 w-px bg-slate-200 mx-1"></div>

                        <!-- Clear Formatting & Undo -->
                        <button type="button" onclick="formatDoc('removeFormat')" title="Remove Formatting" class="p-1.5 hover:bg-slate-100 rounded text-slate-400 hover:text-slate-700 w-7 h-7 flex items-center justify-center">Tx</button>
                        <button type="button" onclick="formatDoc('undo')" title="Undo" class="p-1.5 hover:bg-slate-100 rounded text-slate-400 hover:text-slate-700 w-7 h-7 flex items-center justify-center">&larr;</button>
                        <button type="button" onclick="formatDoc('redo')" title="Redo" class="p-1.5 hover:bg-slate-100 rounded text-slate-400 hover:text-slate-700 w-7 h-7 flex items-center justify-center">&rarr;</button>

                    </div>

                    <!-- WYSIWYG Content Editable Canvas -->
                    <div id="wysiwygCanvas" 
                         contenteditable="true"
                         class="w-full min-h-[480px] p-6 focus:outline-none prose prose-slate max-w-none font-serif text-base sm:text-lg leading-relaxed text-slate-900 bg-white"
                         style="min-height: 500px;">{!! old('content') !!}</div>

                    <!-- Raw HTML Source Textarea -->
                    <textarea id="rawContentArea"
                              name="content" 
                              rows="22" 
                              required 
                              placeholder="Write story content here..."
                              class="hidden w-full p-4 font-mono text-xs sm:text-sm leading-relaxed text-slate-100 bg-slate-900 focus:outline-none border-0"
                              style="min-height: 500px;">{{ old('content') }}</textarea>

                </div>

                <!-- Search Engine Optimization (SEO) & Social Card -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span>Search Engine Optimization (SEO) & Social Graph</span>
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Control search engine discovery, meta tags, and social cards.</p>
                        </div>
                        <button type="button" 
                                onclick="toggleSerpPreview()" 
                                class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                            <span id="serpToggleLabel">Hide Preview</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Meta Title -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-semibold text-slate-700">SEO Custom Title</label>
                                <span id="metaTitleCounter" class="text-[11px] font-mono text-slate-400">0 / 60 chars</span>
                            </div>
                            <input type="text" 
                                   id="inputMetaTitle"
                                   name="meta_title" 
                                   value="{{ old('meta_title') }}"
                                   placeholder="Leave blank to fallback to Article Headline"
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                        </div>

                        <!-- Canonical URL -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Canonical URL</label>
                            <input type="url" 
                                   name="canonical_url" 
                                   value="{{ old('canonical_url') }}"
                                   placeholder="Auto-defaults to published article URL"
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                        </div>

                        <!-- Meta Description -->
                        <div class="md:col-span-2">
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-semibold text-slate-700">Meta Description</label>
                                <span id="metaDescCounter" class="text-[11px] font-mono text-slate-400">0 / 160 chars</span>
                            </div>
                            <textarea id="inputMetaDescription"
                                      name="meta_description" 
                                      rows="2" 
                                      placeholder="Leave blank to fallback to Lead Excerpt"
                                      class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">{{ old('meta_description') }}</textarea>
                        </div>

                        <!-- Robots Directives -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Robots Indexing</label>
                            <select name="robots" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-slate-400">
                                <option value="" selected>Default (index, follow)</option>
                                <option value="index, follow">index, follow (Standard Public)</option>
                                <option value="noindex, follow">noindex, follow (Hide from Search Engines)</option>
                                <option value="noindex, nofollow">noindex, nofollow (Complete Disallow)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Live Google SERP Preview Card -->
                    <div id="serpPreviewContainer" class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Google Search Result Preview</div>
                        <div class="bg-white p-3.5 rounded-lg border border-slate-200/80 shadow-2xs space-y-1">
                            <div class="flex items-center gap-1.5 text-xs text-slate-600">
                                <span class="w-4 h-4 rounded-full bg-red-600 text-white font-bold flex items-center justify-center text-[10px]">A</span>
                                <span class="text-slate-800 font-medium">AQ NEWSWIRE</span>
                                <span class="text-slate-400">&rsaquo;</span>
                                <span class="text-slate-500 font-mono text-[11px]">article / <span id="serpPreviewSlug">new-story</span></span>
                            </div>
                            <div id="serpPreviewTitle" class="text-blue-700 hover:underline font-medium text-base line-clamp-1 cursor-pointer">
                                New Story Headline — AQ NEWSWIRE
                            </div>
                            <div id="serpPreviewDesc" class="text-xs text-slate-600 line-clamp-2">
                                Read full investigative report and market insights on AQ NEWSWIRE International Business & Leadership.
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Inspector Right Sidebar (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Publishing Controls Card -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Publishing Controls</h3>
                        <span class="text-[11px] text-slate-400 font-semibold">New Entry</span>
                    </div>

                    <!-- Workflow Status -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Editorial Workflow Status *</label>
                        <select id="selectStatus"
                                name="status" 
                                onchange="onStatusSelectChange()"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-400">
                            <option value="draft" selected>Draft (Private Workspace)</option>
                            <option value="submitted">Submitted for Editorial Review</option>
                            @if(Auth::user()->isEditor() || Auth::user()->isAdmin())
                                <option value="published">Published (Live Online)</option>
                                <option value="scheduled">Scheduled for Future Publication</option>
                            @endif
                        </select>
                    </div>

                    <!-- Author Selection (Admins / Editors only) -->
                    @if(Auth::user()->isEditor() || Auth::user()->isAdmin())
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Byline Author *</label>
                            <select name="user_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-400">
                                @foreach($authors as $auth)
                                    <option value="{{ $auth->id }}" {{ (Auth::id() == $auth->id) ? 'selected' : '' }}>
                                        {{ $auth->name }} ({{ ucfirst($auth->role) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Primary Channel / Category -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Primary News Channel *</label>
                        <select name="category_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-400">
                            <option value="">Select Channel...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tags & Dynamic Tag Creator -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tags & Topics</label>
                        <div class="space-y-2">
                            <select name="tags[]" multiple size="3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-slate-400">
                                @foreach($tags as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                            <input type="text" 
                                   name="new_tags" 
                                   placeholder="Add new tags (comma-separated)..."
                                   class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-400">
                        </div>
                    </div>

                    <!-- Scheduled Publishing Date/Time -->
                    <div id="scheduledDateContainer" class="hidden p-3 bg-purple-50/70 border border-purple-200 rounded-xl space-y-2">
                        <label class="block text-xs font-bold text-purple-900">Scheduled Release Date & Time</label>
                        <input type="datetime-local" 
                               name="scheduled_at" 
                               class="w-full px-3 py-1.5 bg-white border border-purple-200 rounded-lg text-xs text-purple-900 focus:outline-none focus:border-purple-500">
                        <p class="text-[11px] text-purple-700">Timezone: <strong>{{ $siteTimezone }}</strong></p>
                    </div>

                    <!-- Save / Update Action Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider py-3 rounded-xl shadow-xs transition">
                            Save Story
                        </button>
                    </div>
                </div>

                <!-- Featured Cover Media Card -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Featured Cover Media</h3>
                        <button type="button" 
                                onclick="openMediaPicker('cover')" 
                                class="text-xs font-semibold text-red-600 hover:text-red-800 inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Media Library</span>
                        </button>
                    </div>

                    <!-- Image URL input -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Image URL or Storage Path</label>
                        <input type="text" 
                               id="inputFeaturedImage"
                               name="featured_image" 
                               value="{{ old('featured_image') }}"
                               oninput="updateCoverImagePreview()"
                               placeholder="https://... or media/photo.webp"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-slate-400">
                    </div>

                    <!-- Live Image Preview Box -->
                    <div class="w-full aspect-video rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center relative" id="coverImagePreviewContainer">
                        <div id="coverPlaceholder" class="text-center text-slate-400 p-4">
                            <svg class="w-8 h-8 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs">No cover image selected</span>
                        </div>
                        <img id="coverPreviewImg" src="" alt="Cover Preview" class="hidden w-full h-full object-cover">
                    </div>

                    <!-- Photo Credit & Caption -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Photo Credit / Caption</label>
                        <input type="text" 
                               id="inputFeaturedCaption"
                               name="featured_image_caption" 
                               value="{{ old('featured_image_caption') }}"
                               placeholder="e.g. Photo by AP Images / Getty"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-slate-400">
                    </div>

                    <!-- Alt Text for Accessibility & SEO -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alt Text (Accessibility & SEO)</label>
                        <input type="text" 
                               id="inputFeaturedAlt"
                               name="featured_image_alt" 
                               value="{{ old('featured_image_alt') }}"
                               placeholder="Descriptive text for screen readers and SEO"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-slate-400">
                    </div>
                </div>

                <!-- Editorial Visibility Flags Card -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-2.5">Editorial Visibility Flags</h3>

                    <label class="flex items-center gap-2.5 text-xs text-slate-800 cursor-pointer p-1.5 hover:bg-slate-50 rounded-lg transition">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 rounded text-red-600 border-slate-300">
                        <span><strong>Featured Cover Story</strong> (Hero carousel)</span>
                    </label>

                    <label class="flex items-center gap-2.5 text-xs text-slate-800 cursor-pointer p-1.5 hover:bg-slate-50 rounded-lg transition">
                        <input type="checkbox" name="is_breaking" value="1" {{ old('is_breaking') ? 'checked' : '' }} class="w-4 h-4 rounded text-red-600 border-slate-300">
                        <span><strong>Breaking News Alert</strong> (Header banner)</span>
                    </label>

                    <label class="flex items-center gap-2.5 text-xs text-slate-800 cursor-pointer p-1.5 hover:bg-slate-50 rounded-lg transition">
                        <input type="checkbox" name="is_trending" value="1" {{ old('is_trending') ? 'checked' : '' }} class="w-4 h-4 rounded text-red-600 border-slate-300">
                        <span><strong>Trending Leaderboard</strong> (Top 10 bar)</span>
                    </label>

                    <label class="flex items-center gap-2.5 text-xs text-slate-800 cursor-pointer p-1.5 hover:bg-slate-50 rounded-lg transition">
                        <input type="checkbox" name="is_editors_pick" value="1" {{ old('is_editors_pick') ? 'checked' : '' }} class="w-4 h-4 rounded text-red-600 border-slate-300">
                        <span><strong>Editors' Pick Spotlight</strong> (Curated section)</span>
                    </label>

                    <label class="flex items-center gap-2.5 text-xs text-slate-800 cursor-pointer p-1.5 hover:bg-slate-50 rounded-lg transition">
                        <input type="checkbox" name="is_premium" value="1" {{ old('is_premium') ? 'checked' : '' }} class="w-4 h-4 rounded text-amber-600 border-slate-300">
                        <span><strong>Premium / Subscriber Only</strong> (Member badge)</span>
                    </label>
                </div>

                <!-- Monetization & Sponsorship Card -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-2.5">Monetization & Sponsorship</h3>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Brand Sponsor Name</label>
                        <input type="text" 
                               name="sponsored_by" 
                               value="{{ old('sponsored_by') }}" 
                               placeholder="e.g. Goldman Sachs, Rolex"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Sponsor Destination URL</label>
                        <input type="url" 
                               name="sponsor_url" 
                               value="{{ old('sponsor_url') }}" 
                               placeholder="https://sponsor.com"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Affiliate Disclosure</label>
                        <textarea name="affiliate_disclosure" 
                                  rows="2" 
                                  placeholder="Disclosure for editorial independence or affiliate links..."
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-slate-400">{{ old('affiliate_disclosure') }}</textarea>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <!-- Media Library Modal (Hidden by Default via standard 'hidden' class) -->
    <div id="mediaPickerModal" 
         class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-bold text-slate-900">Media Library & Asset Browser</h3>
                    <span class="text-xs text-slate-400">Select or upload media for your story</span>
                </div>
                <button type="button" onclick="closeMediaPicker()" class="text-slate-400 hover:text-slate-700 text-lg font-bold">&times;</button>
            </div>

            <!-- Upload & Filter Bar -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <input type="text" 
                       id="mediaSearchInput"
                       oninput="onMediaSearchInput()" 
                       placeholder="Search media..." 
                       class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400 w-full sm:w-60">

                <label class="px-3.5 py-1.5 bg-slate-900 hover:bg-black text-white text-xs font-semibold rounded-lg cursor-pointer transition inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Upload New File</span>
                    <input type="file" id="mediaFileInput" onchange="uploadSelectedMediaFile(event)" accept="image/*,video/*,audio/*" class="hidden">
                </label>
            </div>

            <!-- Media Grid Container -->
            <div class="flex-1 overflow-y-auto min-h-[300px] border border-slate-100 rounded-xl p-3 bg-slate-50">
                <div id="mediaLoadingIndicator" class="hidden flex items-center justify-center h-48 text-slate-400 text-xs">
                    <span>Loading media assets...</span>
                </div>

                <div id="mediaEmptyNotice" class="hidden flex items-center justify-center h-48 text-slate-400 text-xs">
                    <span>No media assets found. Upload one above!</span>
                </div>

                <div id="mediaGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="pt-2 flex justify-between items-center text-xs text-slate-500">
                <span>Click an image to insert into your story.</span>
                <button type="button" onclick="closeMediaPicker()" class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold transition">Close</button>
            </div>

        </div>
    </div>

</div>

<!-- Vanilla JavaScript Article Create Controller -->
<script>
    const CREATE_CONFIG = {
        wpm: {{ $wpm }},
        mediaPickerUrl: '{{ route('admin.media.picker') }}',
        mediaUploadUrl: '{{ route('admin.media.store') }}',
        csrfToken: '{{ csrf_token() }}'
    };

    let isSourceMode = false;
    let currentMediaTarget = 'cover';
    let mediaSearchDebounce = null;

    document.addEventListener('DOMContentLoaded', function() {
        updateAllCounters();

        const monitoredInputs = ['inputTitle', 'inputSubtitle', 'inputExcerpt', 'inputMetaTitle', 'inputMetaDescription'];
        monitoredInputs.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', handleFieldInput);
        });

        const titleEl = document.getElementById('inputTitle');
        if (titleEl) {
            titleEl.addEventListener('input', function() {
                const slugEl = document.getElementById('inputSlug');
                if (slugEl && !slugEl.value) {
                    generateSlugFromTitle();
                }
            });
        }

        const canvas = document.getElementById('wysiwygCanvas');
        if (canvas) canvas.addEventListener('input', handleCanvasInput);

        const raw = document.getElementById('rawContentArea');
        if (raw) raw.addEventListener('input', handleRawInput);

        // Sync before submit
        const form = document.getElementById('articleCreateForm');
        if (form) {
            form.addEventListener('submit', function() {
                syncToRaw();
            });
        }

        // Close modal on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMediaPicker();
        });

        const modal = document.getElementById('mediaPickerModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeMediaPicker();
            });
        }
    });

    function handleFieldInput() {
        updateAllCounters();
    }

    function handleCanvasInput() {
        syncToRaw();
        updateAllCounters();
    }

    function handleRawInput() {
        syncToCanvas();
        updateAllCounters();
    }

    function syncToRaw() {
        const canvas = document.getElementById('wysiwygCanvas');
        const raw = document.getElementById('rawContentArea');
        if (canvas && raw) raw.value = canvas.innerHTML;
    }

    function syncToCanvas() {
        const canvas = document.getElementById('wysiwygCanvas');
        const raw = document.getElementById('rawContentArea');
        if (canvas && raw) canvas.innerHTML = raw.value;
    }

    function updateAllCounters() {
        const title = (document.getElementById('inputTitle')?.value || '').trim();
        const subtitle = (document.getElementById('inputSubtitle')?.value || '').trim();
        const excerpt = (document.getElementById('inputExcerpt')?.value || '').trim();
        const metaTitle = (document.getElementById('inputMetaTitle')?.value || '').trim();
        const metaDesc = (document.getElementById('inputMetaDescription')?.value || '').trim();
        const raw = document.getElementById('rawContentArea')?.value || '';

        const titleCounter = document.getElementById('headlineCounter');
        if (titleCounter) {
            titleCounter.textContent = `${title.length} / 70 chars (recommended)`;
            titleCounter.className = title.length >= 50 && title.length <= 75 ? 'text-[11px] font-mono text-emerald-600 font-bold' : (title.length > 75 ? 'text-[11px] font-mono text-amber-600' : 'text-[11px] font-mono text-slate-400');
        }

        const subCounter = document.getElementById('subtitleCounter');
        if (subCounter) {
            subCounter.textContent = `${subtitle.length} / 160 chars`;
            subCounter.className = subtitle.length >= 80 && subtitle.length <= 160 ? 'text-[11px] font-mono text-emerald-600 font-bold' : 'text-[11px] font-mono text-slate-400';
        }

        const expCounter = document.getElementById('excerptCounter');
        if (expCounter) {
            expCounter.textContent = `${excerpt.length} / 250 chars`;
            expCounter.className = excerpt.length >= 120 && excerpt.length <= 250 ? 'text-[11px] font-mono text-emerald-600 font-bold' : 'text-[11px] font-mono text-slate-400';
        }

        const mtCounter = document.getElementById('metaTitleCounter');
        if (mtCounter) mtCounter.textContent = `${metaTitle.length} / 60 chars`;

        const mdCounter = document.getElementById('metaDescCounter');
        if (mdCounter) mdCounter.textContent = `${metaDesc.length} / 160 chars`;

        const plainText = raw.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
        const words = plainText ? plainText.split(' ').length : 0;
        const minutes = Math.max(1, Math.ceil(words / (CREATE_CONFIG.wpm || 220)));

        document.getElementById('topWordCount').textContent = words.toLocaleString();
        document.getElementById('topReadingTime').textContent = `${minutes} min`;
        document.getElementById('bodyWordCount').textContent = words.toLocaleString();
        document.getElementById('bodyReadingTime').textContent = minutes;

        const serpTitle = document.getElementById('serpPreviewTitle');
        if (serpTitle) {
            serpTitle.textContent = (metaTitle || title || 'New Story Headline') + ' — AQ NEWSWIRE';
        }
        const serpDesc = document.getElementById('serpPreviewDesc');
        if (serpDesc) {
            serpDesc.textContent = metaDesc || excerpt || 'Read full investigative report and market insights on AQ NEWSWIRE International Business & Leadership.';
        }
        const slug = document.getElementById('inputSlug')?.value || '';
        const serpSlug = document.getElementById('serpPreviewSlug');
        if (serpSlug) serpSlug.textContent = slug || 'new-story';
    }

    function generateSlugFromTitle() {
        const title = document.getElementById('inputTitle')?.value || '';
        const slug = title.toLowerCase().trim()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
        const slugInput = document.getElementById('inputSlug');
        if (slugInput) {
            slugInput.value = slug;
            handleFieldInput();
        }
    }

    function toggleSourceMode() {
        isSourceMode = !isSourceMode;
        const canvas = document.getElementById('wysiwygCanvas');
        const raw = document.getElementById('rawContentArea');
        const toolbar = document.getElementById('wysiwygToolbar');
        const label = document.getElementById('sourceToggleText');
        const btn = document.getElementById('btnSourceToggle');

        if (isSourceMode) {
            syncToRaw();
            canvas.classList.add('hidden');
            toolbar.classList.add('hidden');
            raw.classList.remove('hidden');
            label.textContent = 'Visual Editor';
            btn.className = 'px-2.5 py-1 text-xs font-mono font-semibold rounded-lg transition inline-flex items-center gap-1 bg-slate-900 text-white';
        } else {
            syncToCanvas();
            raw.classList.add('hidden');
            canvas.classList.remove('hidden');
            toolbar.classList.remove('hidden');
            label.textContent = 'HTML Source';
            btn.className = 'px-2.5 py-1 text-xs font-mono font-semibold rounded-lg transition inline-flex items-center gap-1 bg-white text-slate-700 border border-slate-200 hover:bg-slate-100';
        }
    }

    function formatDoc(cmd, val = null) {
        if (isSourceMode) return;
        const canvas = document.getElementById('wysiwygCanvas');
        canvas.focus();
        document.execCommand(cmd, false, val);
        handleCanvasInput();
    }

    function formatBlock(tag) {
        if (isSourceMode) return;
        const canvas = document.getElementById('wysiwygCanvas');
        canvas.focus();
        document.execCommand('formatBlock', false, '<' + tag + '>');
        handleCanvasInput();
    }

    function insertLink() {
        if (isSourceMode) return;
        const url = prompt('Enter the link destination URL:');
        if (url) formatDoc('createLink', url);
    }

    function insertTable() {
        if (isSourceMode) return;
        const html = `
            <table class="border-collapse border border-slate-300 w-full my-4 text-sm">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="border border-slate-300 p-2 font-bold text-left">Header 1</th>
                        <th class="border border-slate-300 p-2 font-bold text-left">Header 2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-slate-300 p-2">Item 1</td>
                        <td class="border border-slate-300 p-2">Details</td>
                    </tr>
                </tbody>
            </table><p></p>
        `;
        formatDoc('insertHTML', html);
    }

    function toggleSerpPreview() {
        const preview = document.getElementById('serpPreviewContainer');
        const label = document.getElementById('serpToggleLabel');
        if (preview && label) {
            const isHidden = preview.classList.contains('hidden');
            if (isHidden) {
                preview.classList.remove('hidden');
                label.textContent = 'Hide Preview';
            } else {
                preview.classList.add('hidden');
                label.textContent = 'Show Live Snippet Preview';
            }
        }
    }

    function onStatusSelectChange() {
        const sel = document.getElementById('selectStatus');
        const val = sel ? sel.value : 'draft';
        const sched = document.getElementById('scheduledDateContainer');
        if (sched) {
            if (val === 'scheduled') sched.classList.remove('hidden');
            else sched.classList.add('hidden');
        }
    }

    function updateCoverImagePreview() {
        const url = (document.getElementById('inputFeaturedImage')?.value || '').trim();
        const placeholder = document.getElementById('coverPlaceholder');
        const img = document.getElementById('coverPreviewImg');

        if (url) {
            if (placeholder) placeholder.classList.add('hidden');
            if (img) {
                img.src = url.startsWith('http') ? url : ('/storage/' + url);
                img.classList.remove('hidden');
            }
        } else {
            if (placeholder) placeholder.classList.remove('hidden');
            if (img) img.classList.add('hidden');
        }
    }

    function openMediaPicker(target) {
        currentMediaTarget = target;
        const modal = document.getElementById('mediaPickerModal');
        if (modal) {
            modal.classList.remove('hidden');
            fetchMediaList();
        }
    }

    function closeMediaPicker() {
        const modal = document.getElementById('mediaPickerModal');
        if (modal) modal.classList.add('hidden');
    }

    function onMediaSearchInput() {
        clearTimeout(mediaSearchDebounce);
        mediaSearchDebounce = setTimeout(() => {
            fetchMediaList();
        }, 300);
    }

    function fetchMediaList() {
        const search = document.getElementById('mediaSearchInput')?.value || '';
        const grid = document.getElementById('mediaGrid');
        const loading = document.getElementById('mediaLoadingIndicator');
        const empty = document.getElementById('mediaEmptyNotice');

        if (loading) loading.classList.remove('hidden');
        if (empty) empty.classList.add('hidden');
        if (grid) grid.innerHTML = '';

        const url = new URL(CREATE_CONFIG.mediaPickerUrl, window.location.origin);
        if (search) url.searchParams.set('search', search);

        fetch(url.toString(), {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(res => {
            if (loading) loading.classList.add('hidden');
            const items = res.data || [];
            if (items.length === 0) {
                if (empty) empty.classList.remove('hidden');
                return;
            }

            let html = '';
            items.forEach(asset => {
                const isImg = asset.file_type === 'image';
                const mediaThumb = isImg 
                    ? `<img src="${asset.url}" alt="${asset.title}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">`
                    : `<div class="flex items-center justify-center h-full text-slate-400 font-bold uppercase text-xs">${asset.file_type}</div>`;

                html += `
                    <div onclick="selectMediaAsset('${asset.url}', '${escapeHtml(asset.title || '')}', '${escapeHtml(asset.caption || '')}', '${escapeHtml(asset.alt_text || '')}')" 
                         class="group cursor-pointer bg-white border border-slate-200 hover:border-red-500 rounded-xl overflow-hidden shadow-2xs hover:shadow-md transition">
                        <div class="aspect-video bg-slate-100 overflow-hidden relative">
                            ${mediaThumb}
                        </div>
                        <div class="p-2 text-[11px]">
                            <div class="font-semibold text-slate-800 truncate">${escapeHtml(asset.title || asset.filename)}</div>
                            <div class="text-slate-400 truncate">${escapeHtml(asset.dimensions || asset.file_type)}</div>
                        </div>
                    </div>
                `;
            });
            if (grid) grid.innerHTML = html;
        })
        .catch(() => {
            if (loading) loading.classList.add('hidden');
            if (empty) empty.classList.remove('hidden');
        });
    }

    function selectMediaAsset(url, title, caption, alt) {
        if (currentMediaTarget === 'cover') {
            const inputImg = document.getElementById('inputFeaturedImage');
            const inputCaption = document.getElementById('inputFeaturedCaption');
            const inputAlt = document.getElementById('inputFeaturedAlt');

            if (inputImg) inputImg.value = url;
            if (inputCaption && caption) inputCaption.value = caption;
            if (inputAlt && alt) inputAlt.value = alt;

            updateCoverImagePreview();
        } else if (currentMediaTarget === 'content') {
            const imgHtml = `<figure class="my-6"><img src="${url}" alt="${alt || title || ''}" class="w-full h-auto rounded-xl shadow-xs" /><figcaption class="text-xs text-slate-500 italic mt-1.5 text-right">${caption || ''}</figcaption></figure><p></p>`;
            formatDoc('insertHTML', imgHtml);
        }
        closeMediaPicker();
    }

    function uploadSelectedMediaFile(e) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('title', file.name);

        fetch(CREATE_CONFIG.mediaUploadUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CREATE_CONFIG.csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.asset) {
                fetchMediaList();
                selectMediaAsset(data.url, data.asset.title, data.asset.caption, data.asset.alt_text);
            }
        });
    }

    function escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
</script>
@endsection
