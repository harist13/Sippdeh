<!-- Edit TPS Modal -->
<div id="editTPSModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="editTPSForm" action="#" method="POST">
            @method('PUT')
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Edit TPS</h3>

			{{-- Nama TPS --}}
            <div class="mb-3">
                <label for="editTPSName" class="mb-1 block">Nama</label>
                <input type="text" id="editTPSName" name="name"
                    class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nama TPS" required>
            </div>

            {{-- DPT --}}
            <div class="mb-3">
                <label for="editTPSDPT" class="mb-1 block">DPT</label>
                <input type="number" id="editTPSDPT" name="dpt"
                    class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

			{{-- Kelurahan --}}
            <div class="mb-3">
                <label for="editTPSKelurahanId" class="my-1 block">Kelurahan</label>
                <select id="editTPSKelurahanId" name="kelurahan_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                    @foreach ($kelurahan as $kel)
                        <option value="{{ $kel->id }}">{{ $kel->nama }}</option>
                    @endforeach
                </select>
            </div>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="closeEditTps" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" id="confirmEditTPS" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Simpan
				</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        function showEditTPSModal() {
            const editTPSModal = document.getElementById('editTPSModal');
            editTPSModal.classList.remove('hidden');
        }

        function closeEditTPSModal() {
            const editTPSModal = document.getElementById('editTPSModal');
            editTPSModal.classList.add('hidden');
        }
        
        function getId() {
            return this.closest('tr').dataset.id;
        }

        function getName() {
            return this.closest('tr').dataset.nama;
        }

        function getKelurahanId() {
            return this.closest('tr').dataset.kelurahanId;
        }

        function getDpt() {
            return this.closest('tr').dataset.dpt;
        }

        function getUpdateUrl() {
            const tpsId = getId.call(this);
            const tpsUpdateRoute = `{{ route('tps.update', ['tp' => '__tp__']) }}`;
            const tpsUpdateUrl = tpsUpdateRoute.replace('__tp__', tpsId);

            return tpsUpdateUrl;
        }

        function onEditTpsButtonClick() {
            const editTPSName = document.getElementById('editTPSName');
            editTPSName.value = getName.call(this);

            const editTPSDPT = document.getElementById('editTPSDPT');
            editTPSDPT.value = getDpt.call(this);

            const editTPSKelurahanId = document.getElementById('editTPSKelurahanId');
            editTPSKelurahanId.value = getKelurahanId.call(this);

            const editTPSForm = document.getElementById('editTPSForm');
            editTPSForm.action = getUpdateUrl.call(this);

            showEditTPSModal();
        }

        document.querySelectorAll('.edit-tps')
            .forEach(button => button.addEventListener('click', onEditTpsButtonClick));

        document.getElementById('closeEditTps').addEventListener('click', closeEditTPSModal);
    </script>
@endpush