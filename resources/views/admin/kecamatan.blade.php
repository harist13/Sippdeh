@include('admin.layout.header')
<main class="container flex-grow px-4 mx-auto mt-6">
<div class="container mx-auto mt-8">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-2">
            <span class="text-lg font-bold"><i class="fa fa-map-marker"></i> Kecamatan</span>
        </div>
        <div class="flex space-x-2">
            <!-- Dropdown Pilih Kab/Kota -->
            <div class="relative">
                <button id="dropdownButton" class="bg-gray-100 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg flex items-center">
                    Pilih Kab/Kota <i class="fa fa-chevron-down ml-2"></i>
                </button>
                <div id="dropdownMenu" class="absolute mt-2 w-full rounded-lg shadow-lg bg-white z-10 hidden">
                    <ul class="py-1 text-gray-700">
                        <li class="px-4 py-2 hover:bg-gray-100">Samarinda</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Balikpapan</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Bontang</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Kutai Kartanegara</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Kutai Timur</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Kutai Barat</li>
                    </ul>
                </div>
            </div>
            <button class="bg-blue-500 text-white py-2 px-4 rounded-lg">+ Tambah Kecamatan</button>
            <input type="text" placeholder="Cari Kelurahan" class="border border-gray-300 rounded-lg px-4 py-2">
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal text-sm">
            <thead>
                <tr>
                    <th class="px-4 py-3 bg-blue-600 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-4 py-3 bg-blue-600 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        Nama Kecamatan
                    </th>
                    <th class="px-4 py-3 bg-blue-600 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        Kabupaten/Kota
                    </th>
                    <th class="px-4 py-3 bg-blue-600 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                <!-- Contoh data 1 -->
                <tr>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        001
                    </td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        Palaran
                    </td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        Samarinda
                    </td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                    <a href="#" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></a>
                        <a href="#" class="text-red-600 hover:text-red-900 ml-3"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <!-- Contoh data 2 -->
                <tr>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        002
                    </td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        Samarinda Seberang
                    </td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        Samarinda
                    </td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        <a href="#" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></a>
                        <a href="#" class="text-red-600 hover:text-red-900 ml-3"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</main>
<script>
    document.getElementById('dropdownButton').addEventListener('click', function() {
        var menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    });
</script>

@include('admin.layout.footer')
