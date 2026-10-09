@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4">
        <div class="text-xs text-slate-400">
            <span>Showing</span>
            <span class="font-bold text-slate-200">{{ $paginator->firstItem() }}</span>
            <span>to</span>
            <span class="font-bold text-slate-200">{{ $paginator->lastItem() }}</span>
            <span>of</span>
            <span class="font-bold text-slate-200">{{ $paginator->total() }}</span>
            <span>stories</span>
        </div>

        <div class="flex items-center gap-1.5 text-xs font-semibold">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 rounded-xl bg-slate-900/50 border border-slate-800/50 text-slate-600 cursor-not-allowed">
                    &larr; Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 transition">
                    &larr; Previous
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-2.5 py-1.5 text-slate-500">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1.5 rounded-xl bg-red-600 text-white font-bold shadow-xs">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 transition">
                    Next &rarr;
                </a>
            @else
                <span class="px-3 py-1.5 rounded-xl bg-slate-900/50 border border-slate-800/50 text-slate-600 cursor-not-allowed">
                    Next &rarr;
                </span>
            @endif
        </div>
    </nav>
@endif
