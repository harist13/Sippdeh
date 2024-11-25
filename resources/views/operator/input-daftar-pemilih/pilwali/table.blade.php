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
            <th class="py-4 px-2 text-center font-semibold text-sm border border-white select-none" style="width: 30px;">
                <input type="checkbox" id="checkAll" class="form-checkbox h-5 w-5 text-white border-white select-none rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200">
            </th>
            
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="width: 300px">
                Kecamatan
            </th>
            
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="width: 50px">
                DPTb
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="width: 50px">
                DPK
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