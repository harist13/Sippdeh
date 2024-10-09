<div id="deleteKecamatanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
	<div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
		<form id="deleteKecamatanForm" action="#" method="POST">
				@method('DELETE')
				@csrf
				<h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Hapus Kecamatan</h3>
				<p>Apakah anda yakin ingin menghapus kecamatan ini?</p>
				<hr class="h-1 my-3">
				<div class="flex items-center">
					<button type="button" id="cancelDeleteKecamatan"
						class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">Batalkan</button>
					<button type="submit" id="confirmDeleteKecamatan"
						class="flex-1 px-4 py-2 bg-[#C00000] text-white rounded-md hover:bg-blue-700">Hapus</button>
				</div>
		</form>
	</div>
</div>

<script>
	function showDeleteKecamatanModal() {
		const deleteKecamatanModal = document.getElementById('deleteKecamatanModal');
		deleteKecamatanModal.classList.remove('hidden');
	}

	function closeDeleteKecamatanModal() {
		const deleteKecamatanModal = document.getElementById('deleteKecamatanModal');
		deleteKecamatanModal.classList.add('hidden');
	}

	function getKecamatanId() {
		return this.closest('tr').querySelector('td:nth-child(2)').dataset.id;
	}

	function getDestroyKecamatanUrl() {
		const kecamatanId = getKecamatanId.call(this);
		const kecamatanDestroyRoute = `{{ route('kecamatan.destroy', ['kecamatan' => '__kecamatan__']) }}`;
		const kecamatanDestroyUrl = kecamatanDestroyRoute.replace('__kecamatan__', kecamatanId);

		return kecamatanDestroyUrl;
	}

	document.querySelectorAll('.hapus-kecamatan-btn').forEach(button => {
		button.addEventListener('click', function () {
			showDeleteKecamatanModal();

			const deleteKecamatanForm = document.getElementById('deleteKecamatanForm');
			deleteKecamatanForm.action = getDestroyKecamatanUrl.call(this);
		});
	});

	document.getElementById('cancelDeleteKecamatan').addEventListener('click', closeDeleteKecamatanModal);
</script>