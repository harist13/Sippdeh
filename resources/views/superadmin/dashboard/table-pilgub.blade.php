{{-- admin/dashboard/table-pilgub.blade.php --}}
<div class="overflow-x-auto">
    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden border-collapse text-center">
        <thead class="bg-[#3560a0] text-white">
            <tr>
                <th class="py-3 px-4 border-r border-white">NO</th>
                <th class="py-3 px-4 border-r border-white">KAB/KOTA</th>
                <th class="py-3 px-4 border-r border-white">DPT</th>
                <th class="py-3 px-4 border-r border-white">{{ $tableData['paslon1_nama'] }} / {{ $tableData['paslon1_wakil'] }}</th>
                <th class="py-3 px-4 border-r border-white">{{ $tableData['paslon2_nama'] }} / {{ $tableData['paslon2_wakil'] }}</th>
                <th class="py-3 px-4 border-r border-white">SUARA MASUK</th>
                <th class="py-3 px-4 border-r border-white">PARTISIPASI</th>
            </tr>
        </thead>
        <tbody class="bg-gray-100">
            @foreach($tableData['data'] as $data)
            <tr class="border-b">
                <td class="py-3 px-4 border-r">{{ $data['no'] }}</td>
                <td class="py-3 px-4 border-r">{{ $data['kabupaten'] }}</td>
                <td class="py-3 px-4 border-r">{{ number_format($data['dpt'], 0, ',', '.') }}</td>
                <td class="py-3 px-4 border-r">{{ number_format($data['paslon1'], 0, ',', '.') }}</td>
                <td class="py-3 px-4 border-r">{{ number_format($data['paslon2'], 0, ',', '.') }}</td>
                <td class="py-3 px-4 border-r">{{ number_format($data['suara_masuk'], 0, ',', '.') }}</td>
                <td class="py-3 px-4 border-r">
                    <div class="participation-button participation-{{ $data['warna_partisipasi'] }}">
                        {{ number_format($data['partisipasi'], 1) }}%
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
    .participation-button {
        @apply rounded-full px-4 py-1 text-white font-medium;
    }
    .participation-green {
        @apply bg-green-500;
    }
    .participation-yellow {
        @apply bg-yellow-500;
    }
    .participation-red {
        @apply bg-red-500;
    }
</style>