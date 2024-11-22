<div>
	<div class="bg-white sticky top-20 p-4 z-20 rounded-t-[20px] shadow-lg">
		<div class="container mx-auto">
			{{-- Actionable --}}
			<div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between">
				{{-- Simpan, Batal Edit, dan Masuk Edit Mode --}}
				@include('operator.input-suara.pilgub.action-buttons')
				
				{{-- Cari dan Filter --}}
				@include('operator.input-suara.pilgub.export-search-filter')
			</div>
			
			{{-- Success Message --}}
			@php $status = session('pesan_sukses'); @endphp
			@isset ($status)
				<div class="mt-3">
					@include('components.alert-berhasil', ['message' => $status, 'withoutMarginBottom' => true])
				</div>
			@endisset

			{{-- Failed Message --}}
			@php $status = session('pesan_gagal'); @endphp
			@isset ($status)
				<div class="mt-3">
					@include('components.alert-gagal', ['message' => $status])
				</div>
			@endisset

			{{-- Loading --}}
			@include('operator.input-suara.pilgub.loading-alert')
		</div>
	</div>

	{{-- Table --}}
	<div class="overflow-x-auto -mx-4 sm:mx-0">
		<div class="inline-block min-w-full align-middle">
			<div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
				{{-- Loading Overlay --}}
				<div wire:loading.delay wire:target.except="export" class="absolute inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-10"></div>

				<div class="px-4">
					@include('operator.input-suara.pilgub.table', compact('tps', 'paslon', 'includedColumns'))
				</div>
			</div>
		</div>
	</div>

	{{-- Paginasi --}}
	<div class="py-4 px-6">
		{{ $tps->links('vendor.livewire.simple', data: ['scrollTo' => false]) }}
	</div>

	{{-- Filter Pilgub Modal --}}
	@include(
		'operator.input-suara.pilgub.filter-modal',
		compact('selectedKecamatan','selectedKelurahan','includedColumns','partisipasi')
	)
</div>

@assets
    <script src="{{ asset('scripts/input-suara.js') }}"></script>
@endassets

@script
    <script type="text/javascript">
        const inputSuaraUI = new InputSuaraUIManager($wire);
        inputSuaraUI.initialize();
        inputSuaraUI.initializeHooks();
    </script>
@endscript