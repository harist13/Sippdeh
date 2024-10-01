@include('admin.layout.header')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<style>
    .hidden {
        display: none;
    }
    #tpsBtn, #suaraBtn, #paslonBtn {
        margin-right: 1px; /* Adjust the value as needed */
    }
    .btn-group button {
        margin: 0;
        padding: 10px 20px;
        border: none;
    }
    .btn-group button:not(:last-child) {
        border-right: 1px solid #d1d5db; /* Light gray color */
    }
    .btn-tambah-user {
        background-color: #008CFF;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
    }
    .btn-tambah-user:hover {
        background-color: #006bb3;
    }
    .btn-tambah-user i {
        margin-right: 8px;
    }

    .btn-aksi {
        background-color: #f56565;
        color: white;
        padding: 6px 12px;
        border-radius: 4px;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
    }
    .btn-aksi:hover {
        background-color: #e53e3e;
    }
</style>

<main class="container flex-grow px-4 mx-auto mt-6">
    <!-- Table Container -->
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
        <!-- Table Header -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                <button id="tpsBtn" class="px-4 py-2 bg-blue-700 text-white rounded-l-lg border-r border-gray-300">LIST USER</button>
                <button id="suaraBtn" class="px-4 py-2 bg-gray-200 text-gray-700 border-r border-gray-300">HISTORY</button>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Tambah User Button -->
                <button class="btn-tambah-user">
                    <i class="fas fa-plus"></i> Tambah User
                </button>

                <!-- Search Field -->
                <div class="relative">
                    <input type="text" placeholder="Cari User" class="bg-gray-100 rounded-md px-3 py-2 pl-8">
                    <i class="fas fa-search absolute left-2 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Table TPS (initially visible) -->
        <div id="tpsTable">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-700 text-white">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Username</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Wilayah</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows -->
                    <tr class="border-b">
                        <td class="px-4 py-2">001</td>
                        <td class="px-4 py-2">admin</td>
                        <td class="px-4 py-2">admin@kesbangpolkaltim.info</td>
                        <td class="px-4 py-2">Provinsi</td>
                        <td class="px-4 py-2">Admin</td>
                        <td class="px-4 py-2">
                            <i class="fas fa-edit text-blue-600 cursor-pointer"></i>
                            <i class="fas fa-trash-alt text-red-600 cursor-pointer"></i>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>

         <!-- Table HISTORY (initially hidden) -->
        <div id="suaraTable" class="hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-700 text-white">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Wilayah</th>
                        <th class="px-4 py-2 text-left">Terakhir Login</th>
                        <th class="px-4 py-2 text-left">Device</th>
                        <th class="px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="px-4 py-2">001</td>
                        <td class="px-4 py-2">admin@kesbangpolkaltim.info</td>
                        <td class="px-4 py-2">Provinsi</td>
                        <td class="px-4 py-2">9/21/2024 11:59 PM</td>
                        <td class="px-4 py-2">Laptop</td>
                        <td class="px-4 py-2">
                            <button class="btn-aksi">Keluar Akun</button>
                        </td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2">002</td>
                        <td class="px-4 py-2">operatorsmd@kesbangpolkaltim.info</td>
                        <td class="px-4 py-2">Samarinda</td>
                        <td class="px-4 py-2">9/21/2024 11:59 AM</td>
                        <td class="px-4 py-2">Komputer</td>
                        <td class="px-4 py-2">
                            <button class="btn-aksi">Keluar Akun</button>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    // JavaScript to toggle between TPS and SUARA tables
    document.getElementById('tpsBtn').addEventListener('click', function() {
        document.getElementById('tpsTable').classList.remove('hidden');
        document.getElementById('suaraTable').classList.add('hidden');
        this.classList.remove('bg-gray-200', 'text-gray-700');
        this.classList.add('bg-blue-700', 'text-white');
        document.getElementById('suaraBtn').classList.remove('bg-blue-700', 'text-white');
        document.getElementById('suaraBtn').classList.add('bg-gray-200', 'text-gray-700');
    });

    document.getElementById('suaraBtn').addEventListener('click', function() {
        document.getElementById('tpsTable').classList.add('hidden');
        document.getElementById('suaraTable').classList.remove('hidden');
        this.classList.remove('bg-gray-200', 'text-gray-700');
        this.classList.add('bg-blue-700', 'text-white');
        document.getElementById('tpsBtn').classList.remove('bg-blue-700', 'text-white');
        document.getElementById('tpsBtn').classList.add('bg-gray-200', 'text-gray-700');
    });
</script>

@include('admin.layout.footer')
