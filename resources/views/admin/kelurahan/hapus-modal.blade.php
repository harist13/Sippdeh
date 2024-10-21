<div id="deleteKelurahanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
	<div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
		<form id="deleteKelurahanForm" action="#" method="POST">
				@method('DELETE')
				@csrf
				<h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Hapus Kelurahan</h3>
				<p>Apakah anda yakin ingin menghapus kelurahan ini?</p>
				<hr class="h-1 my-3">
				<div class="flex items-center">
					<button type="button" id="cancelDeleteKelurahan"
						class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">Batalkan</button>
					<button type="submit" id="confirmDeleteKelurahan"
						class="flex-1 px-4 py-2 bg-[#C00000] text-white rounded-md hover:bg-blue-700">Hapus</button>
				</div>
		</form>
	</div>
</div>

<script>
	function showDeleteKelurahanModal() {
		const deleteKelurahanModal = document.getElementById('deleteKelurahanModal');
		deleteKelurahanModal.classList.remove('hidden');
	}

	function closeDeleteKelurahanModal() {
		const deleteKelurahanModal = document.getElementById('deleteKelurahanModal');
		deleteKelurahanModal.classList.add('hidden');
	}

	function getKelurahanId() {
		return this.closest('tr').querySelector('td:nth-child(2)').dataset.id;
	}

	function getDestroyKelurahanUrl() {
		const kelurahanId = getKelurahanId.call(this);
		const kelurahanDestroyRoute = `{{ route('kelurahan.destroy', ['kelurahan' => '__kelurahan__']) }}`;
		const kelurahanDestroyUrl = kelurahanDestroyRoute.replace('__kelurahan__', kelurahanId);

		return kelurahanDestroyUrl;
	}

	document.querySelectorAll('.hapus-kelurahan-btn').forEach(button => {
		button.addEventListener('click', function () {
			showDeleteKelurahanModal();

			const deleteKelurahanForm = document.getElementById('deleteKelurahanForm');
			deleteKelurahanForm.action = getDestroyKelurahanUrl.call(this);
		});
	});

	document.getElementById('cancelDeleteKelurahan').addEventListener('click', closeDeleteKelurahanModal);
</script>