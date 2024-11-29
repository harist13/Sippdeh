@php
    $isPilkadaTunggal = count($paslon) == 1;
@endphp

<table>
    <thead>
        <tr>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                Kabupaten/Kota
            </th>
            
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                Kecamatan
            </th>
            
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                Kelurahan
            </th>
            
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                TPS
            </th>

            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 100px;">
                DPT
            </th>

            @if ($isPilkadaTunggal)
                <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                    <span>Kotak Kosong</span>
                </th>
            @endif

            @foreach ($paslon as $calon)
                <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                    <span>{{ $calon->nama }}/<br>{{ $calon->nama_wakil }}</span>
                </th>
            @endforeach
            
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 100px;">
                <span>Suara Tidak Sah</span>
            </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($tps as $datum)
            @php
                $kelurahan = App\Models\Kelurahan::find($datum->kelurahan_id);
                $kecamatan = App\Models\Kecamatan::find($kelurahan->kecamatan_id);
                $kabupaten = App\Models\Kabupaten::find($kecamatan->kabupaten_id);

                $tps = App\Models\TPS::find($datum->id);
            @endphp

            <tr>
                <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                    {{ $kabupaten?->nama ?? '-' }}
                </td>
                
                <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                    {{ $kecamatan?->nama ?? '-' }}
                </td>
                
                <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                    {{ $kelurahan?->nama ?? '-' }}
                </td>
                
                <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                    {{ $datum->nama }}
                </td>

                <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                    {{ $datum->dpt }}
                </td>
                
                @if ($isPilkadaTunggal)
                    <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                        {{ $datum->kotak_kosong }}
                    </td>
                @endif

                @foreach ($paslon as $calon)
                    @php
                        $suaraCalon = $tps->suaraCalonByCalonId($calon->id)->first();
                        $suaraCalon = $suaraCalon ? $suaraCalon->suara : 0;
                    @endphp
                    <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                        {{ $suaraCalon }}
                    </td>
                @endforeach

                <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                    {{ $datum->suara_tidak_sah }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>