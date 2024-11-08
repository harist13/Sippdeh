<div>
    <!-- Export Modal -->
    <div x-show="$wire.showExportModal" 
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-20"
         x-cloak>
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-5">Ekspor Resume Walikota</h3>

                <div class="mb-4">
                    <div class="text-left mb-2">Kabupaten/Kota</div>
                    <select wire:model.live="exportKabupatenId" class="w-full p-2 border rounded">
                        <option value="">Semua</option>
                        @foreach($kabupatens as $kabupaten)
                        <option value="{{ $kabupaten->id }}">{{ $kabupaten->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4 flex justify-center gap-4">
                    <button wire:click="$set('showExportModal', false)"
                        class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-28 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batalkan
                    </button>
                    <button wire:click="export"
                        class="px-4 py-2 bg-[#3560A0] text-white text-base font-medium rounded-md w-28 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Ekspor
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
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
                <div class="relative">
                    <label class="block text-sm font-semibold mb-1">Kab/Kota</label>
                    <select wire:model.live="kabupaten_id" class="w-full h-10 px-3 text-sm border border-[#e0e0e0] rounded-lg focus:outline-none focus:border-[#3560a0] bg-white">
                        <option value="">Pilih Kab/Kota</option>
                        @foreach($kabupatens as $kabupaten)
                        <option value="{{ $kabupaten->id }}">{{ $kabupaten->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative">
                    <label class="block text-sm font-semibold mb-1">Kecamatan</label>
                    <select wire:model.live="kecamatan_id" class="w-full h-10 px-3 text-sm border border-[#e0e0e0] rounded-lg focus:outline-none focus:border-[#3560a0] bg-white" 
                            @disabled(!$kabupaten_id)>
                        <option value="">Pilih Kecamatan</option>
                        @foreach($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative">
                    <label class="block text-sm font-semibold mb-1">Kelurahan</label>
                    <select wire:model.live="kelurahan_id" class="w-full h-10 px-3 text-sm border border-[#e0e0e0] rounded-lg focus:outline-none focus:border-[#3560a0] bg-white"
                            @disabled(!$kecamatan_id)>
                        <option value="">Pilih Kelurahan</option>
                        @foreach($kelurahans as $kelurahan)
                        <option value="{{ $kelurahan->id }}">{{ $kelurahan->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative">
                    <label class="block text-sm font-semibold mb-1">Sembunyikan Kolom</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            'kabupaten' => 'Kab/Kota',
                            'kecamatan' => 'Kec.',
                            'kelurahan' => 'Kel.',
                            'calon' => 'Paslon',
                            'abstain' => 'Abstain'
                        ] as $key => $label)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" 
                                wire:model.live="hiddenColumns" 
                                value="{{ $key }}" 
                                class="rounded border-gray-300 text-[#3560a0] focus:ring-[#3560a0]">
                            <span class="text-sm">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

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

    <div class="container mx-auto p-4 sm:p-6 bg-white rounded-lg shadow-md">
        <div class="overflow-hidden mb-8">
            <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div class="text-black py-2 rounded-lg mb-4 sm:mb-0 w-full sm:w-auto">
                    Daftar Paslon Walikota Dengan Partisipasi Se-Kalimantan Timur
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                    <div class="flex items-center rounded-lg bg-[#ECEFF5] px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z"
                                clip-rule="evenodd" />
                        </svg>
                        <input type="search" 
                               wire:model.live.debounce.1000ms="search"
                               placeholder="Cari..." 
                               class="ml-2 bg-transparent focus:outline-none text-gray-600">
                    </div>
                    <button wire:click="$set('showFilterModal', true)"
                            class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 flex items-center justify-center w-full sm:w-auto">
                        <img src="{{ asset('assets/icon/filter.svg') }}" alt="">
                        Filter
                    </button>
                    <button wire:click="$set('showExportModal', true)"
                            class="px-4 py-2 bg-[#ee3c46] text-white rounded-lg whitespace-nowrap flex items-center space-x-2">
                        <img src="{{ asset('assets/icon/download.png') }}" alt="">
                        <span>Export</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full bg-white shadow-md rounded-lg overflow-hidden border-collapse text-center">
                    <thead class="bg-[#3560a0] text-white">
                        <tr>
                            <th class="py-3 px-4 border-r border-white">No</th>
                            @if(!in_array('kabupaten', $hiddenColumns))
                                <th class="py-3 px-4 border-r border-white">Kab/Kota</th>
                            @endif
                            @if(!in_array('kecamatan', $hiddenColumns))
                                <th class="py-3 px-4 border-r border-white">Kec.</th>
                            @endif
                            @if(!in_array('kelurahan', $hiddenColumns))
                                <th class="py-3 px-4 border-r border-white">Kel.</th>
                            @endif
                            <th class="py-3 px-4 border-r border-white">DPT</th>
                            @if(!in_array('calon', $hiddenColumns))
                                @foreach($paslon as $calon)
                                <th class="py-3 px-4 border-r border-white">
                                    {{ $calon->nama }}/{{ $calon->nama_wakil }}
                                </th>
                                @endforeach
                            @endif
                            @if(!in_array('abstain', $hiddenColumns))
                                <th class="py-3 px-4 border-r border-white">Abstain</th>
                            @endif
                            <th class="py-3 px-4 border-r border-white">Tingkat Partisipasi (%)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-100">
                        @forelse($walikotaData as $index => $data)
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">
                                {{ str_pad(($walikotaData->currentPage() - 1) * $walikotaData->perPage() + $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            @if(!in_array('kabupaten', $hiddenColumns))
                                <td class="py-3 px-4 border-r">{{ $data->kabupaten_nama }}</td>
                            @endif
                            @if(!in_array('kecamatan', $hiddenColumns))
                                <td class="py-3 px-4 border-r">{{ $data->kecamatan_nama }}</td>
                            @endif
                            @if(!in_array('kelurahan', $hiddenColumns))
                                <td class="py-3 px-4 border-r">{{ $data->kelurahan_nama }}</td>
                            @endif
                            <td class="py-3 px-4 border-r">{{ $data->suara->dpt ?? 0 }}</td>
                            @if(!in_array('calon', $hiddenColumns))
                                @foreach($paslon as $calon)
                                <td class="py-3 px-4 border-r">
                                    {{ $data->suaraCalon->where('calon_id', $calon->id)->first()->suara ?? 0 }}
                                </td>
                                @endforeach
                            @endif
                            @if(!in_array('abstain', $hiddenColumns))
                                <td class="py-3 px-4 border-r">{{ $data->abstain ?? 0 }}</td>
                            @endif
                            <td class="py-3 px-4 border-r">
                                @php
                                $partisipasi = $data->partisipasi ?? 0;
                                $colorClass = $partisipasi >= 80 ? 'bg-green-400' : 
                                        ($partisipasi >= 60 ? 'bg-yellow-400' : 'bg-red-400');
                                @endphp
                                <div class="participation-button {{ $colorClass }} text-white py-1 px-7 rounded text-xs">
                                    {{ number_format($partisipasi, 1) }}%
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="100%" class="py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <p class="text-lg">Data yang dicari tidak ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $walikotaData->links('vendor.livewire.simple') }}
            </div>
        </div>
    </div>
</div>