@extends('Superadmin.layout.app')

@push('scripts')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endpush

@push('styles')
    <style>
        .spinner {
            border: 4px solid transparent;
            border-top: 4px solid #3560A0; /* Customize color */
            border-right: 4px solid #3560A0;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.3); /* Adds a subtle shadow */
        }

        /* Keyframes for spinning animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Style untuk dropdown */
        select#dataLimit {
            min-width: 100px;
        }

        /* Style untuk pagination container */
        .pagination-container {
            @apply flex items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm: px-6;
        }

        /* Style untuk pagination info */
        .pagination-info {
            @apply text-sm text-gray-700;
        }

        /* Style untuk pagination links */
        .pagination {
            @apply relative z-0 inline-flex rounded-md shadow-sm -space-x-px;
        }

        /* Style untuk active page */
        .pagination .active {
            @apply z-10 bg-[#3560A0] border-[#3560A0] text-white;
        }

        /* Style untuk disabled page */
        .pagination .disabled {
            @apply cursor-not-allowed text-gray-500;
        }

        #tpsBtn,
        #suaraBtn,
        #paslonBtn {
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

            .btn-tambah-user,
            .search-field {
                width: 100%;
                margin-top: 0.5rem;
            }

            .modal-content {
                width: 90%;
                max-width: none;
            }
        }
    </style>
@endpush

@section('content')
    <main class="container flex-grow px-4 mx-auto mt-6">
        <div class="container mx-auto p-6 bg-white rounded-lg shadow-md mb-8">
            <!-- Table Header -->
            <div class="flex flex-wrap-mobile justify-between items-center mb-4">
                <div class="flex border border-gray-300 rounded-lg overflow-hidden w-full-mobile">
                    <button id="tpsBtn"
                        class="px-4 py-2 bg-[#3560A0] text-white rounded-l-lg border-r border-gray-300 flex-grow">LIST
                        USER</button>
                    <button id="suaraBtn"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-r-lg flex-grow">HISTORY</button>
                </div>

                <div class="flex flex-wrap-mobile items-center space-x-4 mt-4-mobile w-full-mobile">
                    <button class="btn-tambah-user w-full-mobile">
                        <i class="fas fa-plus"></i> Tambah User
                    </button>

                    <div class="relative">
                        <select id="roleFilter" class="bg-gray-100 rounded-md px-3 py-2 appearance-none pr-8 border border-gray-300 cursor-pointer">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <div class="relative w-full-mobile mt-4-mobile">
                        <input 
                            type="text" 
                            id="searchInput"
                            placeholder="Cari User" 
                            class="bg-gray-100 rounded-md px-3 py-2 pl-8 w-full"
                        >
                        <i class="fas fa-search absolute left-2 top-3 text-gray-400"></i>
                        <div id="searchLoading" class="absolute right-2 top-2 hidden">
                            <div class="spinner"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table USER (initially visible) -->
            <div id="tpsTable" class="overflow-x-auto">
                <table class="w-full shadow-md rounded-lg overflow-hidden border-collapse text-center">
                    <thead>
                        <tr class="bg-[#3560A0] text-white">
                            <th class="px-4 py-2 border-r border-white">NO</th>
                            <th class="px-4 py-2 border-r border-white">Username</th>
                            <th class="px-4 py-2 border-r border-white">Email</th>
                            <th class="px-4 py-2 border-r border-white">Wilayah</th>
                            <th class="px-4 py-2 border-r border-white">Role</th>
                            <th class="px-4 py-2 border-r border-white">Device Aktif</th>
                            <th class="px-4 py-2 border-r border-white">Status</th>
                            <th class="px-4 py-2 border-r border-white">Aksi</th>
                        </tr>
                    </thead>


                    <tbody class="bg-gray-100">
                        @foreach($users as $user)
                        <tr class="border-b">
                            <td class="px-4 py-2 border-r">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                            <td class="px-4 py-2 border-r">{{ $user->username }}</td>
                            <td class="px-4 py-2 border-r">{{ $user->email }}</td>
                            <td class="px-4 py-2 border-r">{{ $user->wilayah?->nama ?? '-' }}</td>
                            <td class="px-4 py-2 border-r">{{ $user->roles->first()->name ?? 'No Role' }}</td>
                            <td class="px-4 py-2 border-r">{{ $activeDevices[$user->id] ?? 0 }} / {{ $user->limit }}</td>
                            <td class="px-4 py-2 border-r">
                                @if($user->is_forced_logout)
                                <span class="text-red-600">Dikeluarkan</span>
                                @else
                                <span class="text-green-600">Aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border-r">
                                <i class="fas fa-edit text-blue-600 cursor-pointer mr-2 edit-user"
                                    data-id="{{ $user->id }}"></i>
                                <form action="{{ route('deleteUser', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="fas fa-trash-alt text-red-600 cursor-pointer"></button>
                                </form>
                                @if(!$user->is_forced_logout)
                                <form action="{{ route('forceLogout', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="fas fa-sign-out-alt text-yellow-600 cursor-pointer"></button>
                                </form>
                                @else
                                <form action="{{ route('reactivateUser', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="fas fa-user-check text-green-600 cursor-pointer"></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        <tr id="noDataTPS" class="hidden">
                            <td colspan="8" class="py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <p class="text-lg">Data yang dicari tidak ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
                <div class="mt-4">
                    {{ $users->links('vendor.pagination.custom') }}
                </div>
            </div>

            <!-- Table HISTORY (initially hidden) -->
            <div id="suaraTable" class="hidden overflow-x-auto">
                <table class="w-full shadow-md rounded-lg overflow-hidden border-collapse text-center">
                    <thead>
                        <tr class="bg-[#3560A0] text-white">
                            <th class="px-4 py-2 border-r border-white">NO</th>
                            <th class="px-4 py-2 border-r border-white">Email</th>
                            <th class="px-4 py-2 border-r border-white">Wilayah</th>
                            <th class="px-4 py-2 border-r border-white">Role</th>
                            <th class="px-4 py-2 border-r border-white">Terakhir Login</th>
                            <th class="px-4 py-2 border-r border-white">Device</th>
                            <th class="px-4 py-2 border-r border-white">IP Address</th>
                            <th class="px-4 py-2 border-r border-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-100">
                        @foreach($loginHistories as $history)
                        <tr class="border-b">
                            <td class="px-4 py-2 border-r">
                                {{ ($loginHistories->currentPage() - 1) * $loginHistories->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-2 border-r">{{ $history->user->email }}</td>
                            <td class="px-4 py-2 border-r">{{ $history->user->nama }}</td>
                            <td class="px-4 py-2 border-r">{{ $history->user->roles->first()->name ?? 'No Role' }}</td>
                            <td class="px-4 py-2 border-r">{{ $history->login_at }}</td>
                            <td class="px-4 py-2 border-r">{{ $history->user_agent }}</td>
                            <td class="px-4 py-2 border-r">{{ $history->ip_address }}</td>
                            <td class="px-4 py-2 border-r">
                                <form
                                    action="{{ route('forceLogoutDevice', ['userId' => $history->user_id, 'loginHistoryId' => $history->id]) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                                        Keluarkan
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr id="noDataSuara" class="hidden">
                            <td colspan="8" class="py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <p class="text-lg">Data yang dicari tidak ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $loginHistories->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Tambah User -->
    @include('Superadmin.user.tambah-user')

    <!-- Modal Edit User -->
    @include('Superadmin.user.edit-user')
@endsection

@push('scripts')
    @if(session('success'))
        <script>
            showSuccessMessage("{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            showErrorMessage("{{ session('error') }}");
        </script>
    @endif
@endpush

@push('scripts')
    <script>
        // Show success message
        function showSuccessMessage(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        }

        // Show error message
        function showErrorMessage(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message
            });
        }

        // Show confirmation dialog
        function showConfirmationDialog(title, text, confirmButtonText, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        }

        // JavaScript to toggle between TPS and SUARA tables
        document.getElementById('tpsBtn').addEventListener('click', function () {
            document.getElementById('tpsTable').classList.remove('hidden');
            document.getElementById('suaraTable').classList.add('hidden');
            this.classList.remove('bg-gray-200', 'text-gray-700');
            this.classList.add('bg-[#3560A0]', 'text-white');
            document.getElementById('suaraBtn').classList.remove('bg-[#3560A0]', 'text-white');
            document.getElementById('suaraBtn').classList.add('bg-gray-200', 'text-gray-700');
        });

        document.getElementById('suaraBtn').addEventListener('click', function () {
            document.getElementById('tpsTable').classList.add('hidden');
            document.getElementById('suaraTable').classList.remove('hidden');
            this.classList.remove('bg-gray-200', 'text-gray-700');
            this.classList.add('bg-[#3560A0]', 'text-white');
            document.getElementById('tpsBtn').classList.remove('bg-[#3560A0]', 'text-white');
            document.getElementById('tpsBtn').classList.add('bg-gray-200', 'text-gray-700');
        });

        // Show and hide the "Tambah User" modal
        document.querySelector('.btn-tambah-user').addEventListener('click', function () {
            document.getElementById('modalTambahUser').classList.remove('hidden');
        });

        document.getElementById('closeTambahUserModal').addEventListener('click', function () {
            document.getElementById('modalTambahUser').classList.add('hidden');
        });

        // Show and hide the "Edit User" modal
        document.querySelectorAll('.edit-user').forEach(function (editBtn) {
            editBtn.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                const userRow = this.closest('tr');
                const username = userRow.querySelector('td:nth-child(2)').textContent;
                const email = userRow.querySelector('td:nth-child(3)').textContent;
                const wilayah = userRow.querySelector('td:nth-child(4)').textContent;
                const role = userRow.querySelector('td:nth-child(5)').textContent;
                const deviceText = userRow.querySelector('td:nth-child(6)').textContent;
                const limit = deviceText.split('/')[1].trim();

                document.getElementById('editUsername').value = username;
                document.getElementById('editEmail').value = email;

                // Find and select the correct wilayah option
                const wilayahSelect = document.getElementById('editWilayah');
                for (let i = 0; i < wilayahSelect.options.length; i++) {
                    if (wilayahSelect.options[i].text === wilayah) {
                        wilayahSelect.selectedIndex = i;
                        break;
                    }
                }

                // If no match was found in the dropdown, add a new option with the current wilayah
                if (wilayahSelect.selectedIndex === 0 && wilayah !== "") {
                    const newOption = new Option(wilayah, "");
                    wilayahSelect.add(newOption);
                    wilayahSelect.selectedIndex = wilayahSelect.options.length - 1;
                }

                document.getElementById('editRole').value = role;

                document.getElementById('editUserForm').action = `/updateUser/${userId}`;
                document.getElementById('modalEditUser').classList.remove('hidden');
            });
        });

        // ... (rest of the JavaScript code remains unchanged)

        // Function to validate form
        function validateForm(form) {
            const username = form.querySelector('[name="username"]').value.trim();
            const email = form.querySelector('[name="email"]').value.trim();
            const role = form.querySelector('[name="role"]').value;
            const wilayah = form.querySelector('[name="wilayah"]').value;
            const password = form.querySelector('[name="password"]').value;
            const limit = form.querySelector('[name="limit"]').value;

            let isValid = true;
            let errorMessage = '';

            if (username.length < 3) {
                errorMessage += 'Username harus memiliki minimal 3 karakter.\n';
                isValid = false;
            }

            if (!isValidEmail(email)) {
                errorMessage += 'Email tidak valid.\n';
                isValid = false;
            }

            if (role === '') {
                errorMessage += 'Harap pilih role.\n';
                isValid = false;
            }

            if (wilayah === '') {
                errorMessage += 'Harap pilih wilayah.\n';
                isValid = false;
            }

            // For add user form, password is required
            if (form.id === 'tambahUserForm' && password.length < 6) {
                errorMessage += 'Password harus memiliki minimal 6 karakter.\n';
                isValid = false;
            }

            if (!isValid) {
                showErrorMessage(errorMessage);
            }

            return isValid;
        }

        document.getElementById('closeEditUserModal').addEventListener('click', function () {
            document.getElementById('modalEditUser').classList.add('hidden');
        });

        // Close modals when clicking outside
        window.addEventListener('click', function (event) {
            if (event.target === document.getElementById('modalTambahUser')) {
                document.getElementById('modalTambahUser').classList.add('hidden');
            }
            if (event.target === document.getElementById('modalEditUser')) {
                document.getElementById('modalEditUser').classList.add('hidden');
            }
        });




        // Handle delete user action
        document.querySelectorAll('.fa-trash-alt').forEach(function (deleteBtn) {
            deleteBtn.addEventListener('click', function (event) {
                event.preventDefault();
                const form = this.closest('form');
                showConfirmationDialog(
                    'Konfirmasi Hapus',
                    'Apakah Anda yakin ingin menghapus user ini?',
                    'Ya, Hapus',
                    function () {
                        form.submit();
                    }
                );
            });
        });




        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchLoading = document.getElementById('searchLoading');
            const tpsTable = document.getElementById('tpsTable');
            const suaraTable = document.getElementById('suaraTable');
            const roleFilter = document.getElementById('roleFilter');
            let searchTimeout;

            function showNoDataMessage(tableBody, message = 'Data yang dicari tidak ditemukan') {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="py-4 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <p class="text-lg font-medium">${message}</p>
                            </div>
                        </td>
                    </tr>
                `;
            }

            function updateContent(searchTerm, roleValue) {
                // Show loading
                searchLoading.classList.remove('hidden');
                
                // Build URL with search and role parameters
                const url = new URL(window.location.href);
                if (searchTerm) {
                    url.searchParams.set('search', searchTerm);
                } else {
                    url.searchParams.delete('search');
                }

                // Add role parameter
                if (roleValue) {
                    url.searchParams.set('role', roleValue);
                } else {
                    url.searchParams.delete('role');
                }

                // Make AJAX request
                fetch(url.toString(), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;

                    // Update TPS table content
                    const tpsTableBody = tpsTable.querySelector('tbody');
                    const newTpsRows = tempDiv.querySelectorAll('#tpsTable tbody tr');
                    
                    if (newTpsRows.length > 0) {
                        tpsTableBody.innerHTML = Array.from(newTpsRows)
                            .map(row => row.outerHTML)
                            .join('');
                    } else {
                        showNoDataMessage(tpsTableBody);
                    }

                    // Update Suara table content
                    if (suaraTable) {
                        const suaraTableBody = suaraTable.querySelector('tbody');
                        const newSuaraRows = tempDiv.querySelectorAll('#suaraTable tbody tr');
                        
                        if (newSuaraRows.length > 0) {
                            suaraTableBody.innerHTML = Array.from(newSuaraRows)
                                .map(row => row.outerHTML)
                                .join('');
                        } else {
                            showNoDataMessage(suaraTableBody);
                        }
                    }

                    // Update pagination if exists
                    const tpsPagination = tpsTable.querySelector('.mt-4');
                    const newTpsPagination = tempDiv.querySelector('#tpsTable .mt-4');
                    if (tpsPagination && newTpsPagination) {
                        tpsPagination.innerHTML = newTpsPagination.innerHTML;
                    }

                    if (suaraTable) {
                        const suaraPagination = suaraTable.querySelector('.mt-4');
                        const newSuaraPagination = tempDiv.querySelector('#suaraTable .mt-4');
                        if (suaraPagination && newSuaraPagination) {
                            suaraPagination.innerHTML = newSuaraPagination.innerHTML;
                        }
                    }

                    // Update URL without page refresh
                    window.history.pushState({}, '', url.toString());
                })
                .catch(error => {
                    console.error('Error fetching results:', error);
                    // Show error message in both tables
                    const tpsTableBody = tpsTable.querySelector('tbody');
                    const suaraTableBody = suaraTable ? suaraTable.querySelector('tbody') : null;
                    
                    showNoDataMessage(tpsTableBody, 'Terjadi kesalahan saat mencari data');
                    if (suaraTableBody) {
                        showNoDataMessage(suaraTableBody, 'Terjadi kesalahan saat mencari data');
                    }
                })
                .finally(() => {
                    // Hide loading
                    searchLoading.classList.add('hidden');
                });
            }

            // Search input event with 1 second delay
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                
                const searchTerm = this.value.trim();
                const roleValue = roleFilter.value; // Ambil nilai role saat ini
                searchTimeout = setTimeout(() => {
                    updateContent(searchTerm, roleValue);
                }, 1000);
            });

            // Role filter change event
            roleFilter.addEventListener('change', function() {
                const searchTerm = searchInput.value.trim();
                const roleValue = this.value;
                updateContent(searchTerm, roleValue);
            });

            // Clear search handler
            searchInput.addEventListener('search', function() {
                if (this.value === '') {
                    const roleValue = roleFilter.value;
                    updateContent('', roleValue);
                }
            });

            // Handle browser back/forward
            window.addEventListener('popstate', function(e) {
                const urlParams = new URLSearchParams(window.location.search);
                const searchTerm = urlParams.get('search') || '';
                const roleValue = urlParams.get('role') || '';
                
                searchInput.value = searchTerm;
                roleFilter.value = roleValue;
                
                updateContent(searchTerm, roleValue);
            });
        });


        // Function to validate email
        function isValidEmail(email) {
            const re =
                /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }


        // Add event listeners to forms
        document.getElementById('tambahUserForm').addEventListener('submit', function (event) {
            if (!validateForm(this)) {
                event.preventDefault();
            }
        });

        document.getElementById('editUserForm').addEventListener('submit', function (event) {
            if (!validateForm(this)) {
                event.preventDefault();
            }
        });

        // Update existing form submission handlers
        document.getElementById('tambahUserForm').addEventListener('submit', function (event) {
            event.preventDefault();
            if (validateForm(this)) {
                const form = this;
                fetch(form.action, {
                        method: form.method,
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessMessage('User berhasil ditambahkan');
                            document.getElementById('modalTambahUser').classList.add('hidden');
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showErrorMessage('Gagal menambahkan user');
                        }
                    })
                    .catch(error => {
                        showErrorMessage('Terjadi kesalahan');
                    });
            }
        });

        document.getElementById('editUserForm').addEventListener('submit', function (event) {
            event.preventDefault();
            if (validateForm(this)) {
                const form = this;
                fetch(form.action, {
                        method: form.method,
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessMessage('User berhasil diperbarui');
                            document.getElementById('modalEditUser').classList.add('hidden');
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showErrorMessage('Gagal memperbarui user');
                        }
                    })
                    .catch(error => {
                        showErrorMessage('Terjadi kesalahan');
                    });
            }
        });


        // Handle "Keluar Akun" button click
        document.querySelectorAll('.fa-sign-out-alt').forEach(function (logoutBtn) {
            logoutBtn.addEventListener('click', function (event) {
                event.preventDefault();
                const form = this.closest('form');
                showConfirmationDialog(
                    'Konfirmasi Keluar Akun',
                    'Apakah Anda yakin ingin mengeluarkan user ini dari semua device?',
                    'Ya, Keluarkan',
                    function () {
                        form.submit();
                    }
                );
            });
        });

        // Handle "Aktifkan Akun" button click
        document.querySelectorAll('.fa-user-check').forEach(function (activateBtn) {
            activateBtn.addEventListener('click', function (event) {
                event.preventDefault();
                const form = this.closest('form');
                showConfirmationDialog(
                    'Konfirmasi Aktifkan Akun',
                    'Apakah Anda yakin ingin mengaktifkan kembali user ini?',
                    'Ya, Aktifkan',
                    function () {
                        form.submit();
                    }
                );
            });
        });

        document.getElementById('dataLimit').addEventListener('change', function () {
            const url = new URL(window.location.href);
            url.searchParams.set('perPage', this.value);
            url.searchParams.set('page', 1); // Reset ke halaman pertama saat mengubah jumlah data
            window.location.href = url.toString();
        });

        // Set selected option berdasarkan URL
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const perPage = urlParams.get('perPage') || '10';
            document.getElementById('dataLimit').value = perPage;
        });
    </script>
@endpush