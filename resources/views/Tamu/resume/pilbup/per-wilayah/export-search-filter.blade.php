<div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center order-1 lg:order-2">
   

    {{-- Search Input --}}
    <div class="flex items-center rounded-lg bg-[#ECEFF5] px-4 py-2">
        {{-- Loading Icon --}}
        <svg wire:loading wire:target="keyword" class="animate-spin -ml-1 mr-2 h-4 w-4 text-[#3560A0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>

        {{-- Search Icon --}}
        <svg wire:loading.remove wire:target="keyword" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
        </svg>

        {{-- Input --}}
        <input 
            wire:model.live.debounce.500ms="keyword"
            type="search" 
            placeholder="Cari" 
            name="cari" 
            id="search"
            class="ml-2 bg-transparent focus:outline-none text-gray-600"
        >
    </div>

    {{-- Filter Button --}}
    <button 
        class="flex items-center justify-center bg-[#ECEFF5] text-white text-sm font-medium px-4 py-2 rounded-lg sm:w-auto w-full"
        id="openFilterPilbupPerWilayah"
    >
        <img src="{{ asset('assets/icon/filter-lines.png') }}" alt="Filter" class="w-4 h-4 mr-2">
        <span class="text-[#344054]">Filter</span>
    </button>
</div>

@push('scripts')
    <script>
        function showFilterPilbupPerWilayahModal() {
            const filterPilbupPerWilayahModal = document.getElementById('filterPilbupPerWilayahModal');
            filterPilbupPerWilayahModal.classList.remove('hidden');
        }

        function closeFilterPilbupPerWilayahModal() {
            const filterPilbupPerWilayahModal = document.getElementById('filterPilbupPerWilayahModal');
            filterPilbupPerWilayahModal.classList.add('hidden');
        }
        
        function initializeFilterResumePilbupPerWilayah() {
            document.getElementById('openFilterPilbupPerWilayah').addEventListener('click', showFilterPilbupPerWilayahModal);
            document.getElementById('cancelFilterPilbupPerWilayah').addEventListener('click', closeFilterPilbupPerWilayahModal);

            document.addEventListener('keyup', function(event) {
                if (event.key === "Escape") {
                    closeFilterPilbupPerWilayahModal();
                }
            });

            document.addEventListener('click', function(event) {
                if (event.target == filterPilbupPerWilayahModal) {
                    closeFilterPilbupPerWilayahModal();
                }
            });
        }

        initializeFilterResumePilbupPerWilayah();
    </script>
@endpush