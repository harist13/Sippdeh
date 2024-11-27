<div class="grid grid-cols-5 gap-4 mb-8">
    @foreach($kabupatens as $kabupaten)
        @php
            $isKota = str_contains(strtolower($kabupaten->nama), 'kota');
            $route = route('Superadmin.resume', [
                'wilayah' => $kabupaten->slug,
                'kabupatenId' => $kabupaten->id,
                'showPilgub' => false,
                'showPilwali' => $isKota,
                'showPilbup' => !$isKota
            ])
        @endphp
        
        <a href="{{ $route }}" class="bg-[#3560A0] text-white py-2 px-4 rounded text-center hover:bg-[#2a4d8a] transition-colors duration-200">{{ str_replace(['Kabupaten ', 'Kota '], '', $kabupaten->nama) }}</a>
    @endforeach
</div>