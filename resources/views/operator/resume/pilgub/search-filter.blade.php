<div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center order-1 lg:order-2">
    <button wire:click="export" class="flex items-center justify-center bg-[#EE3C46] text-white text-sm font-medium px-4 py-2 rounded-lg sm:w-auto w-full">
        <i class="fas fa-file-export w-4 h-4 mr-2"></i>
        <span>Ekspor</span>
    </button>
    <div class="flex items-center rounded-lg bg-[#ECEFF5] px-4 py-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
                d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z"
                clip-rule="evenodd" />
        </svg>
        <input wire:model.live.debounce.250ms="keyword" type="search" placeholder="Cari" name="cari" id="search"
            class="ml-2 bg-transparent focus:outline-none text-gray-600" value="{{ request()->get('cari') }}">
    </div>
    <button
        class="flex items-center justify-center bg-[#ECEFF5] text-white text-sm font-medium px-4 py-2 rounded-lg sm:w-auto w-full"
        id="openFilterPilgub">
        <img src="{{ asset('assets/icon/filter-lines.png') }}" alt="Filter" class="w-4 h-4 mr-2">
        <span class="text-[#344054]">Filter</span>
    </button>
</div>

@push('scripts')
    <script>
        function showFilterPilgubModal() {
            const filterPilgubModal = document.getElementById('filterPilgubModal');
            filterPilgubModal.classList.remove('hidden');
        }

        function closeFilterPilgubModal() {
            const filterPilgubModal = document.getElementById('filterPilgubModal');
            filterPilgubModal.classList.add('hidden');
        }
        
        function initializeFilter() {
            document.getElementById('openFilterPilgub').addEventListener('click', showFilterPilgubModal);
            document.getElementById('cancelFilterPilgub').addEventListener('click', closeFilterPilgubModal);

            document.addEventListener('keyup', function(event) {
                if (event.key === "Escape") {
                    closeFilterPilgubModal();
                }
            });

            document.addEventListener('click', function(event) {
                if (event.target == filterPilgubModal) {
                    closeFilterPilgubModal();
                }
            });
        }

        initializeFilter();
    </script>
@endpush