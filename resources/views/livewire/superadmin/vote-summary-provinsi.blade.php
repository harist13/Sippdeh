<div>
    <div class="p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
                    <!-- Title -->
                    <div class="bg-[#3560a0] text-white py-2 px-4 rounded-lg mb-4 sm:mb-0 w-full sm:w-auto">
                            Jumlah Partisipasi Suara Provinsi Kalimantan Timur
                    </div>

                    <!-- Controls Container -->
                    <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                        <!-- Export Button -->
                        <button wire:click="export" class="flex items-center gap-2 px-4 py-2 bg-[#ee3c46] text-white rounded-lg hover:bg-red-600 transition-colors">
                            <i class="fas fa-download"></i>
                            <span>Export</span>
                        </button>

                      

    
                        <!-- Search Input -->
                         <div class="relative">
                            <input 
                                wire:model.live="search" 
                                type="text" 
                                placeholder="Search" 
                                class="bg-gray-100 rounded-md px-3 py-2 pl-9">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                        
                    </div>
                </div>
    <div class="overflow-x-auto shadow-md rounded-lg">
        <table class="w-full border-collapse text-center">
            <thead>
                <tr class="bg-[#3560A0] text-white">
                    <th class="px-4 py-3 border-r border-white whitespace-nowrap">NO</th>
                    <th class="px-4 py-3 border-r border-white whitespace-nowrap">PROVINSI</th>
                    <th class="px-4 py-3 border-r border-white whitespace-nowrap">DPT</th>
                    <th class="px-4 py-3 border-r border-white whitespace-nowrap">SUARA SAH</th>
                    <th class="px-4 py-3 border-r border-white whitespace-nowrap">SUARA TIDAK SAH</th>
                    <th class="px-4 py-3 border-r border-white whitespace-nowrap">ABSTAIN</th>
                    <th class="px-4 py-3 border-r border-white whitespace-nowrap">SUARA MASUK</th>
                    <th class="px-4 py-3 whitespace-nowrap">PARTISIPASI</th>
                </tr>
            </thead>
            <tbody class="bg-gray-50">
                @foreach($summaryData as $index => $data)
                <tr class="border-b hover:bg-gray-100 transition-colors">
                    <td class="px-4 py-3 border-r">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 border-r text-left">{{ $data->provinsi_nama }}</td>
                    <td class="px-4 py-3 border-r">{{ number_format($data->total_dpt) }}</td>
                    <td class="px-4 py-3 border-r">{{ number_format($data->total_suara_sah) }}</td>
                    <td class="px-4 py-3 border-r">{{ number_format($data->total_suara_tidak_sah) }}</td>
                    <td class="px-4 py-3 border-r">{{ number_format($data->total_abstain) }}</td>
                    <td class="px-4 py-3 border-r">{{ number_format($data->total_suara_masuk) }}</td>
                    <td class="px-4 py-3">
                        @php
                            $partisipasi = $data->total_dpt > 0 
                                ? round(($data->total_suara_masuk / $data->total_dpt) * 100, 1) 
                                : 0;
                            $colorClass = $partisipasi >= 70 
                                ? 'bg-green-500' 
                                : ($partisipasi >= 50 ? 'bg-yellow-500' : 'bg-red-500');
                        @endphp
                        <span class="{{ $colorClass }} text-white px-2 py-1 rounded">
                            {{ $partisipasi }}%
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $summaryData->links() }}
    </div>
</div>
</div>