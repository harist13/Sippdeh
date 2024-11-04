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
                    <input type="text" id="username" name="username" class="w-full bg-gray-100 px-3 py-2 rounded-md"
                        placeholder="Username" required>
                </div>
                <div>
                    <label for="email" class="block text-sm">Email</label>
                    <input type="email" id="email" name="email" class="w-full bg-gray-100 px-3 py-2 rounded-md"
                        placeholder="Email" required>
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
                    <input type="password" id="password" name="password" class="w-full bg-gray-100 px-3 py-2 rounded-md"
                        placeholder="Password" required>
                </div>
                <div class="col-span-1 sm:col-span-2">
                    <label for="wilayah" class="block text-sm">Wilayah</label>
                    <select id="wilayah" name="wilayah" class="w-full bg-gray-100 px-3 py-2 rounded-md" required>
                        <option value="">Pilih Wilayah</option>
                        @foreach($kabupatens as $kabupaten)
                        <option value="{{ $kabupaten->id }}">{{ $kabupaten->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 text-right">
                <button type="submit" class="bg-[#3560A0] text-white px-4 py-2 rounded-md">Tambah User</button>
            </div>
        </form>
    </div>
</div>