<header class="bg-white shadow navbar-fixed">
    <div class="container flex items-center justify-between px-4 py-3 mx-auto">
        <div class="flex items-center">
            <button id="openSidebar" class="mr-4 text-gray-600 focus:outline-none">
                <i class="text-xl fas fa-bars"></i>
            </button>
            <a href="{{ route('Dashboard') }}">
                <div class="flex items-center">
                    <img src="{{ asset('assets/logo.png')}}" alt="Logo" class="w-8 h-10 mr-2">
                    <div class="flex flex-col">
                        <span class="text-lg font-semibold text-blue-600">Badan Kesatuan Bangsa Dan Politik</span>
                        <span class="text-sm text-gray-600">Provinsi Kalimantan Timur</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="flex items-center">
            <div class="relative">
                <button id="profileDropdown" class="flex items-center text-gray-600 focus:outline-none">
                    <img src="{{ asset('assets/user.png')}}" alt="Logo" class="w-10 h-10 mr-2">

                    <div class="flex-col text-left hidden sm:flex">
                        <span class="font-semibold">{{ Auth::user()->wilayah?->nama ?? '-' }}</span>
                        <span class="text-sm text-gray-500">{{ Auth::user()->roles->first()?->name ?? '-' }}</span>
                    </div>
                </button>
                <!-- Dropdown Menu -->
                <div id="profileMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-50">
                    <div class="py-1">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">
                            <i class="fas fa-user-circle mr-2"></i> Profile
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-blue-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="profileModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-6 border w-96 shadow-lg rounded-lg bg-white">
            <div class="text-center">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Profile</h3>
                <form id="profileForm" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1 text-left" for="username">
                            Username
                        </label>
                        <input
                            class="w-full px-3 py-2 border bg-gray-300 border-gray-300 rounded-md text-gray-700 focus:outline-none focus:border-blue-500"
                            id="username" type="text" value="{{ Auth::user()->username }}" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1 text-left" for="email">
                            Email
                        </label>
                        <input
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:border-blue-500"
                            id="email" name="email" type="email" value="{{ Auth::user()->email }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1 text-left" for="wilayah">
                            Wilayah
                        </label>
                        <input
                            class="w-full px-3 py-2 border bg-gray-300 border-gray-300 rounded-md text-gray-700 focus:outline-none focus:border-blue-500"
                            id="wilayah" type="text" value="{{ Auth::user()->wilayah?->nama ?? '-' }}" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1 text-left" for="password">
                            New Password
                        </label>
                        <input
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:border-blue-500"
                            id="password" name="password" type="password"
                            placeholder="Kosongkan jika password tidak di ubah">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1 text-left"
                            for="password_confirmation">
                            Confirm New Password
                        </label>
                        <input
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:border-blue-500"
                            id="password_confirmation" name="password_confirmation" type="password"
                            placeholder="Konfirmasi password baru">
                    </div>
                </form>
                <div class="mt-6 flex justify-between">
                    <button id="saveProfile"
                        class="px-4 py-2 bg-[#3560A0] text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Simpan Perubahan
                    </button>
                    <button id="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>