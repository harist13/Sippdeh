<div class="bg-white rounded-[20px] w-[70%] mx-auto p-8">
    <div class="grid grid-cols-2 gap-5 mx-auto">
        @php
            $isPilkadaTunggal = count($paslon) == 1;
            $suaraSah = $isPilkadaTunggal ? $suaraSah + $kotakKosong : $suaraSah;
        @endphp
    
        @foreach ($paslon as $calon)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="h-[217px] bg-gradient-to-b from-[#3560a0] to-[#608ac9] overflow-hidden">
                    @if ($calon->foto)
                        <img class="w-full h-full object-cover" 
                            src="{{ Storage::disk('foto_calon_lokal')->url($calon->foto) }}" 
                            alt="{{ $calon->nama }} / {{ $calon->nama_wakil }}">
                    @endif
                </div>
                <div class="p-4 text-center">
                    <h4 class="text-[#52526c] font-bold mb-1">
                        {{ $calon->nama }} / {{ $calon->nama_wakil }}
                    </h4>
                    @if ($calon->kabupaten)
                        <p class="text-[#6b6b6b] mb-2">
                            {{ $calon->kabupaten->nama }}
                        </p>
                    @endif
                    @if ($calon->provinsi)
                        <p class="text-[#6b6b6b] mb-2">
                            {{ $calon->provinsi->nama }}
                        </p>
                    @endif
                    <div class="text-[#008bf9] font-medium">
                        @if ($calon->suara > 0 && $suaraSah > 0)
                            {{ round(($calon->suara / $suaraSah) * 100, 1) }}% | {{ number_format($calon->suara, 0, '', '.') }} Suara
                        @else
                            0% | 0 Suara
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    
        @if ($isPilkadaTunggal)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="h-[217px] bg-gradient-to-b from-[#3560a0] to-[#608ac9] overflow-hidden">
                </div>
                <div class="p-4 text-center">
                    <h4 class="text-[#52526c] font-bold mb-1">
                        Kotak Kosong
                    </h4>
                    @if ($paslon[0]?->kabupaten)
                        <p class="text-[#6b6b6b] mb-2">
                            {{ $paslon[0]?->kabupaten?->nama }}
                        </p>
                    @endif
                    @if ($paslon[0]?->provinsi)
                        <p class="text-[#6b6b6b] mb-2">
                            {{ $paslon[0]?->provinsi?->nama }}
                        </p>
                    @endif
                    <div class="text-[#008bf9] font-medium">
                        @if ($kotakKosong > 0 && $suaraSah > 0)
                            {{ round(($kotakKosong / $suaraSah) * 100, 1) }}% | {{ number_format($kotakKosong, 0, '', '.') }} Suara
                        @else
                            0% | 0 Suara
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>