<!-- Import Kelurahan Modal -->
<div id="importKelurahanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="{{ route('kelurahan.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Impor Kelurahan</h3>

			{{-- Spreadsheet --}}
			<label for="importKelurahan" class="my-2 block">Spreadsheet (.csv atau .xlsx)</label>
			<input
                type="file"
                id="kelurahanSpreadsheet"
                name="spreadsheet"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            @if ($errors->has('spreadsheet'))
                <span class="text-red-800 mb-3 block">{{ $errors->first('spreadsheet') }}</span>
            @endif
            
            <p class="text-xs text-gray-500 my-2">
                Catatan: Pastikan berkas yang diimpor memiliki format yang sama dengan berkas hasil ekspor.
            </p>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="cancelImportKelurahan" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" id="confirmImportKelurahan" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Impor
				</button>
            </div>
        </form>
    </div>
</div>

<script>
	function showImportKelurahanModal() {
		const importKelurahanModal = document.getElementById('importKelurahanModal');
		importKelurahanModal.classList.remove('hidden');
	}

	function closeImportKelurahanModal() {
		const importKelurahanModal = document.getElementById('importKelurahanModal');
		importKelurahanModal.classList.add('hidden');
	}

    document.getElementById('importKelurahanBtn').addEventListener('click', showImportKelurahanModal);
    document.getElementById('cancelImportKelurahan').addEventListener('click', closeImportKelurahanModal);
</script>

@error('spreadsheet')
    <script>
        showImportKelurahanModal();
    </script>
@enderror