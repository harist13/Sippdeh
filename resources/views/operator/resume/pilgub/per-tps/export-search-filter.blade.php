<div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center order-1 lg:order-2">
    <button 
        wire:click="export"
        wire:loading.attr="disabled" 
        wire:target="export"
        class="flex items-center bg-[#198754] disabled:bg-opacity-60 text-white text-sm font-medium px-4 py-2 rounded-lg sm:w-auto w-full"
    >
        <i wire:loading.remove wire:target="export" class="fas fa-file-excel w-4 h-4 mr-2"></i>
        <svg wire:loading wire:target="export" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Ekspor Excel</span>
    </button>

     <!-- Button Export PDF --> 
    <button type="button"
        wire:click="exportPdf"
        wire:loading.attr="disabled"
        wire:target="exportPdf"
        class="flex items-center justify-center bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg sm:w-auto w-full"
    >
        <div wire:loading.remove wire:target="exportPdf">
            <i class="fas fa-file-pdf w-4 h-4 mr-2"></i>
            Export PDF
        </div>
        <div wire:loading wire:target="exportPdf">
            <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
           
        </div>
    </button>
    
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
        id="openFilterPilgubPerTps"
    >
        <img src="{{ asset('assets/icon/filter-lines.png') }}" alt="Filter" class="w-4 h-4 mr-2">
        <span class="text-[#344054]">Filter</span>
    </button>
</div>

@push('scripts')
    <script>
        function showFilterPilgubPerTpsModal() {
            const filterPilgubPerTpsModal = document.getElementById('filterPilgubPerTpsModal');
            filterPilgubPerTpsModal.classList.remove('hidden');
        }

        function closeFilterPilgubPerTpsModal() {
            const filterPilgubPerTpsModal = document.getElementById('filterPilgubPerTpsModal');
            filterPilgubPerTpsModal.classList.add('hidden');
        }
        
        function initializeFilterResumeSuaraPerTps() {
            document.getElementById('openFilterPilgubPerTps').addEventListener('click', showFilterPilgubPerTpsModal);
            document.getElementById('cancelFilterPilgub').addEventListener('click', closeFilterPilgubPerTpsModal);

            document.addEventListener('keyup', function(event) {
                if (event.key === "Escape") {
                    closeFilterPilgubPerTpsModal();
                }
            });

            document.addEventListener('click', function(event) {
                if (event.target == filterPilgubPerTpsModal) {
                    closeFilterPilgubPerTpsModal();
                }
            });
        }

        initializeFilterResumeSuaraPerTps();
    </script>
@endpush