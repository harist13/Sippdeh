<div>
 <div class="p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
                    <!-- Title -->
                    <div class="bg-[#3560a0] text-white py-2 px-4 rounded-lg mb-4 sm:mb-0 w-full sm:w-auto">
                            Jumlah Partisipasi Suara Tertinggi Provinsi Kalimantan Timur
                    </div>

                    <!-- Controls Container -->
                    <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                        <!-- Export Button -->
                        <button wire:click="export" class="flex items-center gap-2 px-4 py-2 bg-[#ee3c46] text-white rounded-lg hover:bg-red-600 transition-colors">
                            <i class="fas fa-download"></i>
                            <span>Export</span>
                        </button>

                        <div class="relative">
                            <input 
                                wire:model.live="search" 
                                type="text" 
                                placeholder="Search" 
                                class="bg-gray-100 rounded-md px-3 py-2 pl-9">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                    
                        <!-- Filter Dropdown -->
                        <div class="relative">
                            <!-- Filter Button -->
                            <button wire:click="$set('showFilterModal', true)"
                                    class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 flex items-center justify-center w-full sm:w-auto">
                                <img src="{{ asset('assets/icon/filter.svg') }}" alt="Filter">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
        <div class="overflow-x-auto shadow-md rounded-lg">
            <table class="w-full border-collapse text-center">
                <thead>
                    <tr class="bg-[#3560A0] text-white">
                        <th class="px-4 py-3 border-r border-white whitespace-nowrap">NO</th>
                        <th class="px-4 py-3 border-r border-white whitespace-nowrap">KAB/KOTA</th>
                        <th class="px-4 py-3 border-r border-white whitespace-nowrap">DPT</th>
                        <th class="px-4 py-3 border-r border-white whitespace-nowrap">SUARA SAH</th>
                        <th class="px-4 py-3 border-r border-white whitespace-nowrap">SUARA TIDAK SAH</th>
                        <th class="px-4 py-3 border-r border-white whitespace-nowrap">ABSTAIN</th>
                        <th class="px-4 py-3 border-r border-white whitespace-nowrap">SUARA MASUK</th>
                        <th class="px-4 py-3 whitespace-nowrap">PARTISIPASI</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-50">
                    @foreach($kabupatenData as $index => $data)
                    <tr class="border-b hover:bg-gray-100 transition-colors">
                        <td class="px-4 py-3 border-r">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 border-r text-left">{{ $data->kabupaten_nama }}</td>
                        <td class="px-4 py-3 border-r">{{ number_format($data->total_dpt) }}</td>
                        <td class="px-4 py-3 border-r">{{ number_format($data->total_suara_sah) }}</td>
                        <td class="px-4 py-3 border-r">{{ number_format($data->total_suara_tidak_sah) }}</td>
                        <td class="px-4 py-3 border-r">{{ number_format($data->total_abstain) }}</td>
                        <td class="px-4 py-3 border-r">{{ number_format($data->total_suara_masuk) }}</td>
                        <td class="px-4 py-3">
                            <span class="@if($data->partisipasi >= 70) bg-green-500 @elseif($data->partisipasi >= 50) bg-yellow-500 @else bg-red-500 @endif text-white px-2 py-1 rounded">
                                {{ $data->partisipasi }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Filter Modal -->
<div x-show="$wire.showFilterModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-4"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-start justify-center pt-[12vh] overflow-y-auto"
    x-cloak>
    <div class="w-[393px] p-4 bg-white rounded-[30px] shadow-md relative mb-[5vh]">
        <button wire:click="$set('showFilterModal', false)"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-3xl font-bold w-8 h-8 flex items-center justify-center">
            ×
        </button>

        <div class="flex items-center space-x-2 mb-6">
            <span class="text-lg font-semibold">Filter</span>
        </div>

        <div class="space-y-4">
            <!-- Kabupaten Multiple Select -->
            <div class="relative" x-data="{ open: false }" @click.away="open = false; $wire.resetDropdownSearch()">
                <label class="block text-sm font-semibold mb-1">Kab/Kota</label>
                <div class="relative">
                    <button @click="open = !open" 
                            type="button"
                            class="w-full h-10 px-3 text-sm border border-[#e0e0e0] rounded-lg focus:outline-none focus:border-[#3560a0] bg-white flex items-center justify-between">
                        <span x-text="$wire.kabupaten_ids.length ? `${$wire.kabupaten_ids.length} dipilih` : 'Pilih Kab/Kota'"></span>
                        <svg class="w-4 h-4" :class="{'transform rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" 
                         class="absolute z-10 w-full mt-1 bg-white border rounded-lg shadow-lg">
                        <!-- Search input -->
                        <div class="p-2 border-b">
                            <input type="text" 
                                   wire:model.live.debounce.300ms="searchKabupaten" 
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-[#3560a0]"
                                   placeholder="Cari Kabupaten..."
                                   @click.stop>
                        </div>
                        <!-- Dropdown list -->
                        <div class="max-h-60 overflow-y-auto">
                            @if($filteredKabupatens->isEmpty())
                                <div class="px-3 py-2 text-gray-500 text-center">Tidak ada data</div>
                            @else
                                @foreach($filteredKabupatens as $kabupaten)
                                <label class="flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
                                    <input type="checkbox" 
                                           wire:model.live="kabupaten_ids" 
                                           value="{{ $kabupaten->id }}"
                                           class="rounded border-gray-300 text-[#3560a0] focus:ring-[#3560a0]">
                                    <span class="ml-2">{{ $kabupaten->nama }}</span>
                                </label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partisipasi Section -->
            <div class="relative">
                <label class="block text-sm font-semibold mb-1">Tingkat Partisipasi</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['hijau', 'kuning', 'merah'] as $color)
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                            wire:model.live="partisipasi" 
                            value="{{ $color }}" 
                            class="hidden">
                        <span class="px-3 py-1 rounded-full border text-sm font-medium transition-all duration-200"
                            :class="{ 
                                'bg-[#3560a0]': $wire.partisipasi.includes('{{ $color }}'),
                                'text-[#69d788]': $wire.partisipasi.includes('{{ $color }}') && '{{ $color }}' === 'hijau',
                                'text-[#ffe608]': $wire.partisipasi.includes('{{ $color }}') && '{{ $color }}' === 'kuning',
                                'text-[#fe756c]': $wire.partisipasi.includes('{{ $color }}') && '{{ $color }}' === 'merah',
                                'text-gray-600 bg-white': !$wire.partisipasi.includes('{{ $color }}')
                            }">
                            {{ ucfirst($color) }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Filter Actions -->
        <div class="flex justify-between mt-6">
            <button wire:click="resetFilter"
                class="w-[177px] h-10 bg-white border border-[#3560a0] text-[#3560a0] text-sm font-semibold rounded-full hover:bg-gray-50 transition-colors">
                Reset
            </button>
            <button wire:click="$set('showFilterModal', false)"
                class="w-[177px] h-10 bg-[#3560a0] text-white text-sm font-semibold rounded-full hover:bg-[#2d5288] transition-colors">
                Terapkan Filter
            </button>
        </div>
    </div>
</div>
</div>
</div>
</div>
