<div class="relative w-[300px] w-full-mobile">
    <button id="dropdownButton"
        class="bg-gray-100 w-full border border-gray-300 text-gray-700 py-2 px-4 rounded-lg flex items-center justify-between w-full-mobile">
        Pilih Kab/Kota <i class="fas fa-chevron-right ml-2"></i>
    </button>
    <div id="dropdownMenu" class="absolute mt-2 inset-x-0 rounded-lg shadow-lg bg-white z-10 hidden">
        <ul class="py-1 text-gray-700">
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
                        <li class="px-4 py-2 hover:bg-gray-100">{{ $kab->nama }}</li>
                    </a>
                @else
                    <a href="{{ route($routeName) }}?kabupaten={{ $kab->id }}" class="line-clamp-1 text-ellipsis">
                        <li class="px-4 py-2 hover:bg-gray-100">{{ $kab->nama }}</li>
                    </a>
                @endif
            @endforeach
        </ul>
    </div>
</div>