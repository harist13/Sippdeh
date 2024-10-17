<!-- Export Kabupaten Modal -->
<div id="exportKabupatenModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="{{ route('kabupaten.export') }}" method="GET">
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Ekspor Kabupaten</h3>

			{{-- Kabupaten --}}
			<label for="exportKabupatenKabupaten" class="my-1 block">Kabupaten</label>
			<select id="exportKabupatenKabupaten" name="kabupaten_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                <option value="0">Semua</option>
				@foreach ($kabupaten as $kab)
					<option value="{{ $kab->id }}" {{ request()->has('kabupaten') && request()->get('kabupaten') == $kab->id ? 'selected' : '' }}>{{ $kab->nama }}</option>
				@endforeach
			</select>
			<span class="text-red-800">{{ $errors->first('kabupaten_id') }}</span>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="cancelExportKabupaten" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" id="confirmExportKabupaten" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Ekspor
				</button>
            </div>
        </form>
    </div>
</div>

<script>
	function showExportKabupatenModal() {
		const exportKabupatenModal = document.getElementById('exportKabupatenModal');
		exportKabupatenModal.classList.remove('hidden');
	}

	function closeExportKabupatenModal() {
		const exportKabupatenModal = document.getElementById('exportKabupatenModal');
		exportKabupatenModal.classList.add('hidden');
	}

    document.getElementById('exportKabupatenBtn').addEventListener('click', showExportKabupatenModal);
    document.getElementById('cancelExportKabupaten').addEventListener('click', closeExportKabupatenModal);
</script>

@error('kabupaten_id')
    <script>
        showExportKabupatenModal();
    </script>
@enderror