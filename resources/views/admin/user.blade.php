@include('admin.layout.header')

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<style>
    
    #tpsBtn, #suaraBtn, #paslonBtn {
        margin-right: 1px;
    }
    .btn-group button {
        margin: 0;
        padding: 10px 20px;
        border: none;
    }
    .btn-group button:not(:last-child) {
        border-right: 1px solid #d1d5db;
    }
    .btn-tambah-user {
        background-color: #008CFF;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
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
    @media (max-width: 640px) {
        .container {
            padding: 1rem;
        }
        .flex-wrap-mobile {
            flex-wrap: wrap;
        }
        .w-full-mobile {
            width: 100%;
        }
        .mt-4-mobile {
            margin-top: 1rem;
        }
        .overflow-x-auto {
            overflow-x: auto;
        }
        .btn-tambah-user, .search-field {
            width: 100%;
            margin-top: 0.5rem;
        }
        .modal-content {
            width: 90%;
            max-width: none;
        }
    }
</style>

<main class="container flex-grow px-4 mx-auto mt-6">
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
        <!-- Table Header -->
        <div class="flex flex-wrap-mobile justify-between items-center mb-4">
            <div class="flex border border-gray-300 rounded-lg overflow-hidden w-full-mobile">
                <button id="tpsBtn" class="px-4 py-2 bg-blue-700 text-white rounded-l-lg border-r border-gray-300 flex-grow">LIST USER</button>
                <button id="suaraBtn" class="px-4 py-2 bg-gray-200 text-gray-700 border-r border-gray-300 flex-grow">HISTORY</button>
            </div>

            <div class="flex flex-wrap-mobile items-center space-x-4 mt-4-mobile w-full-mobile">
                <button class="btn-tambah-user w-full-mobile">
                    <i class="fas fa-plus"></i> Tambah User
                </button>

                <div class="relative w-full-mobile mt-4-mobile">
                    <input type="text" placeholder="Cari User" class="bg-gray-100 rounded-md px-3 py-2 pl-8 w-full">
                    <i class="fas fa-search absolute left-2 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Table TPS (initially visible) -->
        <div id="tpsTable" class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-700 text-white">
                        <th class="px-4 py-2 text-left">NO</th>
                        <th class="px-4 py-2 text-left">Username</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Wilayah</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                    @foreach($users as $user)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $user->username }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->wilayah }}</td>
                        <td class="px-4 py-2">{{ $user->roles->first()->name ?? 'No Role' }}</td>
                        <td class="px-4 py-2">
                            @if($user->is_forced_logout)
                                <span class="text-red-600">Dikeluarkan</span>
                            @else
                                <span class="text-green-600">Aktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <i class="fas fa-edit text-blue-600 cursor-pointer mr-2 edit-user" data-id="{{ $user->id }}"></i>
                            <form action="{{ route('deleteUser', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="fas fa-trash-alt text-red-600 cursor-pointer" onclick="return confirm('Are you sure you want to delete this user?')"></button>
                            </form>
                            @if(!$user->is_forced_logout)
                                <form action="{{ route('forceLogout', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="fas fa-sign-out-alt text-yellow-600 cursor-pointer" onclick="return confirm('Are you sure you want to force logout this user?')"></button>
                                </form>
                            @else
                                <form action="{{ route('reactivateUser', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="fas fa-user-check text-green-600 cursor-pointer" onclick="return confirm('Are you sure you want to reactivate this user?')"></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

       <!-- Table HISTORY (initially hidden) -->
        <div id="suaraTable" class="hidden overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-700 text-white">
                        <th class="px-4 py-2 text-left">NO</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Wilayah</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2 text-left">Terakhir Login</th>
                        <th class="px-4 py-2 text-left">Device</th>
                        <th class="px-4 py-2 text-left">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                    @foreach($loginHistories as $history)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $history->user->email }}</td>
                        <td class="px-4 py-2">{{ $history->user->wilayah }}</td>
                        <td class="px-4 py-2">{{ $history->user->roles->first()->name ?? 'No Role' }}</td>
                        <td class="px-4 py-2">{{ $history->login_at }}</td>
                        <td class="px-4 py-2">{{ $history->user_agent }}</td>
                        <td class="px-4 py-2">{{ $history->ip_address }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal Tambah User -->
<div id="modalTambahUser" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full sm:w-2/3 md:w-1/2 lg:w-1/3 max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Tambah User</h2>
            <button id="closeTambahUserModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="tambahUserForm" action="{{ route('storeUser') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="username" class="block text-sm">Username</label>
                    <input type="text" id="username" name="username" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="Username" required>
                </div>
                <div>
                    <label for="email" class="block text-sm">Email</label>
                    <input type="email" id="email" name="email" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="Email" required>
                </div>
                <div>
                    <label for="role" class="block text-sm">Role</label>
                    <select id="role" name="role" class="w-full bg-gray-100 px-3 py-2 rounded-md" required>
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="password" class="block text-sm">Password</label>
                    <input type="password" id="password" name="password" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="Password" required>
                </div>
                <div class="col-span-1 sm:col-span-2">
                    <label for="wilayah" class="block text-sm">Wilayah</label>
                    <input type="text" id="wilayah" name="wilayah" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="Wilayah" required>
                </div>
            </div>
            <div class="mt-4 text-right">
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-md">Tambah User</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="modalEditUser" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full sm:w-2/3 md:w-1/2 lg:w-1/3 max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Edit User</h2>
            <button id="closeEditUserModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editUserForm" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="editUsername" class="block text-sm">Username</label>
                    <input type="text" id="editUsername" name="username" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="Username" required>
                </div>
                <div>
                    <label for="editEmail" class="block text-sm">Email</label>
                    <input type="email" id="editEmail" name="email" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="Email" required>
                </div>
                <div>
                    <label for="editRole" class="block text-sm">Role</label>
                    <select id="editRole" name="role" class="w-full bg-gray-100 px-3 py-2 rounded-md" required>
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="editPassword" class="block text-sm">Password (Leave blank to keep current)</label>
                    <input type="password" id="editPassword" name="password" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="New Password">
                </div>
                <div class="col-span-1 sm:col-span-2">
                    <label for="editWilayah" class="block text-sm">Wilayah</label>
                    <input type="text" id="editWilayah" name="wilayah" class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="Wilayah" required>
                </div>
            </div>
            <div class="mt-4 text-right">
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>


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
            // You can add code here to fetch user data and populate the form
        });
    });

    document.getElementById('closeEditUserModal').addEventListener('click', function () {
        document.getElementById('modalEditUser').classList.add('hidden');
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === document.getElementById('modalTambahUser')) {
            document.getElementById('modalTambahUser').classList.add('hidden');
        }
        if (event.target === document.getElementById('modalEditUser')) {
            document.getElementById('modalEditUser').classList.add('hidden');
        }
    });


    // Form submission for Edit User
    document.getElementById('editUserForm').addEventListener('submit', function(event) {
        event.preventDefault();
        // Add your code here to handle form submission
        // You can use AJAX to send the data to the server
        console.log('Edit User form submitted');
        // Close the modal after submission
        document.getElementById('modalEditUser').classList.add('hidden');
    });

    // Handle delete user action
    document.querySelectorAll('.fa-trash-alt').forEach(function(deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this user?')) {
                // Add your code here to handle user deletion
                console.log('User deleted');
            }
        });
    });

    // Handle "Keluar Akun" button click
    document.querySelectorAll('.btn-aksi').forEach(function(logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to log out this user?')) {
                // Add your code here to handle user logout
                console.log('User logged out');
            }
        });
    });

    // Search functionality
    document.querySelector('input[placeholder="Cari User"]').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#tpsTable tbody tr, #suaraTable tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    

    // Edit User
    document.querySelectorAll('.edit-user').forEach(function (editBtn) {
        editBtn.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            const userRow = this.closest('tr');
            const username = userRow.querySelector('td:nth-child(2)').textContent;
            const email = userRow.querySelector('td:nth-child(3)').textContent;
            const wilayah = userRow.querySelector('td:nth-child(4)').textContent;
            const role = userRow.querySelector('td:nth-child(5)').textContent;

            document.getElementById('editUsername').value = username;
            document.getElementById('editEmail').value = email;
            document.getElementById('editWilayah').value = wilayah;
            document.getElementById('editRole').value = role;
            
            document.getElementById('editUserForm').action = `/updateUser/${userId}`;
            document.getElementById('modalEditUser').classList.remove('hidden');
        });
    });

    // Edit User Form Submission
    document.getElementById('editUserForm').addEventListener('submit', function(event) {
        event.preventDefault();
        this.submit();
    });


    // Tambahkan fungsi untuk menampilkan SweetAlert
    function showAlert(icon, title, text) {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
        });
    }

    // Modifikasi form submission untuk Tambah User
    document.getElementById('tambahUserForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Tambah User',
            text: "Apakah Anda yakin ingin menambahkan user ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, tambahkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Modifikasi form submission untuk Edit User
    document.getElementById('editUserForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Edit User',
            text: "Apakah Anda yakin ingin menyimpan perubahan?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Modifikasi delete user action
    document.querySelectorAll('.fa-trash-alt').forEach(function(deleteBtn) {
        deleteBtn.addEventListener('click', function(event) {
            event.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Hapus User',
                text: "Apakah Anda yakin ingin menghapus user ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Modifikasi force logout action
    document.querySelectorAll('.fa-sign-out-alt').forEach(function(logoutBtn) {
        logoutBtn.addEventListener('click', function(event) {
            event.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Keluarkan User',
                text: "Apakah Anda yakin ingin mengeluarkan user ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, keluarkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Modifikasi reactivate user action
    document.querySelectorAll('.fa-user-check').forEach(function(reactivateBtn) {
        reactivateBtn.addEventListener('click', function(event) {
            event.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Aktifkan Kembali User',
                text: "Apakah Anda yakin ingin mengaktifkan kembali user ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, aktifkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });


</script>

<script>
    @if(session('success'))
        showAlert('success', 'Berhasil!', '{{ session('success') }}');
    @endif

    @if(session('error'))
        showAlert('error', 'Gagal!', '{{ session('error') }}');
    @endif
</script>

@include('admin.layout.footer')