<div id="deleteTPSModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
	<div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
		<form id="deleteTPSForm" action="#" method="POST">
				@method('DELETE')
				@csrf
				<h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Hapus TPS</h3>
				<p>Apakah anda yakin ingin menghapus tps ini?</p>
				<hr class="h-1 my-3">
				<div class="flex items-center">
					<button type="button" id="cancelDeleteTPS"
						class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">Batalkan</button>
					<button type="submit" id="confirmDeleteTPS"
						class="flex-1 px-4 py-2 bg-[#C00000] text-white rounded-md hover:bg-blue-700">Hapus</button>
				</div>
		</form>
	</div>
</div>

<script>
	function showDeleteTPSModal() {
		const deleteTPSModal = document.getElementById('deleteTPSModal');
		deleteTPSModal.classList.remove('hidden');
	}

	function closeDeleteTPSModal() {
		const deleteTPSModal = document.getElementById('deleteTPSModal');
		deleteTPSModal.classList.add('hidden');
	}

	function getTPSId() {
		return this.closest('tr').querySelector('td:nth-child(5)').dataset.id;
	}

	function getDestroyTPSUrl() {
		const tpsId = getTPSId.call(this);
		const tpsDestroyRoute = `{{ route('tps.destroy', ['tp' => '__tp__']) }}`;
		const tpsDestroyUrl = tpsDestroyRoute.replace('__tp__', tpsId);

		return tpsDestroyUrl;
	}

	document.querySelectorAll('.hapus-tps-btn').forEach(button => {
		button.addEventListener('click', function () {
			showDeleteTPSModal();

			const deleteTPSForm = document.getElementById('deleteTPSForm');
			deleteTPSForm.action = getDestroyTPSUrl.call(this);
		});
	});

	document.getElementById('cancelDeleteTPS').addEventListener('click', closeDeleteTPSModal);
</script>