<div class="relative w-[300px] w-full-mobile">
    <button id="dropdownButton"
        class="bg-gray-100 w-full border border-gray-300 text-gray-700 py-2 px-4 rounded-lg flex items-center justify-between w-full-mobile">
        <span id="kabupatenTerpilih">Pilih Kab/Kota</span> <i class="fas fa-chevron-right ml-2"></i>
    </button>
    <div id="dropdownMenu" class="absolute h-[300px] overflow-y-scroll mt-2 inset-x-0 rounded-lg shadow-lg bg-white z-10 hidden">
        <ul class=" text-gray-700">
            @if (request()->has('cari'))
                <a href="{{ route($routeName) }}?cari={{ request()->get('cari') }}">
                    <li class="px-4 py-2 hover:bg-gray-100">
                        Semua
                    </li>
                </a>
            @else
                <a href="{{ route($routeName) }}">
                    <li class="px-4 py-2 hover:bg-gray-100">
                        Semua
                    </li>
                </a>
            @endif

            @foreach ($kabupaten as $kab)
                @if (request()->has('cari'))
                    <a href="{{ route($routeName) }}?cari={{ request()->get('cari') }}&kabupaten={{ $kab->id }}" class="line-clamp-1 text-ellipsis">
                        <li class="px-4 py-2 {{ request()->get('kabupaten') == $kab->id ? 'bg-[#3560A0] text-white kabupaten-dilipih' : 'hover:bg-gray-100' }} pilihan-kabupaten">{{ $kab->nama }}</li>
                    </a>
                @else
                    <a href="{{ route($routeName) }}?kabupaten={{ $kab->id }}" class="line-clamp-1 text-ellipsis">
                        <li class="px-4 py-2 {{ request()->get('kabupaten') == $kab->id ? 'bg-[#3560A0] text-white kabupaten-dilipih' : 'hover:bg-gray-100' }} pilihan-kabupaten">{{ $kab->nama }}</li>
                    </a>
                @endif
            @endforeach
        </ul>
    </div>
</div>

<script>
    function tetapkanKabupatenTerpilih() {
        const kabupatenTerpilih = document.getElementById('kabupatenTerpilih');
        const kabupatenDipilih = document.querySelector('.kabupaten-dilipih');
        
        if (kabupatenDipilih != null) {
            kabupatenTerpilih.textContent = kabupatenDipilih.textContent;
        }
    }

    tetapkanKabupatenTerpilih();

    // Dropdown functionality
    document.getElementById('dropdownButton').addEventListener('click', function() {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    });
</script>