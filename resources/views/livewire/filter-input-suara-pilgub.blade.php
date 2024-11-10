<div>
    <div class="flex items-center mb-5">
        <i class="fas fa-arrow-left mr-3 select-none cursor-pointer" id="cancelFilterPilgub"></i>
        <h3 class="text-lg font-medium text-gray-900">Filter</h3>
    </div>

    {{-- Provinsi --}}
    <div class="mb-5">
        <label for="provinsi" class="mb-2 font-bold mt-5 block">Provinsi</label>
        <div class="custom-select" wire:key="provinsi-select">
            <div class="relative w-full">
                <button type="button" class="select-button relative w-full cursor-pointer rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-left shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <span class="selected-text block truncate">Pilih provinsi...</span>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>

                <!-- Hidden select for Livewire binding -->
                <select wire:model.live="selectedProvinsi" multiple class="hidden">
                    @foreach($provinsi as $prov)
                        <option value="{{ $prov['id'] }}">{{ $prov['name'] }}</option>
                    @endforeach
                </select>

                <div class="options-container absolute z-10 mt-1 hidden w-full overflow-auto rounded-md bg-white text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="sticky top-0 border-b border-gray-200 bg-white px-3 py-2">
                        <div class="relative mb-2">
                            <input 
                                type="text" 
                                class="search-input w-full rounded-md border border-gray-300 pl-8 pr-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Cari..."
                            >
                            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <button type="button" class="select-all-button text-sm text-blue-600 hover:text-blue-800 font-medium focus:outline-none">
                            Pilih Semua
                        </button>
                    </div>

                    <div class="max-h-60 overflow-y-auto py-1"> <!-- Wrap options in scrollable container -->
                        @if(count($provinsi) > 0)
                            @foreach($provinsi as $prov)
                                <div class="option relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-gray-100" data-value="{{ $prov['id'] }}" data-name="{{ $prov['name'] }}">
                                    <span class="block truncate">{{ $prov['name'] }}</span>
                                    <span class="checkmark absolute inset-y-0 right-0 hidden items-center pr-4 text-blue-600">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <div class="py-3 px-3 text-sm text-gray-500 text-center">Tidak ada data provinsi</div>
                        @endif
                    </div>
                    
                    <!-- Apply button section -->
                    <div class="border-t border-gray-200 bg-gray-50 px-3 py-2">
                        <button wire:loading.attr="disabled" wire:target="selectedProvinsi" type="button" class="apply-button w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 disabled:bg-blue-200">
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kabupaten --}}
    <div class="mb-5">
        <label for="kabupaten" class="mb-2 font-bold mt-5 block">Kabupaten</label>
        <div class="custom-select" wire:key="kabupaten-select">
            <div class="relative w-full">
                <button type="button" class="select-button relative w-full {{ empty($selectedProvinsi) ? 'bg-gray-300 cursor-no-drop' : 'bg-white cursor-pointer' }} rounded-md border border-gray-300 py-2 pl-3 pr-10 text-left shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" @empty($selectedProvinsi) disabled @endempty>
                    <span class="selected-text block truncate">
                        @if(empty($selectedProvinsi))
                            Pilih provinsi terlebih dahulu...
                        @else
                            Pilih kabupaten...
                        @endif
                    </span>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>

                <!-- Hidden select for Livewire binding -->
                <select wire:model.live="selectedKabupaten" multiple class="hidden">
                    @foreach($kabupaten as $kab)
                        <option value="{{ $kab['id'] }}">{{ $kab['name'] }}</option>
                    @endforeach
                </select>

                <div class="options-container absolute z-10 mt-1 hidden w-full overflow-auto rounded-md bg-white text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="sticky top-0 border-b border-gray-200 bg-white px-3 py-2">
                        <div class="relative mb-2">
                            <input 
                                type="text" 
                                class="search-input w-full rounded-md border border-gray-300 pl-8 pr-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Cari..."
                            >
                            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <button type="button" class="select-all-button text-sm text-blue-600 hover:text-blue-800 font-medium focus:outline-none">
                            Pilih Semua
                        </button>
                    </div>
                    <div class="max-h-60 overflow-y-auto py-1">
                        @if(count($kabupaten) > 0)
                            @foreach($kabupaten as $kab)
                                <div class="option relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-gray-100" data-value="{{ $kab['id'] }}" data-name="{{ $kab['name'] }}">
                                    <span class="block truncate">{{ $kab['name'] }}</span>
                                    <span class="checkmark absolute inset-y-0 right-0 hidden items-center pr-4 text-blue-600">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                            @endforeach
                        @else
                            @if(!empty($selectedProvinsi))
                                <div class="py-3 px-3 text-sm text-gray-500 text-center">Tidak ada data kabupaten</div>
                            @else
                                <div class="py-3 px-3 text-sm text-gray-500 text-center">Pilih provinsi terlebih dahulu</div>
                            @endif
                        @endif
                    </div>
                    
                    <!-- Apply button section -->
                    <div class="border-t border-gray-200 bg-gray-50 px-3 py-2">
                        <button wire:loading.attr="disabled" wire:target="selectedKabupaten" type="button" class="apply-button w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 disabled:bg-blue-200">
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kecamatan --}}
    <div class="mb-5">
        <label for="kecamatan" class="mb-2 font-bold mt-5 block">Kecamatan</label>
        <div class="custom-select" wire:key="kecamatan-select">
            <div class="relative w-full">
                <button type="button" class="select-button relative w-full {{ empty($selectedKabupaten) ? 'bg-gray-300 cursor-no-drop' : 'bg-white cursor-pointer' }} rounded-md border border-gray-300 py-2 pl-3 pr-10 text-left shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" @empty($selectedKabupaten) disabled @endempty>
                    <span class="selected-text block truncate">
                        @if(empty($selectedKabupaten))
                            Pilih kabupaten terlebih dahulu...
                        @else
                            Pilih kecamatan...
                        @endif
                    </span>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>

                <!-- Hidden select for Livewire binding -->
                <select wire:model.live="selectedKecamatan" multiple class="hidden">
                    @foreach($kecamatan as $kec)
                        <option value="{{ $kec['id'] }}">{{ $kec['name'] }}</option>
                    @endforeach
                </select>

                <div class="options-container absolute z-10 mt-1 hidden w-full overflow-auto rounded-md bg-white text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="sticky top-0 border-b border-gray-200 bg-white px-3 py-2">
                        <div class="relative mb-2">
                            <input 
                                type="text" 
                                class="search-input w-full rounded-md border border-gray-300 pl-8 pr-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Cari..."
                            >
                            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <button type="button" class="select-all-button text-sm text-blue-600 hover:text-blue-800 font-medium focus:outline-none">
                            Pilih Semua
                        </button>
                    </div>
                    <div class="max-h-60 overflow-y-auto py-1">
                        @if(count($kecamatan) > 0)
                            @foreach($kecamatan as $kec)
                                <div class="option relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-gray-100" data-value="{{ $kec['id'] }}" data-name="{{ $kec['name'] }}">
                                    <span class="block truncate">{{ $kec['name'] }}</span>
                                    <span class="checkmark absolute inset-y-0 right-0 hidden items-center pr-4 text-blue-600">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                            @endforeach
                        @else
                            @if(!empty($selectedKabupaten))
                                <div class="py-3 px-3 text-sm text-gray-500 text-center">Tidak ada data kecamatan</div>
                            @else
                                <div class="py-3 px-3 text-sm text-gray-500 text-center">Pilih kabupaten terlebih dahulu</div>
                            @endif
                        @endif
                    </div>
                    
                    <!-- Apply button section -->
                    <div class="border-t border-gray-200 bg-gray-50 px-3 py-2">
                        <button wire:loading.attr="disabled" wire:target="selectedKecamatan" type="button" class="apply-button w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 disabled:bg-blue-200">
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kelurahan --}}
    <div class="mb-5">
        <label for="kelurahan" class="mb-2 font-bold mt-5 block">Kelurahan</label>
        <div class="custom-select" wire:key="kelurahan-select">
            <div class="relative w-full">
                <button type="button" class="select-button relative w-full {{ empty($selectedKecamatan) ? 'bg-gray-300 cursor-no-drop' : 'bg-white cursor-pointer' }} rounded-md border border-gray-300 py-2 pl-3 pr-10 text-left shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" @empty($selectedKecamatan) disabled @endempty>
                    <span class="selected-text block truncate">
                        @if(empty($selectedKecamatan))
                            Pilih kecamatan terlebih dahulu...
                        @else
                            Pilih kelurahan...
                        @endif
                    </span>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>

                <!-- Hidden select for Livewire binding -->
                <select wire:model.live="selectedKelurahan" multiple class="hidden">
                    @foreach($kelurahan as $kel)
                        <option value="{{ $kel['id'] }}">{{ $kel['name'] }}</option>
                    @endforeach
                </select>

                <div class="options-container absolute z-10 mt-1 hidden w-full overflow-auto rounded-md bg-white text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="sticky top-0 border-b border-gray-200 bg-white px-3 py-2">
                        <div class="relative mb-2">
                            <input 
                                type="text" 
                                class="search-input w-full rounded-md border border-gray-300 pl-8 pr-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Cari..."
                            >
                            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <button type="button" class="select-all-button text-sm text-blue-600 hover:text-blue-800 font-medium focus:outline-none">
                            Pilih Semua
                        </button>
                    </div>
                    <div class="max-h-60 overflow-y-auto py-1">
                        @if(count($kelurahan) > 0)
                            @foreach($kelurahan as $kel)
                                <div class="option relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-gray-100" data-value="{{ $kel['id'] }}" data-name="{{ $kel['name'] }}">
                                    <span class="block truncate">{{ $kel['name'] }}</span>
                                    <span class="checkmark absolute inset-y-0 right-0 hidden items-center pr-4 text-blue-600">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                            @endforeach
                        @else
                            @if(!empty($selectedKecamatan))
                                <div class="py-3 px-3 text-sm text-gray-500 text-center">Tidak ada data kelurahan</div>
                            @else
                                <div class="py-3 px-3 text-sm text-gray-500 text-center">Pilih kecamatan terlebih dahulu</div>
                            @endif
                        @endif
                    </div>
                    
                    <!-- Apply button section -->
                    <div class="border-t border-gray-200 bg-gray-50 px-3 py-2">
                        <button wire:loading.attr="disabled" wire:target="selectedKelurahan" type="button" class="apply-button w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 disabled:bg-blue-200">
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom --}}
    <div class="mb-5">
        <label for="kolom" class="mb-3 font-bold mt-5 block">Kolom</label>
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
    </div>
    {{-- <span class="text-red-800">{{ $errors->first('kabupaten_id') }}</span> --}}

    {{-- Tingkat Partisipasi --}}
    <div class="mb-5" x-data="{ partisipasiList: $wire.partisipasi }">
        <label for="partisipasi" class="mb-3 font-bold mt-5 block">Tingkat Partisipasi</label>
        <div class="flex gap-2">
            <label for="partisipasi-hijau" class="flex items-center gap-2 cursor-pointer">
                <input 
                    type="checkbox" 
                    class="hidden" 
                    id="partisipasi-hijau" 
                    value="HIJAU" 
                    wire:model="partisipasi"
                    x-on:change="partisipasiList = $wire.partisipasi"
                />
                <span class="bg-green-400 text-white py-2 px-7 rounded text-sm select-none transition-all duration-200"
                    :class="{ 'border-2 border-blue-500': partisipasiList.includes('HIJAU'), 'border-2 border-transparent': !partisipasiList.includes('HIJAU') }"
                >> 80%</span>
            </label>
            <label for="partisipasi-kuning" class="flex items-center gap-2 cursor-pointer">
                <input 
                    type="checkbox" 
                    class="hidden" 
                    id="partisipasi-kuning" 
                    value="KUNING" 
                    wire:model="partisipasi"
                    x-on:change="partisipasiList = $wire.partisipasi"
                />
                <span class="bg-yellow-400 text-white py-2 px-7 rounded text-sm select-none transition-all duration-200"
                    :class="{ 'border-2 border-blue-500': partisipasiList.includes('KUNING'), 'border-2 border-transparent': !partisipasiList.includes('KUNING') }"
                >> 60%</span>
            </label>
            <label for="partisipasi-merah" class="flex items-center gap-2 cursor-pointer">
                <input 
                    type="checkbox" 
                    class="hidden" 
                    id="partisipasi-merah" 
                    value="MERAH" 
                    wire:model="partisipasi"
                    x-on:change="partisipasiList = $wire.partisipasi"
                />
                <span class="bg-red-400 text-white py-2 px-7 rounded text-sm select-none transition-all duration-200"
                    :class="{ 'border-2 border-blue-500': partisipasiList.includes('MERAH'), 'border-2 border-transparent': !partisipasiList.includes('MERAH') }"
                >< 60%</span>
            </label>
        </div>
    </div>
    
    <hr class="h-1 my-3">

    <div class="flex">
        <button type="button" wire:loading.attr="disabled" wire:click="resetFilter" class="flex-1 bg-gray-300 disabled:bg-[#d1d5d06c] hover:bg-gray-400 text-black rounded-md px-4 py-2 mr-2">
            Reset
        </button>
        <button type="submit" wire:loading.attr="disabled" wire:click="applyFilter" id="applyFilterPilgub" class="flex-1 bg-[#3560A0] disabled:bg-[#0070F06c] hover:bg-blue-700 text-white rounded-md px-4 py-2">
            Terapkan
        </button>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.timeoutId = null;

            function initializeCustomSelect(selectContainer) {
                const button = selectContainer.querySelector('.select-button');
                const hiddenSelect = selectContainer.querySelector('select');
                const selectedText = selectContainer.querySelector('.selected-text');
                const optionsContainer = selectContainer.querySelector('.options-container');
                const searchInput = optionsContainer.querySelector('.search-input');
                const selectAllButton = optionsContainer.querySelector('.select-all-button');
                const options = optionsContainer.querySelectorAll('.option');
                const applyButton = optionsContainer.querySelector('.apply-button');
                
                // Store selected values
                let selectedValues = new Set();
                
                // Toggle options container
                button.onclick = function() {
                    if (button.disabled) return;
                    
                    const isOpen = optionsContainer.classList.contains('block');
                    closeAllOptionContainers();
                    
                    if (!isOpen) {
                        optionsContainer.classList.remove('hidden');
                        optionsContainer.classList.add('block');
                    }
                };

                searchInput.onkeydown = function(event) {
                    if (event.ctrlKey && event.key === "a") {
                        event.stopPropagation();
                    }
                }

                searchInput.onkeyup = function(event) {
                    options.forEach(option => {
                        if (option.dataset.name.toLowerCase().includes(this.value.toLowerCase())) {
                            option.classList.remove('hidden');
                        } else {
                            option.classList.add('hidden');
                        }
                    });
                };

                selectAllButton?.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    const visibleOptions = Array.from(options).filter(option => 
                        !option.classList.contains('hidden')
                    );
                    
                    const allSelected = visibleOptions.every(option => 
                        selectedValues.has(option.dataset.value)
                    );
                    
                    visibleOptions.forEach(option => {
                        const value = option.dataset.value;
                        const name = option.dataset.name;
                        const checkmark = option.querySelector('.checkmark');

                        if (allSelected) {
                            selectedValues.delete(value);
                            option.classList.remove('bg-blue-100');
                            checkmark.classList.remove('flex');
                            checkmark.classList.add('hidden');
                        } else {
                            selectedValues.add(value);
                            option.classList.add('bg-blue-100');
                            checkmark.classList.remove('hidden');
                            checkmark.classList.add('flex');
                        }
                    });
                    
                    // Update button text
                    this.textContent = allSelected ? 'Pilih Semua' : 'Batal Pilih Semua';
                });

                function closeContainer(e) {
                    if (!selectContainer.contains(e.target)) {
                        optionsContainer.classList.remove('block');
                        optionsContainer.classList.add('hidden');
                    }
                }
                
                // Close when clicking outside
                document.removeEventListener('click', closeContainer);
                document.addEventListener('click', closeContainer);
                
                // Handle option selection
                options.forEach(option => {
                    option.onclick = function() {
                        const value = this.dataset.value;
                        const name = this.dataset.name;
                        const checkmark = this.querySelector('.checkmark');
                        
                        if (selectedValues.has(value)) {
                            selectedValues.delete(value);
                            this.classList.remove('bg-blue-100');
                            checkmark.classList.remove('flex');
                            checkmark.classList.add('hidden');
                        } else {
                            selectedValues.add(value);
                            this.classList.add('bg-blue-100');
                            checkmark.classList.remove('hidden');
                            checkmark.classList.add('flex');
                        }
                    };
                });

                applyButton.onclick = function() {
                    updateSelectedText();

                    // Update the hidden select
                    Array.from(hiddenSelect.options).forEach(option => {
                        option.selected = selectedValues.has(option.value);
                    });
                    
                    // Dispatch change event
                    hiddenSelect.dispatchEvent(new Event('change'));
                };
                
                // Update selected text
                function updateSelectedText() {
                    if (selectedValues.size === 0) {
                        // selectedText.textContent = button.disabled ? 
                        //     'Pilih provinsi terlebih dahulu...' : 
                        //     'Pilih ' + (selectContainer.closest('[wire\\:key="provinsi-select"]') ? 'provinsi' : 'kabupaten') + '...';
                        
                        selectedText.textContent = 'Pilih...';
                    } else {
                        const selectedNames = Array.from(options)
                            .filter(option => selectedValues.has(option.dataset.value))
                            .map(option => option.dataset.name);
                        
                        selectedText.textContent = selectedNames.join(', ');
                    }
                }
                
                // Initialize from current select values
                function initializeFromSelect() {
                    selectedValues.clear();

                    Array.from(hiddenSelect.selectedOptions).forEach(option => {
                        selectedValues.add(option.value);
                        const optionEl = optionsContainer.querySelector(`.option[data-value="${option.value}"]`);
                        if (optionEl) {
                            const checkmark = optionEl.querySelector('.checkmark');
                            checkmark.classList.remove('hidden');
                            checkmark.classList.add('flex');
                            
                            optionEl.classList.add('bg-blue-100');
                        }
                    });

                    updateSelectedText();
                }
                
                initializeFromSelect();
                
                // Listen for Livewire updates
                document.removeEventListener('livewire:initialized', initializeFromSelect);
                document.addEventListener('livewire:initialized', initializeFromSelect);
            }
            
            function closeAllOptionContainers() {
                document.querySelectorAll('.options-container').forEach(container => {
                    container.classList.remove('block');
                    container.classList.add('hidden');
                });
            }
            
            // Initialize all custom selects
            document.querySelectorAll('.custom-select').forEach(initializeCustomSelect);
            
            // Re-initialize when Livewire updates the DOM
            Livewire.hook('morph.updated', ({ el }) => {
                clearTimeout(window.timeoutId);
                window.timeoutId = setTimeout(() => {
                    document.querySelectorAll('.custom-select').forEach(initializeCustomSelect);
                    window.timeoutId = null;
                }, 100);
            });
        });
    </script>
@endpush