<div>
	<div class="bg-white sticky top-20 p-4 z-20 rounded-t-[20px]">
		<div class="container mx-auto">
            <button wire:click="submit"
                class="bg-[#58DA91] disabled:bg-[#58da906c] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium sm:w-auto" wire:loading.attr="disabled" wire:target="submit">
                <i class="fas fa-check mr-3"></i>
                Simpan Perubahan Data
            </button>
			
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
		</div>
	</div>

	{{-- Table --}}
	<div class="overflow-x-auto -mx-4 sm:mx-0">
		<div class="inline-block min-w-full align-middle">
			<div class="overflow-hidden border-gray-200 shadow relative rounded-b-lg px-4 pb-4">
				<table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#3560A0] text-white">
                        <tr>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white select-none">
                                Kabupaten/Kota
                            </th>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white select-none">
                                DPTb
                            </th>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white select-none">
                                DPK
                            </th>
                        </tr>
                    </thead>
                
                    <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                        <tr>
                            <td class="py-3 px-4 text-sm border">
                                {{ session('operator_kabupaten_name') }}
                            </td>
                            <td class="py-3 px-4 text-center border">
                                <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 focus:outline-none" wire:model="dptb" />
                            </td>
                            <td class="py-3 px-4 text-center border">
                                <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 focus:outline-none" wire:model="dpk" />
                            </td>
                        </tr>
                    </tbody>
                </table>
			</div>
		</div>
	</div>
</div>

{{-- @assets
    <script src="{{ asset('scripts/input-suara.js') }}"></script>
@endassets

@script
    <script type="text/javascript">
        const inputSuaraUI = new InputSuaraUIManager($wire);
        inputSuaraUI.initialize();
        inputSuaraUI.initializeHooks();
    </script>
@endscript --}}