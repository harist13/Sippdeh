@include('admin.layout.header')
<main class="container flex-grow px-4 mx-auto mt-6">
<div class="container mx-auto mt-8">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-2">
            <span class="text-lg font-bold"><i class="fa fa-user-group"></i> TPS</span>
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
                    </ul>
                </div>
            </div>
            <button class="bg-blue-500 text-white py-2 px-4 rounded-lg">+ Tambah Calon</button>
            <input type="text" placeholder="Cari" class="border border-gray-300 rounded-lg px-4 py-2">
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
                        Nama Pasangan Calon
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Kabupaten/Kota
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Foto
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        001
                    </td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        Andi Harun / Saefuddin Zuhri
                    </td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        Samarinda
                    </td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        Gambar belum upload
                    </td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        <a href="#" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></a>
                        <a href="#" class="text-red-600 hover:text-red-900 ml-3"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <!-- Add more rows here -->
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
</style>

<script>
    document.getElementById('dropdownButton').addEventListener('click', function() {
        var menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    });
</script>

@include('admin.layout.footer')