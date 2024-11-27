<div id="deleteGambarCalonModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
	<div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
		<form id="deleteGambarCalonForm" action="#" method="POST">
				@method('DELETE')
				@csrf
				<h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Hapus Gambar Calon</h3>
				<p class="mb-3">Apakah anda yakin ingin menghapus gambar calon ini?</p>
				<img src="#" id="gambarCalon" class="rounded-md" width="100%" alt="Gambar">
				<hr class="h-1 my-3">
				<div class="flex items-center">
					<button type="button" id="cancelDeleteGambarCalon"
						class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">Batalkan</button>
					<button type="submit" id="confirmDeleteGambarCalon"
						class="flex-1 px-4 py-2 bg-[#C00000] text-white rounded-md hover:bg-blue-700">Hapus</button>
				</div>
		</form>
	</div>
</div>

<script>
	function setGambarCalon(url) {
		const gambarCalon = document.getElementById('gambarCalon');
		gambarCalon.src = url;
	}

	function showDeleteGambarCalonModal() {
		const deleteGambarCalonModal = document.getElementById('deleteGambarCalonModal');
		deleteGambarCalonModal.classList.remove('hidden');
	}

	function closeDeleteGambarCalonModal() {
		const deleteGambarCalonModal = document.getElementById('deleteGambarCalonModal');
		deleteGambarCalonModal.classList.add('hidden');
	}

	function getCalonId() {
		return this.closest('tr').querySelector('td:nth-child(2)').dataset.id;
	}

	function getDestroyGambarCalonUrl() {
		const calonId = getCalonId.call(this);
		const calonDestroyRoute = `{{ route('calon.destroy-gambar', ['id' => '__calonId__']) }}`;
		const calonDestroyUrl = calonDestroyRoute.replace('__calonId__', calonId);

		return calonDestroyUrl;
	}

	document.querySelectorAll('.hapus-gambar-calon-btn').forEach(button => {
		button.addEventListener('click', function () {
			setGambarCalon(this.dataset.url);
			showDeleteGambarCalonModal();

			const deleteGambarCalonForm = document.getElementById('deleteGambarCalonForm');
			deleteGambarCalonForm.action = getDestroyGambarCalonUrl.call(this);
		});
	});

	document.getElementById('cancelDeleteGambarCalon').addEventListener('click', closeDeleteGambarCalonModal);
</script>