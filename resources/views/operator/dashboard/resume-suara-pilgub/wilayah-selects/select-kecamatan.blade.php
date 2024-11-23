<div class="mb-5">
  <label for="kecamatan" class="mb-2 font-bold mt-5 block">Kecamatan</label>
  <div class="wilayah-select" wire:key="kecamatan-select">
      <div class="relative w-full">
          <button type="button" 
              class="select-button relative w-full {{ empty($selectedKabupaten) ? 'bg-gray-300 cursor-no-drop' : 'bg-white cursor-pointer' }} rounded-md border border-gray-300 py-2 pl-3 pr-10 text-left shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
              @empty($selectedKabupaten) disabled @endempty>
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

          <div class="options-container absolute z-10 mt-1 hidden w-full overflow-auto rounded-md bg-white text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
              <div class="sticky top-0 border-b border-gray-200 bg-white px-3 py-2">
                  <div class="relative mb-2">
                      <input type="text" class="search-input w-full rounded-md border border-gray-300 pl-8 pr-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari...">
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
          </div>
      </div>

      <!-- Hidden select for Livewire binding -->
      <select wire:model.live="selectedKecamatan" multiple class="hidden">
          @foreach($kecamatan as $kec)
              <option value="{{ $kec['id'] }}">{{ $kec['name'] }}</option>
          @endforeach
      </select>
  </div>
</div>