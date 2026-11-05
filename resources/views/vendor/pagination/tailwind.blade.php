@if ($paginator->total() > 0)
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4 py-3 px-2 font-cartoon">
        <div>
            <p class="text-xs text-slate-600 font-medium">
                Menampilkan
                @if ($paginator->firstItem())
                    <span class="font-bold text-[#003366]">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-bold text-[#003366]">{{ $paginator->lastItem() }}</span>
                @else
                    <span class="font-bold text-[#003366]">{{ $paginator->count() }}</span>
                @endif
                dari
                <span class="font-extrabold text-[#003366]">{{ $paginator->total() }}</span>
                data
            </p>
        </div>

        @if ($paginator->hasPages())
            <div>
                <span class="inline-flex items-center gap-1.5">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="inline-flex items-center px-2.5 py-1.5 text-xs font-bold text-slate-400 bg-slate-100 border-2 border-slate-200 cursor-not-allowed rounded-xl">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-2.5 py-1.5 text-xs font-bold text-[#003366] bg-white border-2 border-[#003366] rounded-xl hover:bg-amber-100 transition shadow-[2px_2px_0px_#003366]" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-slate-400 cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-white bg-[#003366] border-2 border-[#002244] rounded-xl shadow-[2px_2px_0px_#F5B800]">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-slate-800 bg-white border-2 border-slate-300 hover:border-[#003366] hover:bg-slate-50 rounded-xl transition shadow-xs" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-2.5 py-1.5 text-xs font-bold text-[#003366] bg-white border-2 border-[#003366] rounded-xl hover:bg-amber-100 transition shadow-[2px_2px_0px_#003366]" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="inline-flex items-center px-2.5 py-1.5 text-xs font-bold text-slate-400 bg-slate-100 border-2 border-slate-200 cursor-not-allowed rounded-xl">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @endif
                </span>
            </div>
        @endif
    </nav>
@endif
