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
                    <input type="text" id="editUsername" name="username" class="w-full bg-gray-100 px-3 py-2 rounded-md"
                        placeholder="Username" required>
                </div>
                <div>
                    <label for="editEmail" class="block text-sm">Email</label>
                    <input type="email" id="editEmail" name="email" class="w-full bg-gray-100 px-3 py-2 rounded-md"
                        placeholder="Email" required>
                </div>
                <div>
                    <label for="editRole" class="block text-sm">Role</label>
                    <input type="text" id="editRole" name="role" value="{{ $user->roles->first()->name ?? '' }}" 
                        class="w-full bg-gray-100 px-3 py-2 rounded-md cursor-not-allowed" 
                        readonly>
                </div>
                <div>
                    <label for="editPassword" class="block text-sm">Password (Leave blank to keep current)</label>
                    <input type="password" id="editPassword" name="password"
                        class="w-full bg-gray-100 px-3 py-2 rounded-md" placeholder="New Password">
                </div>
                <div>
                    <label for="editWilayah" class="block text-sm">Wilayah</label>
                    <select id="editWilayah" name="wilayah" class="w-full bg-gray-100 px-3 py-2 rounded-md" required>
                        <option value="">Pilih Wilayah</option>
                        @foreach($kabupatens as $kabupaten)
                        <option value="{{ $kabupaten->id }}">{{ $kabupaten->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="editLimit" class="block text-sm">Batas Device</label>
                    <input type="number" id="editLimit" name="limit" 
                        class="w-full bg-gray-100 px-3 py-2 rounded-md"
                        placeholder="Batas device login" required>
                </div>
            </div>
            <div class="mt-4 text-right">
                <button type="submit" class="bg-[#3560A0] text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>