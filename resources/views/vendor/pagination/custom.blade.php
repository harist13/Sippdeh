@if ($paginator->total() > min([10, 20, 50, 100]))
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex justify-end">
        <div class="flex items-center space-x-6">
            {{-- Items Per Page Dropdown --}}
            @if ($paginator->total() > 10)
            <div class="flex items-center">
                <select id="itemsPerPage" name="itemsPerPage" class="border border-gray-300 rounded-md p-1 text-sm">
                    @foreach ([10, 20, 50, 100] as $perPage)
                        @if ($paginator->total() >= $perPage)
                            <option value="{{ $perPage }}" {{ request('itemsPerPage') == $perPage ? 'selected' : '' }}>
                                {{ $perPage }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Pagination Summary --}}
            <div class="text-sm text-gray-700">
                {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} of {{ $paginator->total() }}
            </div>

            {{-- Pagination Controls --}}
            @if ($paginator->total() > request('itemsPerPage', 10))
            <div class="flex items-center space-x-1">
                {{-- First Page Link --}}
                @if (!$paginator->onFirstPage())
                    <a href="{{ $paginator->url(1) }}" class="p-1 text-gray-500 hover:text-gray-700">|&lt;</a>
                @else
                    <span class="p-1 text-gray-300">|&lt;</span>
                @endif

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="p-1 text-gray-300">&lt;</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="p-1 text-gray-500 hover:text-gray-700">&lt;</a>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="p-1 text-gray-500 hover:text-gray-700">&gt;</a>
                @else
                    <span class="p-1 text-gray-300">&gt;</span>
                @endif

                {{-- Last Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->url($paginator->lastPage()) }}" class="p-1 text-gray-500 hover:text-gray-700">&gt;|</a>
                @else
                    <span class="p-1 text-gray-300">&gt;|</span>
                @endif
            </div>
            @endif
        </div>
    </nav>
    <script>
        document.getElementById('itemsPerPage')?.addEventListener('change', function () {
            const url = new URL(window.location.href);
            url.searchParams.set('itemsPerPage', this.value);
            window.location.href = url;
        });
    </script>
@endif