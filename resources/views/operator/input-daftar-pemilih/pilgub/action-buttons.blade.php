@push('styles')
    <style>
        /* Tooltip styling */
        .tooltip:hover .absolute {
            display: block;
            z-index: 10;
        }
    </style>
@endpush

<div class="flex flex-col space-y-2 sm:space-y-0 sm:flex-row sm:items-center !ml-0 order-2 lg:order-1">
    {{-- Save Changes Button --}}
    <div class="flex items-center mr-7">
        <button
            class="bg-[#58DA91] disabled:bg-[#58da906c] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto"
            id="simpanPerubahanData" wire:loading.attr="disabled" wire:target.except="applyFilter">
            <i class="fas fa-check mr-3"></i>
            Simpan Perubahan Data
        </button>
        <span class="tooltip relative cursor-pointer text-sm text-gray-500 ml-3">
            <i class="fas fa-question-circle"></i>
            <span
                class="absolute top-full left-0 mt-1 w-max px-2 py-1 bg-gray-800 text-white rounded shadow-md text-xs hidden group-hover:block">
                Bisa juga dengan menekan "Ctrl + Enter" atau "Ctrl + S"
            </span>
        </span>
    </div>

    {{-- Cancel Edit Button --}}
    <div class="flex items-center !mr-7">
        <button
            class="bg-[#EE3C46] disabled:bg-[#EE3C406c] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto"
            id="batalUbahData" wire:loading.attr="disabled" wire:target.except="applyFilter">
            <i class="fas fa-times mr-3"></i>
            Batal Ubah Data
        </button>
        <span class="tooltip relative cursor-pointer text-sm text-gray-500 ml-3">
            <i class="fas fa-question-circle"></i>
            <span
                class="absolute top-full left-0 mt-1 w-max px-2 py-1 bg-gray-800 text-white rounded shadow-md text-xs hidden group-hover:block">
                Bisa juga dengan menekan tombol "Esc"
            </span>
        </span>
    </div>

    {{-- Edit Selected Button --}}
    <div class="flex items-center">
        <button
            class="bg-[#0070FF] disabled:bg-[#0070F06c] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto"
            id="ubahDataTercentang" wire:loading.attr="disabled" wire:target.except="applyFilter">
            <i class="fas fa-edit mr-3"></i>
            Ubah Data Tercentang
        </button>
        <span class="tooltip relative cursor-pointer text-sm text-gray-500 ml-3">
            <i class="fas fa-question-circle"></i>
            <span
                class="absolute top-full left-0 mt-1 w-max px-2 py-1 bg-gray-800 text-white rounded shadow-md text-xs hidden group-hover:block">
                Bisa juga dengan menekan tombol "Ctrl + U"
            </span>
        </span>
    </div>
</div>
