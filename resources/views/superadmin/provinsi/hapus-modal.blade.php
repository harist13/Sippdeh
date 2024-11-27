<div id="deleteProvinsiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
	<div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
		<form id="deleteProvinsiForm" action="{{ route('provinsi.store') }}" method="POST">
				@method('DELETE')
				@csrf
				<h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Hapus Provinsi</h3>
				<p>Apakah anda yakin ingin menghapus provinsi ini?</p>
				<hr class="h-1 my-3">
				<div class="flex items-center">
					<button type="button" id="cancelDeleteProvinsi"
						class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">Batalkan</button>
					<button type="submit" id="confirmDeleteProvinsi"
						class="flex-1 px-4 py-2 bg-[#C00000] text-white rounded-md hover:bg-blue-700">Hapus</button>
				</div>
		</form>
	</div>
</div>

@push('scripts')
	<script>
		function showDeleteProvinsiModal() {
			const deleteProvinsiModal = document.getElementById('deleteProvinsiModal');
			deleteProvinsiModal.classList.remove('hidden');
		}

		function closeDeleteProvinsiModal() {
			const deleteProvinsiModal = document.getElementById('deleteProvinsiModal');
			deleteProvinsiModal.classList.add('hidden');
		}

		function getProvinsiId() {
			return this.closest('tr').querySelector('td:nth-child(1)').dataset.id;
		}

		function getDestroyProvinsiUrl() {
			const provinsiId = getProvinsiId.call(this);
			const provinsiDestroyRoute = `{{ route('provinsi.destroy', ['provinsi' => '__provinsi__']) }}`;
			const provinsiDestroyUrl = provinsiDestroyRoute.replace('__provinsi__', provinsiId);

			return provinsiDestroyUrl;
		}

		function onRemoveButtonClick() {
			const deleteProvinsiForm = document.getElementById('deleteProvinsiForm');
			deleteProvinsiForm.action = getDestroyProvinsiUrl.call(this);
			
			showDeleteProvinsiModal();
		}

		function initializeRemoveProvinsiEvents() {
			setTimeout(function() {
				document.querySelectorAll('.hapus-provinsi-btn')
					.forEach(button => button.onclick = onRemoveButtonClick);
				
				document.getElementById('cancelDeleteProvinsi').onclick = closeDeleteProvinsiModal;
			}, 1000);
		}

		initializeRemoveProvinsiEvents();
	</script>
@endpush