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


<!-- Modal Tambah User -->
<div id="modalTambahUser" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Tambah User</h2>
            <button id="closeTambahUserModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="tambahUserForm">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="namaLengkap" class="block text-sm">Nama</label>
                    <input type="text" id="namaLengkap" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="Nama Lengkap">
                </div>
                <div>
                    <label for="email" class="block text-sm">Email</label>
                    <input type="email" id="email" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="Email">
                </div>
                <div>
                    <label for="role" class="block text-sm">Role</label>
                    <select id="role" class="w-full bg-gray-100 px-3 py-2 rounded-md">
                        <option>Pilih Role</option>
                        <option>Admin</option>
                        <option>Operator</option>
                    </select>
                </div>
                <div>
                    <label for="password" class="block text-sm">Password</label>
                    <input type="password" id="password" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="Password">
                </div>
                <div class="col-span-2">
                    <label for="kota" class="block text-sm">Kota</label>
                    <select id="kota" class="w-full bg-gray-100 px-3 py-2 rounded-md">
                        <option>Pilih Kab/Kota</option>
                        <!-- Populate with options dynamically -->
                    </select>
                </div>
            </div>
            <div class="mt-4 text-right">
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-md">Tambah User</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User (can be similar to Tambah User modal) -->
<div id="modalEditUser" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Edit User</h2>
            <button id="closeEditUserModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editUserForm">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="editNamaLengkap" class="block text-sm">Nama</label>
                    <input type="text" id="editNamaLengkap" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="Nama Lengkap">
                </div>
                <div>
                    <label for="editEmail" class="block text-sm">Email</label>
                    <input type="email" id="editEmail" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="Email">
                </div>
                <div>
                    <label for="editRole" class="block text-sm">Role</label>
                    <select id="editRole" class="w-full bg-gray-100 px-3 py-2 rounded-md">
                        <option>Pilih Role</option>
                        <option>Admin</option>
                        <option>Operator</option>
                    </select>
                </div>
                <div>
                    <label for="editPassword" class="block text-sm">Password</label>
                    <input type="password" id="editPassword" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="Password">
                </div>
                <div class="col-span-2">
                    <label for="editKota" class="block text-sm">Kota</label>
                    <select id="editKota" class="w-full bg-gray-100 px-3 py-2 rounded-md">
                        <option>Pilih Kab/Kota</option>
                        <!-- Populate with options dynamically -->
                    </select>
                </div>
            </div>
            <div class="mt-4 text-right">
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>


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


<script>
    // Show and hide the "Tambah User" modal
document.querySelector('.btn-tambah-user').addEventListener('click', function () {
    document.getElementById('modalTambahUser').classList.remove('hidden');
});

document.getElementById('closeTambahUserModal').addEventListener('click', function () {
    document.getElementById('modalTambahUser').classList.add('hidden');
});

// Show and hide the "Edit User" modal (adjust this to your edit button)
document.querySelectorAll('.fa-edit').forEach(function (editBtn) {
    editBtn.addEventListener('click', function () {
        document.getElementById('modalEditUser').classList.remove('hidden');
        // Optionally, populate modal with user data for editing
    });
});

document.getElementById('closeEditUserModal').addEventListener('click', function () {
    document.getElementById('modalEditUser').classList.add('hidden');
});

</script>
@include('admin.layout.footer')
