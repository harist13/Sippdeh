<div id="sidebar" class="fixed top-0 left-0 z-1050 w-64 h-full overflow-y-auto bg-white shadow-lg sidebar hidden">
    <div class="flex items-center justify-between p-4 bg-[#3560A0] my-2 ml-2 mr-2 rounded-md">
        <h2 class="text-lg font-bold text-white">SIPPDEH PROV.KALTIM</h2>
        <button id="closeSidebar" class="text-white focus:outline-none">

        </button>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="mt-4">
        <a href="{{ route('Dashboard') }}"
            class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('Dashboard') ? 'active' : '' }}">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V10C2 6.22876 2 4.34315 3.17157 3.17157C4.34315 2 6.23869 2 10.0298 2C10.6358 2 11.1214 2 11.53 2.01666C11.5166 2.09659 11.5095 2.17813 11.5092 2.26057L11.5 5.09497C11.4999 6.19207 11.4998 7.16164 11.6049 7.94316C11.7188 8.79028 11.9803 9.63726 12.6716 10.3285C13.3628 11.0198 14.2098 11.2813 15.0569 11.3952C15.8385 11.5003 16.808 11.5002 17.9051 11.5001L18 11.5001H21.9574C22 12.0344 22 12.6901 22 13.5629V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22Z"
                    fill="currentColor" />
                <path
                    d="M19.3517 7.61665L15.3929 4.05375C14.2651 3.03868 13.7012 2.53114 13.0092 2.26562L13 5.00011C13 7.35713 13 8.53564 13.7322 9.26787C14.4645 10.0001 15.643 10.0001 18 10.0001H21.5801C21.2175 9.29588 20.5684 8.71164 19.3517 7.61665Z"
                    fill="currentColor" />
            </svg>
            <span class="ml-2">Dashboard</span>
        </a>
        <a href="{{ route('admin.resume') }}"
            class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('rangkuman') ? 'active' : '' }}">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M16.5189 16.5013C16.6939 16.3648 16.8526 16.2061 17.1701 15.8886L21.1275 11.9312C21.2231 11.8356 21.1793 11.6708 21.0515 11.6264C20.5844 11.4644 19.9767 11.1601 19.4083 10.5917C18.8399 10.0233 18.5356 9.41561 18.3736 8.94849C18.3292 8.82066 18.1644 8.77687 18.0688 8.87254L14.1114 12.8299C13.7939 13.1474 13.6352 13.3061 13.4987 13.4811C13.3377 13.6876 13.1996 13.9109 13.087 14.1473C12.9915 14.3476 12.9205 14.5606 12.7786 14.9865L12.5951 15.5368L12.3034 16.4118L12.0299 17.2323C11.9601 17.4419 12.0146 17.6729 12.1708 17.8292C12.3271 17.9854 12.5581 18.0399 12.7677 17.9701L13.5882 17.6966L14.4632 17.4049L15.0135 17.2214L15.0136 17.2214C15.4394 17.0795 15.6524 17.0085 15.8527 16.913C16.0891 16.8004 16.3124 16.6623 16.5189 16.5013Z"
                    fill="currentColor" />
                <path
                    d="M22.3665 10.6922C23.2112 9.84754 23.2112 8.47812 22.3665 7.63348C21.5219 6.78884 20.1525 6.78884 19.3078 7.63348L19.1806 7.76071C19.0578 7.88348 19.0022 8.05496 19.0329 8.22586C19.0522 8.33336 19.0879 8.49053 19.153 8.67807C19.2831 9.05314 19.5288 9.54549 19.9917 10.0083C20.4545 10.4712 20.9469 10.7169 21.3219 10.847C21.5095 10.9121 21.6666 10.9478 21.7741 10.9671C21.945 10.9978 22.1165 10.9422 22.2393 10.8194L22.3665 10.6922Z"
                    fill="currentColor" />
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M4.17157 3.17157C3 4.34315 3 6.22876 3 10V14C3 17.7712 3 19.6569 4.17157 20.8284C5.34315 22 7.22876 22 11 22H13C16.7712 22 18.6569 22 19.8284 20.8284C20.9812 19.6756 20.9997 17.8316 21 14.1801L18.1817 16.9984C17.9119 17.2683 17.691 17.4894 17.4415 17.6841C17.1491 17.9121 16.8328 18.1076 16.4981 18.2671C16.2124 18.4032 15.9159 18.502 15.5538 18.6225L13.2421 19.3931C12.4935 19.6426 11.6682 19.4478 11.1102 18.8898C10.5523 18.3318 10.3574 17.5065 10.607 16.7579L10.8805 15.9375L11.3556 14.5121L11.3775 14.4463C11.4981 14.0842 11.5968 13.7876 11.7329 13.5019C11.8924 13.1672 12.0879 12.8509 12.316 12.5586C12.5106 12.309 12.7317 12.0881 13.0017 11.8183L17.0081 7.81188L18.12 6.70004L18.2472 6.57282C18.9626 5.85741 19.9003 5.49981 20.838 5.5C20.6867 4.46945 20.3941 3.73727 19.8284 3.17157C18.6569 2 16.7712 2 13 2H11C7.22876 2 5.34315 2 4.17157 3.17157ZM7.25 9C7.25 8.58579 7.58579 8.25 8 8.25H14.5C14.9142 8.25 15.25 8.58579 15.25 9C15.25 9.41421 14.9142 9.75 14.5 9.75H8C7.58579 9.75 7.25 9.41421 7.25 9ZM7.25 13C7.25 12.5858 7.58579 12.25 8 12.25H10.5C10.9142 12.25 11.25 12.5858 11.25 13C11.25 13.4142 10.9142 13.75 10.5 13.75H8C7.58579 13.75 7.25 13.4142 7.25 13ZM7.25 17C7.25 16.5858 7.58579 16.25 8 16.25H9.5C9.91421 16.25 10.25 16.5858 10.25 17C10.25 17.4142 9.91421 17.75 9.5 17.75H8C7.58579 17.75 7.25 17.4142 7.25 17Z"
                    fill="currentColor" />
            </svg>
            <span class="ml-2">Resume</span>
        </a>

        <hr class="my-2 ml-2 mr-2 rounded-md gradient-hr">

        <div class="px-2 py-2 text-gray-700 font-bold text-base">User</div>
        <a href="{{ route('user')}}"
            class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('user') ? 'active' : '' }}">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="6" r="4" fill="currentColor" />
                <ellipse cx="12" cy="17" rx="7" ry="4" fill="currentColor" />
            </svg>
            <span class="ml-2">User</span>
        </a>

        <hr class="my-2 ml-2 mr-2 rounded-md gradient-hr">

        <div class="px-2 py-2 text-gray-700 font-bold text-base">Data</div>
        <a href="{{ route('provinsi')}}"
            class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('provinsi') ? 'active' : '' }}">
            <img src="{{ asset('assets/icon/provinsi.svg') }}" alt="Provinsi">
            <span class="ml-2">Provinsi</span>
        </a>
        <a href="{{ route('kabupaten')}}"
            class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('kabupaten') ? 'active' : '' }}">
            <img src="{{ asset('assets/icon/kabupaten.svg') }}" alt="Kabupaten">
            <span class="ml-2">Kabupaten/Kota</span>
        </a>
        <a href="{{ route('kecamatan')}}"
            class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('kecamatan') ? 'active' : '' }}">
            <img src="{{ asset('assets/icon/kecamatan.svg') }}" alt="Kecamatan">
            <span class="ml-2">Kecamatan</span>
        </a>
        <a href="{{ route('kelurahan')}}"
            class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('kelurahan') ? 'active' : '' }}">
            <img src="{{ asset('assets/icon/kelurahan.svg') }}" alt="Kelurahan">
            <span class="ml-2">Kelurahan</span>
        </a>
        <a href="{{ route('tps')}}"
            class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('tps') ? 'active' : '' }}">
            <img src="{{ asset('assets/icon/tps.svg') }}" alt="TPS">
            <span class="ml-2">TPS</span>
        </a>
        <a href="{{ route('calon')}}"
            class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('calon') ? 'active' : '' }}">
            <img src="{{ asset('assets/icon/pasangan_calon.svg') }}" alt="Calon">
            <span class="ml-2">Calon Kabupaten/Kota</span>
        </a>
    </nav>
</div>