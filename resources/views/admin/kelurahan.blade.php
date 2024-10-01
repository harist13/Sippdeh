@include('admin.layout.header')
<main class="container flex-grow px-4 mx-auto mt-6">
<div class="container mx-auto mt-8">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-2">
            <span class="text-lg font-bold"><i class="fas fa-map-marker-alt"></i> Kelurahan</span>
        </div>
        <div class="flex space-x-2">
            <!-- Dropdown Pilih Kab/Kota -->
            <div class="relative">
                <button id="dropdownButton" class="bg-gray-100 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg flex items-center">
                    Pilih Kab/Kota <i class="fas fa-chevron-right ml-2"></i>
                </button>
                <div id="dropdownMenu" class="absolute mt-2 w-full rounded-lg shadow-lg bg-white z-10 hidden">
                    <ul class="py-1 text-gray-700">
                        <li class="px-4 py-2 hover:bg-gray-100">Samarinda</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Loa Janan Ilir</li>
                        <!-- Add more cities as needed -->
                    </ul>
                </div>
            </div>
            <button class="bg-blue-500 text-white py-2 px-4 rounded-lg">+ Tambah Kelurahan</button>
            <div class="relative">
                <input type="text" placeholder="Cari Kelurahan" class="border border-gray-300 rounded-lg pl-10 pr-4 py-2">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal text-sm">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Kabupaten/Kota
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Kecamatan
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Kelurahan
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">001</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">Samarinda</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">Palaran</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">Bantuas</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        <a href="#" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></a>
                        <a href="#" class="text-red-600 hover:text-red-900 ml-3"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
                <!-- Repeat the above <tr> structure for each row, updating the data accordingly -->
                <!-- Example of the last row -->
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">010</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">Loa Janan Ilir</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">Samarinda Seberang</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">Sungai Keledang</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        <a href="#" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></a>
                        <a href="#" class="text-red-600 hover:text-red-900 ml-3"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</main>
<style>
    .container {
        max-width: 1200px;
    }
    .rounded-lg {
        border-radius: 0.5rem;
    }
    .shadow-md {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    table tr:nth-child(even) {
        background-color: #f8f8f8;
    }
</style>

<script>
    document.getElementById('dropdownButton').addEventListener('click', function() {
        var menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    });
</script>

@include('admin.layout.footer')