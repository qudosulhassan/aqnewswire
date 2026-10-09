@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4">
        <div class="text-xs text-slate-500">
            <span>Showing</span>
            <span class="font-bold text-[#0A2540]">{{ $paginator->firstItem() }}</span>
            <span>to</span>
            <span class="font-bold text-[#0A2540]">{{ $paginator->lastItem() }}</span>
            <span>of</span>
            <span class="font-bold text-[#0A2540]">{{ $paginator->total() }}</span>
            <span>stories</span>
        </div>

        <div class="flex items-center gap-1.5 text-xs font-semibold">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-400 cursor-not-allowed">
                    &larr; Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#0A2540] hover:bg-slate-50 hover:border-slate-300 transition shadow-xs">
                    &larr; Previous
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-2.5 py-1.5 text-slate-400">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1.5 rounded-xl bg-[#635BFF] text-white font-bold shadow-xs">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#0A2540] hover:bg-slate-50 hover:border-slate-300 transition shadow-xs">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#0A2540] hover:bg-slate-50 hover:border-slate-300 transition shadow-xs">
                    Next &rarr;
                </a>
            @else
                <span class="px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-400 cursor-not-allowed">
                    Next &rarr;
                </span>
            @endif
        </div>
    </nav>
@endif
