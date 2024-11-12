<div>
    <div class="p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
                    <!-- Title -->
                    <div class="w-full lg:w-auto">
                        <h2 class="text-lg font-bold bg-[#3560A0] text-white px-4 py-2 rounded shadow-sm inline-block">
                            Jumlah Partisipasi Suara Provinsi Kalimantan Timur
                        </h2>
                    </div>

                    <!-- Controls Container -->
                    <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                        <!-- Export Button -->
                        <button class="flex items-center gap-2 px-4 py-2 bg-[#ee3c46] text-white rounded-lg hover:bg-red-600 transition-colors">
                            <i class="fas fa-download"></i>
                            <span>Export</span>
                        </button>

                        <!-- Data Limit Dropdown -->
                        <select class="bg-gray-100 rounded-md px-3 py-2 border border-gray-300 cursor-pointer hover:bg-gray-200 transition-colors">
                            <option value="10">10 Data</option>
                            <option value="20">20 Data</option>
                            <option value="50">50 Data</option>
                            <option value="100">100 Data</option>
                        </select>

                       

                        <!-- Search Input -->
                        <div class="relative">
                            <input type="text" placeholder="Search" class="bg-gray-100 rounded-md px-3 py-2 pl-9">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <!-- Filter Dropdown -->
                        <div class="relative">
                            <button id="filterDropdownButton" class="flex items-center gap-2 bg-gray-100 rounded-md px-3 py-2 hover:bg-gray-200 transition-colors">
                                <i class="fas fa-filter text-gray-600"></i>
                                <span>Filter</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div id="filterDropdown" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                                <ul class="py-1">
                                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 text-green-500 font-semibold">Tinggi (>70%)</a></li>
                                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 text-yellow-500 font-semibold">Sedang (50-70%)</a></li>
                                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 text-red-500 font-semibold">Rendah (<50%)</a></li>
                                </ul>
                            </div>
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