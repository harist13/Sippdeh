{{-- admin/dashboard/score-bar.blade.php --}}
<section class="rounded-lg p-4 mb-8">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
            <img src="{{ Storage::disk('foto_calon_lokal')->url($paslon1->foto) }}" 
                alt="{{ $paslon1->nama }}/{{ $paslon1->nama_wakil }}" 
                class="rounded-full mr-4 w-20 h-20 object-cover">
            <div class="flex flex-col">
                <span class="font-semibold text-lg">{{ $paslon1->nama }}/{{ $paslon1->nama_wakil }}</span>
                <span class="text-sm text-gray-600">{{ number_format($paslon1->persentase, 1) }}%</span>
            </div>
        </div>
        <div class="flex items-center">
            <div class="flex flex-col items-end">
                <span class="font-semibold text-lg">{{ $paslon2->nama }}/{{ $paslon2->nama_wakil }}</span>
                <span class="text-sm text-gray-600">{{ number_format($paslon2->persentase, 1) }}%</span>
            </div>
            <img src="{{ Storage::disk('foto_calon_lokal')->url($paslon2->foto) }}" 
                alt="{{ $paslon2->nama }}/{{ $paslon2->nama_wakil }}" 
                class="rounded-full ml-4 w-20 h-20 object-cover">
        </div>
    </div>

    <div class="bg-gray-200 h-10 rounded-full overflow-hidden relative">
        @if($paslon1Wins)
            {{-- Jika paslon 1 menang, warna biru mengisi dari kiri --}}
            <div class="absolute inset-y-0 left-0 bg-[#3560A0] transition-all duration-500"
                style="width: {{ $paslon1->persentase }}%">
                <span class="text-white text-sm font-semibold ml-4 leading-10">
                    {{ number_format($paslon1->total_suara, 0, ',', '.') }} Suara
                </span>
            </div>
            <div class="absolute inset-y-0 right-0 bg-yellow-400 transition-all duration-500"
                style="width: {{ $paslon2->persentase }}%">
                <span class="text-white text-sm font-semibold mr-4 leading-10 float-right">
                    {{ number_format($paslon2->total_suara, 0, ',', '.') }} Suara
                </span>
            </div>
        @else
            {{-- Jika paslon 2 menang, warna kuning mengisi dari kanan --}}
            <div class="absolute inset-y-0 right-0 bg-yellow-400 transition-all duration-500"
                style="width: {{ $paslon2->persentase }}%">
                <span class="text-white text-sm font-semibold mr-4 leading-10 float-right">
                    {{ number_format($paslon2->total_suara, 0, ',', '.') }} Suara
                </span>
            </div>
            <div class="absolute inset-y-0 left-0 bg-[#3560A0] transition-all duration-500"
                style="width: {{ $paslon1->persentase }}%">
                <span class="text-white text-sm font-semibold ml-4 leading-10">
                    {{ number_format($paslon1->total_suara, 0, ',', '.') }} Suara
                </span>
            </div>
        @endif
    </div>
</section>