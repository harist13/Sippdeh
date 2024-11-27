@php
    // $isKabupatenColumnIgnored = !in_array('KABUPATEN', $includedColumns);
    // $isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
    // $isKelurahanColumnIgnored = !in_array('KELURAHAN', $includedColumns);
    // $isTPSColumnIgnored = !in_array('TPS', $includedColumns);

    // $isCalonColumnIgnored = !in_array('CALON', $includedColumns);
    $isPilkadaTunggal = count($paslon) == 1;
@endphp

@php
    $totalDptb = $kecamatan->sum(fn ($datum) => $datum->dptb ?? 0);
    $totalDpk = $kecamatan->sum(fn ($datum) => $datum->dpk ?? 0);
    $totalSuaraSah = $kecamatan->sum(fn ($datum) => $datum->suara_sah ?? 0);
    $totalSuaraTidakSah = $kecamatan->sum(fn ($datum) => $datum->suara_tidak_sah ?? 0);
    $totalSuaraMasuk = $kecamatan->sum(fn ($datum) => $datum->suara_masuk ?? 0);
    $totalAbstain = $kecamatan->sum(fn ($datum) => $datum->abstain ?? 0);
    
    try {
        $totalPartisipasi = ($totalSuaraMasuk / ($totalDptb + $totalDpk)) * 100;
    } catch (DivisionByZeroError $error) {
        $totalPartisipasi = 0;
    }

    $totalsPerCalon = [];
    foreach ($paslon as $calon) {
        $totalsPerCalon[$calon->id] = $kecamatan->sum(fn($datum) => $datum->getCalonSuaraByCalonId($calon->id)?->total_suara ?? 0);
    }

    $totalKotakKosong = $kecamatan->sum(fn ($datum) => $datum->kotak_kosong ?? 0);
@endphp

@push('styles')
    <style>
        /* Disable spinner on number input */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Fix for layout shifting */
        .value {
            display: inline-block;
            opacity: 1;
            width: 100%;
            text-align: center;
        }

        .hidden {
            opacity: 0;
            position: absolute;
            pointer-events: none;
        }

        /* Position input absolutely within its cell */
        td.dptb, td.dpk {
            position: relative;
        }

        td.dptb input, td.dpk input {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            margin: 0;
            width: 60%; /* Slightly smaller than cell width */
            text-align: center;
        }

        /* Ensure cells maintain consistent width */
        td.dptb, td.dpk {
            width: 50px;
            min-width: 50px;
            max-width: 50px;
        }
    </style>
@endpush

<table class="min-w-full divide-y divide-gray-200 sticky-table input-suara-table" style="table-layout: fixed;">
    <thead class="bg-[#3560A0] text-white">
        <tr>
            <th rowspan="2" class="py-4 px-2 text-center font-semibold text-sm border border-white select-none" style="width: 30px;">
                <input type="checkbox" id="checkAll" class="form-checkbox h-5 w-5 text-white border-white select-none rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200">
            </th>
            
            <th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="width: 300px">
                Kecamatan
            </th>
            
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="width: 100px">
                DPTb
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="width: 100px">
                DPK
            </th>

            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none bg-blue-950" style="min-width: 100px;" {{ !$isPilkadaTunggal ? 'hidden' : '' }}>
                Kotak Kosong
            </th>

            @foreach ($paslon as $calon)
                <th wire:key="{{ $calon->id }}" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none bg-blue-950" style="min-width: 100px;">
                    {{ $calon->nama }}/<br>{{ $calon->nama_wakil }}
                </th>
            @endforeach

            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Suara Sah
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Suara Tidak Sah
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Suara Masuk
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Abstain
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Partisipasi
            </th>
        </tr>
        <tr>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-dptb">
                {{ number_format($totalDptb, 0, '.', '.') }}
            </th>

            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-dpk">
                {{ number_format($totalDpk, 0, '.', '.') }}
            </th>

            {{-- Kotak Kosong --}}
            @if ($isPilkadaTunggal)
                <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-kotak-kosong bg-blue-950">
                    {{ $totalKotakKosong }}
                </th>
            @endif

            @foreach ($paslon as $calon)
                <th wire:key="total-{{ $calon->id }}" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none bg-blue-950 total-calon">
                    {{ number_format($totalsPerCalon[$calon->id], 0, '.', '.') }}
                </th>
            @endforeach
        
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-suara-sah">
                {{ number_format($totalSuaraSah, 0, '.', '.') }}
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-suara-tidak-sah">
                {{ number_format($totalSuaraTidakSah, 0, '.', '.') }}
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-suara-masuk">
                {{ number_format($totalSuaraMasuk, 0, '.', '.') }}
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-abstain">
                {{ number_format($totalAbstain, 0, '.', '.') }}
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none rata-rata-paritisipasi">
                {{ number_format($totalPartisipasi, 1, '.', '.') }}%
            </th>
        </tr>
    </thead>

    <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
        @forelse ($kecamatan as $datum)
            <tr wire:key="{{ $datum->id }}" class="border-b text-center select-none cursor-pointer daftar-pemilih" data-id="{{ $datum->id }}">
                {{-- Checkbox --}}
                <td class="py-3 px-4 border centang" data-id="{{ $datum->id }}" style="width: 30px;">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 cursor-pointer">
                </td>

                {{-- Kecamatan --}}
                <td class="py-3 px-4 text-xs text-left border kecamatan" data-kecamatan-id="{{ $datum->id ?? '-' }}" style="width: 300px">
                    {{ $datum->nama ?? '-' }}
                </td>

                {{-- DPTb --}}
                <td class="py-3 px-4 text-xs border dptb" data-value="{{ $datum->dptb }}" style="width: 50px">
                    <span class="value">{{ $datum->dptb }}</span>
                    <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-16 focus:outline-none hidden" value="{{ $datum->dptb }}" data-default-value="{{ $datum->dptb }}" autocomplete="off">
                </td>

                {{-- DPK --}}
                <td class="py-3 px-4 text-xs border dpk" data-value="{{ $datum->dpk }}" style="width: 50px">
                    <span class="value">{{ $datum->dpk }}</span>
                    <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-16 focus:outline-none hidden" value="{{ $datum->dpk }}" data-default-value="{{ $datum->dpk }}" autocomplete="off">
                </td>

                {{-- Kotak Kosong --}}
                <td class="py-3 px-4 text-xs border kotak-kosong" data-value="{{ $datum->kotak_kosong }}" {{ !$isPilkadaTunggal ? 'hidden' : '' }}>
                    <span class="value">{{ $datum->kotak_kosong }}</span>
                    <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-16 focus:outline-none hidden" value="{{ $datum->kotak_kosong }}" data-default-value="{{ $datum->kotak_kosong }}" autocomplete="off">
                </td>

                {{-- Calon-calon --}}
                @foreach ($paslon as $calon)
                    @php
                        $suaraCalon = $datum->getCalonSuaraByCalonId($calon->id);
                        $suara = $suaraCalon != null ? $suaraCalon->total_suara : 0;
                    @endphp
                    <td wire:key="{{ $datum->id }}{{ $calon->id }}" class="py-3 px-4 text-xs border paslon" data-id="{{ $calon->id }}" data-suara="{{ $suara }}">
                        <span class="value">{{ $suara }}</span>
                        <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-16 focus:outline-none hidden" value="{{ $suara }}" data-default-value="{{ $suara }}" autocomplete="off">
                    </td>
                @endforeach

                {{-- Suara Sah --}}
                <td class="py-3 px-4 text-xs border suara-sah" data-value="{{ $datum->suara_sah }}">
                    {{ $datum->suara_sah }}
                </td>

                {{-- Suara Tidak Sah --}}
                <td class="py-3 px-4 text-xs border suara-tidak-sah" data-value="{{ $datum->suara_tidak_sah }}">
                    <span class="value">{{ $datum->suara_tidak_sah }}</span>
                    <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-16 focus:outline-none hidden" data-default-value="{{ $datum->suara_tidak_sah }}" data-value="{{ $datum->suara_tidak_sah }}">
                </td>

                {{-- Suara Masuk --}}
                <td class="py-3 px-4 text-xs border suara-masuk" data-value="{{ $datum->suara_masuk }}">
                    {{ $datum->suara_masuk }}
                </td>

                {{-- Abstain --}}
                <td class="py-3 px-4 text-xs border abstain" data-value="{{ $datum->abstain }}">
                    {{ $datum->abstain }}
                </td>

                {{-- Partisipasi --}}
                <td class="py-3 px-4 text-xs border partisipasi {{ strtolower($datum->partisipasi) }}">
                    @if ($datum->partisipasi >= 77.5)
                        <span class="bg-green-400 block text-white py-1 px-7 rounded text-xs">
                            {{ number_format($datum->partisipasi, 1, '.', '.') }}%
                        </span>
                    @else
                        <span class="bg-red-400 block text-white py-1 px-7 rounded text-xs">
                            {{ number_format($datum->partisipasi, 1, '.', '.') }}%
                        </span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="15" class="py-5 px-2 text-center text-gray-500">
                    Data tidak tersedia.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>