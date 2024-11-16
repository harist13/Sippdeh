<!-- Edit Kabupaten/Kota Modal -->
<div id="editKabupatenModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="editKabupatenForm" action="#" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Edit Kabupaten/Kota</h3>

			{{-- Nama kabupaten --}}
            <div class="mb-3">
                <label for="editKabupatenName" class="mb-1 block">Nama</label>
                <input type="text" id="editKabupatenName" name="name" placeholder="Nama kabupaten" required
                    class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                {{-- <span class="text-red-800">{{ $errors->first('edit_name') }}</span> --}}
            </div>

            {{-- Logo --}}
            <div class="mb-3">
                <label for="editKabupatenLogo" class="mb-1 block">Logo</label>
                <div class="mb-2">
                    <img id="currentLogo" src="" alt="Current Logo" class="w-32 h-32 object-contain mx-auto hidden">
                </div>
                <input type="file" id="editKabupatenLogo" name="logo" accept="image/*"
                    class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                {{-- <span class="text-red-800 block">{{ $errors->first('edit_logo') }}</span> --}}
                <span class="text-gray-500 text-sm">Format: JPG, JPEG, PNG (max 2MB)</span>
            </div>

			{{-- Provinsi --}}
            <div class="mb-3">
                <label for="editKabupatenProvinsiId" class="my-1 block">Provinsi</label>
                <select id="editKabupatenProvinsiId" name="provinsi_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                    @foreach ($provinsi as $prov)
                        <option value="{{ $prov->id }}">{{ $prov->nama }}</option>
                    @endforeach
                </select>
                {{-- <span class="text-red-800">{{ $errors->first('provinsi_id') }}</span> --}}
            </div>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="closeEditKabupatenModal" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" id="confirmEditKabupaten" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Simpan
				</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        function getKabupatenData() {
            const kabupatenCell = this.closest('tr');
            return {
                id: kabupatenCell.dataset.id,
                nama: kabupatenCell.dataset.nama,
                logo: kabupatenCell.dataset.logo,
                provinsiId: kabupatenCell.dataset.provinsiId
            };
        }

        function showEditKabupatenModal() {
            const editKabupatenModal = document.getElementById('editKabupatenModal');
            editKabupatenModal.classList.remove('hidden');
        }

        function closeEditKabupatenModal() {
            const editKabupatenModal = document.getElementById('editKabupatenModal');
            editKabupatenModal.classList.add('hidden');

            // Reset form
            document.getElementById('editKabupatenLogo').value = '';
        }

        function getUpdateKabupatenUrl() {
            const kabupatenData = getKabupatenData.call(this);
            const kabupatenId = kabupatenData.id;

            const kabupatenUpdateRoute = `{{ route('kabupaten.update', ['kabupaten' => '__kabupaten__']) }}`;
            const kabupatenUpdateUrl = kabupatenUpdateRoute.replace('__kabupaten__', kabupatenId);

            return kabupatenUpdateUrl;
        }

        function onEditButtonClick() {
            const kabupatenData = getKabupatenData.call(this);

            // Set form action URL
            const editKabupatenForm = document.getElementById('editKabupatenForm');
            editKabupatenForm.action = getUpdateKabupatenUrl.call(this);

            // Set nama kabupaten
            const editKabupatenName = document.getElementById('editKabupatenName');
            editKabupatenName.value = kabupatenData.nama;

            // Set provinsi id
            const editKabupatenProvinsiId = document.getElementById('editKabupatenProvinsiId');
            editKabupatenProvinsiId.value = kabupatenData.provinsiId;

            // Update logo preview
            const currentLogo = document.getElementById('currentLogo');
            if (kabupatenData.logo) {
                currentLogo.src = kabupatenData.logo;
                currentLogo.classList.remove('hidden');
            } else {
                currentLogo.classList.add('hidden');
            }

            showEditKabupatenModal();
        }

        function onLogoChange(e) {
            const currentLogo = document.getElementById('currentLogo');

            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    currentLogo.src = e.target.result;
                    currentLogo.classList.remove('hidden');
                }
                reader.readAsDataURL(this.files[0]);
            }
        }

        document.querySelectorAll('.edit-kabupaten')
            .forEach(button => button.addEventListener('click', onEditButtonClick));
        
        document.getElementById('editKabupatenLogo').addEventListener('change', onLogoChange);
        
        document.getElementById('closeEditKabupatenModal').addEventListener('click', closeEditKabupatenModal);
    </script>
@endpush
