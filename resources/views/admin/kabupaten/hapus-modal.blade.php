<div id="deleteKabupatenModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="deleteKabupatenForm" action="#" method="POST">
            @method('DELETE')
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Hapus Kabupaten/Kota</h3>
            <p>Apakah anda yakin ingin menghapus kabupaten/kota ini?</p>
            <hr class="h-1 my-3">
            <div class="flex items-center">
                <button type="button" id="closeDeleteKabupaten"
                    class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">Batalkan</button>
                <button type="submit" id="confirmDeleteKabupaten"
                    class="flex-1 px-4 py-2 bg-[#C00000] text-white rounded-md hover:bg-blue-700">Hapus</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        function showDeleteKabupatenModal() {
            const deleteKabupatenModal = document.getElementById('deleteKabupatenModal');
            deleteKabupatenModal.classList.remove('hidden');
        }

        function closeDeleteKabupatenModal() {
            const deleteKabupatenModal = document.getElementById('deleteKabupatenModal');
            deleteKabupatenModal.classList.add('hidden');
        }

        function getKabupatenId() {
            // Mengambil ID dari elemen dengan class kabupaten-data
            return this.closest('tr').dataset.id;
        }

        function getDestroyKabupatenUrl() {
            const kabupatenId = getKabupatenId.call(this);

            const kabupatenDestroyRoute = `{{ route('kabupaten.destroy', ['kabupaten' => '__kabupaten__']) }}`;
            const kabupatenDestroyUrl = kabupatenDestroyRoute.replace('__kabupaten__', kabupatenId);

            return kabupatenDestroyUrl;
        }

        function onDeleteKabupatenClick() {
            const kabupatenId = getKabupatenId.call(this);

            const deleteKabupatenForm = document.getElementById('deleteKabupatenForm');
            deleteKabupatenForm.action = getDestroyKabupatenUrl.call(this);

            showDeleteKabupatenModal();
        }

        document.querySelectorAll('.hapus-kabupaten')
            .forEach(button => button.addEventListener('click', onDeleteKabupatenClick));

        document.getElementById('closeDeleteKabupaten').addEventListener('click', closeDeleteKabupatenModal);
    </script>
@endpush