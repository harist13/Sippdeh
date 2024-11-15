<!-- Edit Provinsi Modal -->
<div id="editProvinsiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="editProvinsiForm" action="#" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Edit Provinsi</h3>
            
            <input type="text" id="editProvinsiName" name="name"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama provinsi" required>
            <span class="text-red-800">{{ $errors->first('name') }}</span>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Logo</label>
                <div id="currentLogo" class="mt-2 mb-2">
                    <img src="" alt="Current Logo" class="w-20 h-20 object-cover rounded-full mx-auto hidden">
                </div>
                <input type="file" name="logo" accept="image/*"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500">
                <span class="text-red-800">{{ $errors->first('logo') }}</span>
            </div>

            <hr class="h-1 my-3">
            <div class="flex items-center">
                <button type="button" id="cancelEditModal" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">Batalkan</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">Simpan</button>
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
        return this.closest('tr').querySelector('td:nth-child(3)').textContent;
    }

    function getProvinsiLogo() {
        const logoImg = this.closest('tr').querySelector('td:nth-child(2) img');
        return logoImg ? logoImg.getAttribute('data-logo') : null;
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

    function onEditButtonClick() {
        showEditProvinsiModal();
    
        const name = document.getElementById('editProvinsiName');
        name.value = getProvinsiName.call(this);
        
        const currentLogo = document.querySelector('#currentLogo img');
        const logoUrl = getProvinsiLogo.call(this);

        if (logoUrl) {
            currentLogo.src = logoUrl;
            currentLogo.classList.remove('hidden');
        } else {
            currentLogo.classList.add('hidden');
        }
        
        const editProvinsiForm = document.getElementById('editProvinsiForm');
        editProvinsiForm.action = getUpdateProvinsiUrl.call(this);
    }

    function initializeEditProvinsiEvents() {
        setTimeout(function() {
            document.querySelectorAll('.edit-provinsi-btn')
                .forEach(button => button.addEventListener('click', onEditButtonClick));
        
            document.getElementById('cancelEditModal').addEventListener('click', closeEditProvinsiModal);
        }, 1000);
    }

    initializeEditProvinsiEvents();
</script>

@error('name', 'logo')
    <script>
        showEditProvinsiModal();
    </script>
@enderror