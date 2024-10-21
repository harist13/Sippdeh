@include('operator.layout.header')
<main class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-[20px] p-4 mb-8 shadow-lg">
        <div class="container mx-auto p-7">
          <div class="mb-4 flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center">
                <button class="bg-gray-300 text-gray-700 py-3 px-5 rounded text-sm font-medium w-full sm:w-auto">Pemilihan Gubernur</button>
                <button class="bg-green-500 text-white py-3 px-5 rounded flex items-center justify-center text-sm font-medium w-full sm:w-auto">
                    <img src="{{ asset('assets/icon/Unread.png') }}" alt="Unread Icon" class="w-4 h-4 mr-2">
                    Simpan Perubahan Data
                </button>
                <button class="bg-red-500 text-white py-3 px-5 rounded flex items-center justify-center text-sm font-medium w-full sm:w-auto">
                    <img src="{{ asset('assets/icon/close.png') }}" alt="Unread Icon" class="w-4 h-4 mr-2">
                    Batal Ubah Data
                </button>
                <button class="bg-blue-500 text-white py-3 px-5 rounded flex items-center justify-center text-sm font-medium w-full sm:w-auto">
                    <img src="{{ asset('assets/icon/plus.png') }}" alt="Unread Icon" class="w-4 h-4 mr-2">
                    Ubah Data
                </button>
            </div>
            <div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center">
                <div class="relative w-full sm:w-auto">
                    <input type="text" placeholder="Search" class="bg-gray-100 border border-gray-300 text-gray-700 py-3 px-5 pr-8 rounded text-sm w-full">
                    <svg class="w-4 h-4 text-gray-500 absolute right-2 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button class="bg-gray-300 text-gray-700 py-3 px-2 rounded text-sm font-medium w-full sm:w-auto">{{ $userWilayah }}</button>
            </div>
        </div>

            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[#3560A0] text-white">
                                <tr>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 50px;">NO</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 60px;">
                                        <input type="checkbox" id="selectAll" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200">
                                    </th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 100px;">Keterangan</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 120px;">Kelurahan</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 80px;">TPS</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 80px;">DPT</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 160px;">
                                        Rahmad Mas'ud/<br>Bagus Susetyo
                                    </th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 180px;">
                                        Rendi Susiswo Ismail<br>Eddy Sunardi Darmawan
                                    </th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 100px;">Suara Sah</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 120px;">Suara Tidak Sah</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 140px;">Jumlah Pengguna<br>Hak Pilih</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 140px;">Jumlah Pengguna<br>Tidak Pilih</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 100px;">Suara Masuk</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 100px;">Persentase</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                                <tr class="border-b text-center">
                                    <td class="py-3 px-4">01</td>
                                    <td class="py-3 px-4">
                                        <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="bg-red-100 text-red-800 py-1 px-2 rounded-full text-xs">Belum</span>
                                    </td>
                                    <td class="py-3 px-4">Palaran</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="bg-green-400 text-white py-1 px-7 rounded text-xs">90%</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <img src="{{ asset('assets/icon/pen.png')}}" class="w-5 h-5 ml-2">
                                    </td>
                                </tr>
                                <tr class="border-b text-center">
                                    <td class="py-3 px-4">02</td>
                                    <td class="py-3 px-4">
                                        <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="bg-blue-100 text-blue-800 py-1 px-2 rounded-full text-xs">Sudah</span>
                                    </td>
                                    <td class="py-3 px-4">Palaran</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4">55.345</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="bg-green-400 text-white py-1 px-7 rounded text-xs">90%</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <img src="{{ asset('assets/icon/pen.png')}}" class="w-5 h-5 ml-2">
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@include('operator.layout.footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');

    selectAllCheckbox.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });
});
</script>