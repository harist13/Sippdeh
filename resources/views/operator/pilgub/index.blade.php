@include('operator.layout.header')
<main class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-[20px] p-2 mb-8 shadow-lg">
        <div class="container mx-auto p-7">
            <div class="mb-4 flex space-x-4">
                <button class="bg-gray-300 text-gray-700 py-2 px-4 rounded">Pemilihan Gubernur</button>
                <button class="bg-green-500 text-white py-2 px-4 rounded flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan Data
                </button>
                <button class="bg-red-500 text-white py-2 px-4 rounded flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal Ubah Data
                </button>
                <button class="bg-blue-500 text-white py-2 px-4 rounded flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Ubah Data
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 50px;">NO</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 60px;">
                                <div class="w-5 h-5 mx-auto bg-white rounded"></div>
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
                    <tbody>
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
                                <span class="bg-green-400 text-white py-1 px-2 rounded text-xs">90%</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <svg class="w-5 h-5 text-blue-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </td>
                        </tr>
                        <!-- Add more rows here following the same pattern -->
                    </tbody>
                </table>
            </div>

            
        </div>
    </div>
</main>
@include('operator.layout.footer')