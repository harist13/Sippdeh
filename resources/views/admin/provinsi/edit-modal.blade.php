<!-- Edit Provinsi Modal -->
<div id="editProvinsiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="editProvinsiForm" action="#" method="POST">
            @csrf
            @method('PUT')
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Edit Provinsi</h3>
            <input type="text" id="editProvinsiName" name="nama_provinsi"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama provinsi" required>
            <span class="text-red-800">{{ $errors->first('nama_provinsi') }}</span>
            <hr class="h-1 my-3">
            <div class="flex items-center">
                <button type="button" id="cancelEditProvinsi"
                    class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">Batalkan</button>
                <button type="submit" id="confirmEditProvinsi"
                    class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showEditProvinsiModal() {
        const editProvinsiModal = document.getElementById('editProvinsiModal');
        editProvinsiModal.classList.remove('hidden');
    }

    function closeEditProvinsiModal() {
        const editProvinsiModal = document.getElementById('editProvinsiModal');
        editProvinsiModal.classList.add('hidden');
    }

    function getProvinsiName() {
        return this.closest('tr').querySelector('td:nth-child(2)').textContent;
    }

    function getProvinsiId() {
        return this.closest('tr').querySelector('td:nth-child(1)').dataset.id;
    }

    function getUpdateProvinsiUrl() {
        const provinsiId = getProvinsiId.call(this);
        const provinsiUpdateRoute = `{{ route('provinsi.update', ['provinsi' => '__provinsi__']) }}`;
        const provinsiUpdateUrl = provinsiUpdateRoute.replace('__provinsi__', provinsiId);

        return provinsiUpdateUrl;
    }

    document.querySelectorAll('.edit-provinsi-btn').forEach(button => {
        button.addEventListener('click', function() {
            showEditProvinsiModal();

            const editProvinsiName = document.getElementById('editProvinsiName');
            editProvinsiName.value = getProvinsiName.call(this);
            
            const editProvinsiForm = document.getElementById('editProvinsiForm');
            editProvinsiForm.action = getUpdateProvinsiUrl.call(this);
        });
    });

    document.getElementById('cancelEditProvinsi').addEventListener('click', closeEditProvinsiModal);
</script>

@error('nama_provinsi')
    <script>
        showEditProvinsiModal();
    </script>
@enderror