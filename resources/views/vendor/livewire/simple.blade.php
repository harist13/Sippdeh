<div class="flex items-center justify-end gap-10 py-2">
    <!-- Items per page dropdown -->
    <div class="flex items-center">
        <label for="perPage" class="text-sm text-gray-700 mr-2">Jumlah data perhalaman:</label>
        <select wire:model.live="perPage" id="perPage" wire:model="perPage" class="bg-gray-300 border-gray-300 rounded p-1 text-sm">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>

    <!-- Pagination summary and controls -->
    <div class="flex items-center gap-10">
        <span class="text-sm text-gray-700 dark:text-gray-400 mr-4">
            {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} dari {{ $paginator->total() }}
        </span>

        <!-- Pagination buttons -->
        <div class="flex items-center gap-3 space-x-1">
            <!-- First Page -->
            @if (!$paginator->onFirstPage())
                <a href="javascript:void(0);" wire:click="gotoPage(1)" class="text-gray-500 hover:text-blue-500" aria-label="First Page">
                    &#124;&lt;
                </a>
            @else
                <span class="text-gray-300">&#124;&lt;</span>
            @endif

            <!-- Previous Page -->
            @if ($paginator->onFirstPage())
                <span class="text-gray-300">&lt;</span>
            @else
                <a href="javascript:void(0);" wire:click="previousPage" class="text-gray-500 hover:text-blue-500" aria-label="Previous Page" id="prevPage">
                    &lt;
                </a>
            @endif

            <!-- Next Page -->
            @if ($paginator->hasMorePages())
                <a href="javascript:void(0);" wire:click="nextPage" class="text-gray-500 hover:text-blue-500" aria-label="Next Page" id="nextPage">
                    &gt;
                </a>
            @else
                <span class="text-gray-300">&gt;</span>
            @endif

            <!-- Last Page -->
            @if ($paginator->hasMorePages())
                <a href="javascript:void(0);" wire:click="gotoPage({{ $paginator->lastPage() }})" class="text-gray-500 hover:text-blue-500" aria-label="Last Page">
                    &gt;&#124;
                </a>
            @else
                <span class="text-gray-300">&gt;&#124;</span>
            @endif
        </div>
    </div>

    <span class="tooltip relative cursor-pointer text-sm text-gray-500 mt-1">
        <i class="fas fa-question-circle"></i>
        <span class="absolute top-full -right-full mt-1 w-max px-2 py-1 bg-gray-800 text-white rounded shadow-md text-xs hidden group-hover:block">
            Untuk berganti ke halaman lain, bisa juga dengan menekan "Shift + <" atau "Shift + >"
        </span>
    </span>
</div>