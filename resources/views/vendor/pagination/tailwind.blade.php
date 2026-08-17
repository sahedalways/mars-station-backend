@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="pagination-nav">

        {{-- Mobile: Prev / Next --}}
        <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
            @if ($paginator->onFirstPage())
                <span style="display:inline-flex; align-items:center; gap:6px; border-radius:10px; border:1px solid rgba(51,65,85,0.5); background:rgba(15,23,42,0.6); padding:8px 14px; font-size:12px; font-weight:500; color:#64748b; cursor:not-allowed;">
                    <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display:inline-flex; align-items:center; gap:6px; border-radius:10px; border:1px solid rgba(139,92,246,0.25); background:rgba(139,92,246,0.1); padding:8px 14px; font-size:12px; font-weight:500; color:#c4b5fd; text-decoration:none; transition:all 0.2s ease;">
                    <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    Prev
                </a>
            @endif

            <span style="font-size:11px; color:#64748b;">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display:inline-flex; align-items:center; gap:6px; border-radius:10px; border:1px solid rgba(139,92,246,0.25); background:rgba(139,92,246,0.1); padding:8px 14px; font-size:12px; font-weight:500; color:#c4b5fd; text-decoration:none; transition:all 0.2s ease;">
                    Next
                    <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            @else
                <span style="display:inline-flex; align-items:center; gap:6px; border-radius:10px; border:1px solid rgba(51,65,85,0.5); background:rgba(15,23,42,0.6); padding:8px 14px; font-size:12px; font-weight:500; color:#64748b; cursor:not-allowed;">
                    Next
                    <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </span>
            @endif
        </div>

        {{-- Desktop: Showing info + Page links --}}
        <div style="align-items:center; justify-content:space-between; gap:12px;">

            {{-- Showing X to Y of Z --}}
            <div style="display:flex; align-items:center; gap:8px; border-radius:10px; border:1px solid rgba(51,65,85,0.4); background:rgba(15,23,42,0.3); padding:8px 16px;">
                <svg style="width:16px; height:16px; color:rgba(168,85,247,0.7);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" /></svg>
                <p style="margin:0; font-size:12px; color:#94a3b8;">
                    <span style="font-weight:600; color:#c4b5fd;">{{ $paginator->firstItem() }}</span>
                    <span style="color:#475569;"> – </span>
                    <span style="font-weight:600; color:#c4b5fd;">{{ $paginator->lastItem() }}</span>
                    <span style="color:#475569;"> of </span>
                    <span style="font-weight:600; color:#c4b5fd;">{{ $paginator->total() }}</span>
                </p>
            </div>

            {{-- Page buttons --}}
            <div style="display:flex; align-items:center; gap:4px;">

                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(51,65,85,0.4); background:rgba(15,23,42,0.3); color:#475569; cursor:not-allowed;">
                        <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(139,92,246,0.2); background:rgba(139,92,246,0.1); color:#c4b5fd; text-decoration:none; transition:all 0.15s ease;">
                        <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </a>
                @endif

                {{-- Page numbers --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; font-size:12px; font-weight:500; color:#475569;">…</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(139,92,246,0.5); background:#7c3aed; font-size:12px; font-weight:700; color:#fff; box-shadow:0 4px 12px rgba(124,58,237,0.4);">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(51,65,85,0.4); background:rgba(15,23,42,0.3); font-size:12px; font-weight:500; color:#94a3b8; text-decoration:none; transition:all 0.15s ease;">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(139,92,246,0.2); background:rgba(139,92,246,0.1); color:#c4b5fd; text-decoration:none; transition:all 0.15s ease;">
                        <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                @else
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(51,65,85,0.4); background:rgba(15,23,42,0.3); color:#475569; cursor:not-allowed;">
                        <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
