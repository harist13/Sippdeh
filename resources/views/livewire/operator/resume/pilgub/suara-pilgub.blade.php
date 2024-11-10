<div>
  <div class="bg-white rounded-[20px] mb-8 shadow-lg">
      <div class="bg-white sticky top-20 p-4 z-20 rounded-t-[20px] shadow-lg">
          <div class="container mx-auto">
              <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between">
                  {{-- Cari dan Filter --}}
                  @include('operator.resume.pilgub.search-filter')
              </div>
          </div>
      </div>

      <div class="overflow-x-auto -mx-4 sm:mx-0">
          <div class="inline-block min-w-full align-middle">
              <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg relative">
                  <!-- Loading Overlay -->
                  <div wire:loading.delay wire:target.except="applyFilter" class="absolute inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-10"></div>

                  <div class="px-4">
                      @include('operator.resume.pilgub.kelurahan-table', compact('suara', 'paslon', 'includedColumns'))
                  </div>
              </div>
          </div>
      </div>

      <div class="py-4 px-6">
          {{ $suara->links('vendor.livewire.simple', data: ['scrollTo' => false]) }}
      </div>
  </div>

  	<!-- Filter Pilgub Modal -->
  	@include(
		'operator.resume.pilgub.filter-modal',
		compact(
			'selectedProvinsi',
			'selectedKabupaten',
			'selectedKecamatan',
			'selectedKelurahan',
			'includedColumns',
			'partisipasi'
		)
	)
</div>