<div id="deleteCalonModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="deleteCalonForm" action="#" method="POST">
            @method('DELETE')
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Hapus Calon</h3>
            <p>Apakah anda yakin ingin menghapus calon ini?</p>
            <hr class="h-1 my-3">
            <div class="flex items-center">
                <button type="button" id="cancelDeleteCalon"
                    class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">Batalkan</button>
                <button type="submit" id="confirmDeleteCalon"
                    class="flex-1 px-4 py-2 bg-[#C00000] text-white rounded-md hover:bg-blue-700">Hapus</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showDeleteCalonModal() {
        const deleteCalonModal = document.getElementById('deleteCalonModal');
        deleteCalonModal.classList.remove('hidden');
    }

    function closeDeleteCalonModal() {
        const deleteCalonModal = document.getElementById('deleteCalonModal');
        deleteCalonModal.classList.add('hidden');
    }

    function getDeleteUrl(id) {
        return `{{ route('calon.destroy', ['calon' => ':id']) }}`.replace(':id', id);
    }

    document.querySelectorAll('.hapus-calon-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            showDeleteCalonModal();

            const deleteCalonForm = document.getElementById('deleteCalonForm');
            deleteCalonForm.action = getDeleteUrl(id);
        });
    });

    document.getElementById('cancelDeleteCalon').addEventListener('click', closeDeleteCalonModal);
</script>