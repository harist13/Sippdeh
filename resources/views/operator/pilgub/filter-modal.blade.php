@push('styles')
    <style>
        /* Add blue border to span when checkbox is checked */
        input[type="checkbox"]:checked + span {
            border-color: #3b82f6;
        }
    </style>
@endpush

<div id="filterPilgubModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-20 hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex items-center mb-5">
            <i class="fas fa-arrow-left mr-3 select-none cursor-pointer" id="cancelFilterPilgub"></i>
            <h3 class="text-lg font-medium text-gray-900">Filter</h3>
        </div>

        {{-- Kolom --}}
        <label for="pilihKolom" class="mb-3 font-bold mt-5 block">Kolom</label>
        <ul class="flex flex-col gap-2">
            <li class="flex items-center gap-2 w-full">
                <label class="flex items-center gap-3" for="pilihKabupaten">
                    <input type="checkbox" id="pilihKabupaten" value="KABUPATEN" wire:model="includedColumns" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200 cursor-pointer">
                    <span class="cursor-pointer select-none">Kabupaten</span>
                </label>
            </li>
            <li class="flex items-center gap-2 w-full">
                <label class="flex items-center gap-3" for="pilihKecamatan">
                    <input type="checkbox" id="pilihKecamatan" value="KECAMATAN" wire:model="includedColumns" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200 cursor-pointer">
                    <span class="cursor-pointer select-none">Kecamatan</span>
                </label>
            </li>
            <li class="flex items-center gap-2 w-full">
                <label class="flex items-center gap-3" for="pilihKelurahan">
                    <input type="checkbox" id="pilihKelurahan" value="KELURAHAN" wire:model="includedColumns" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200 cursor-pointer">
                    <span class="cursor-pointer select-none">Kelurahan</span>
                </label>
            </li>
            <li class="flex items-center gap-2 w-full">
                <label class="flex items-center gap-3" for="pilihTPS">
                    <input type="checkbox" id="pilihTPS" value="TPS" wire:model="includedColumns" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200 cursor-pointer">
                    <span class="cursor-pointer select-none">TPS</span>
                </label>
            </li>
            <li class="flex items-center gap-2 w-full">
                <label class="flex items-center gap-3" for="pilihCalon">
                    <input type="checkbox" id="pilihCalon" value="CALON" wire:model="includedColumns" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200 cursor-pointer">
                    <span class="cursor-pointer select-none">Calon</span>
                </label>
            </li>
        </ul>
        {{-- <span class="text-red-800">{{ $errors->first('kabupaten_id') }}</span> --}}

        {{-- Tingkat Partisipasi --}}
        <label for="pilihTingkatPartisipasi" class="mb-3 font-bold mt-5 block">Tingkat Partisipasi</label>
        <div class="flex gap-2">
            <label for="hijau" class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" class="hidden" id="hijau" value="HIJAU" wire:model="partisipasi" />
                <span class="bg-green-400 text-white py-2 px-7 rounded text-sm select-none border-2">> 80%</span>
            </label>
            <label for="kuning" class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" class="hidden" id="kuning" value="KUNING" wire:model="partisipasi" />
                <span class="bg-yellow-400 text-white py-2 px-7 rounded text-sm select-none border-2">> 60%</span>
            </label>
            <label for="merah" class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" class="hidden" id="merah" value="MERAH" wire:model="partisipasi" />
                <span class="bg-red-400 text-white py-2 px-7 rounded text-sm select-none border-2">< 60%</span>
            </label>
        </div>
        {{-- <span class="text-red-800">{{ $errors->first('kabupaten_id') }}</span> --}}

        <hr class="h-1 my-3">

        <div class="flex">
            <button type="button" wire:loading.attr="disabled" wire:target="resetFilter" wire:click="resetFilter" class="flex-1 bg-gray-300 disabled:bg-[#d1d5d06c] hover:bg-gray-400 text-black rounded-md px-4 py-2 mr-2">
                Reset
            </button>
            <button type="submit" wire:loading.attr="disabled" wire:target="applyFilter" wire:click="applyFilter" id="applyFilterPilgub" class="flex-1 bg-[#3560A0] disabled:bg-[#0070F06c] hover:bg-blue-700 text-white rounded-md px-4 py-2">
                Terapkan
            </button>
        </div>
    </div>
</div>

@script
    <script>
        console.log('Init filter');
        
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

        function showFilterPilgubModal() {
            const filterPilgubModal = document.getElementById('filterPilgubModal');
            filterPilgubModal.classList.remove('hidden');
        }

        function closeFilterPilgubModal() {
            const filterPilgubModal = document.getElementById('filterPilgubModal');
            filterPilgubModal.classList.add('hidden');
        }

        function onApplyFilter() {
            $wire.$refresh();
        }

        initializeFilter();
    </script>
@endscript