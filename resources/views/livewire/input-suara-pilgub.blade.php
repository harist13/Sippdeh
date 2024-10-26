<div class="bg-white rounded-[20px] p-4 mb-8 shadow-lg">
    <div class="container mx-auto p-7">
      <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between mb-4">
        {{-- Simpan, Batal Edit, dan Masuk Edit Mode --}}
        <div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center order-2 lg:order-1">
            <button class="bg-[#58DA91] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto">
                <i class="fas fa-check mr-3"></i>
                Simpan Perubahan Data
            </button>
            <button class="bg-[#EE3C46] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto">
                <i class="fas fa-times mr-3"></i>
                Batal Ubah Data
            </button>
            <button class="bg-[#0070FF] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto">
                <i class="fas fa-plus mr-3"></i>
                Ubah Data Tercentang
            </button>
        </div>

        {{-- Cari dan Filter --}}
        <div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center order-1 lg:order-2">
            <div class="flex items-center rounded-lg bg-[#ECEFF5] px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
                </svg>
                <input type="search" placeholder="Cari" name="cari" class="ml-2 bg-transparent focus:outline-none text-gray-600" value="{{ request()->get('cari') }}">
            </div>
            <button class="flex items-center justify-center bg-[#ECEFF5] text-white text-sm font-medium px-4 py-2 rounded-lg sm:w-auto w-full" id="openFilterPilgub">
                <img src="{{ asset('assets/icon/filter-lines.png') }}" alt="Filter" class="w-4 h-4 mr-2">
                <span class="text-[#344054]">Filter</span>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto mb-5 -mx-4 sm:mx-0">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#3560A0] text-white">
                        <tr>
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 50px;">NO</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 50px;">
                                <input type="checkbox" id="selectAll" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200">
                            </th>
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 200px;">Kecamatan</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 200px;">Kelurahan</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 200px;">TPS</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 100px;">DPT</th>
                            @foreach ($paslon as $calon)
                                <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 300px;">
                                    {{-- Rahmad Mas'ud/<br>Bagus Susetyo --}}
                                    {{ $calon->nama }}/<br>{{ $calon->nama_wakil }}
                                </th>
                            @endforeach
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 200px;">Calon</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 200px;">Suara Sah</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 200px;">Suara Tidak Sah</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 200px;">Jumlah Pengguna<br>Tidak Pilih</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 200px;">Suara Masuk</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm" style="min-width: 50px;">Partisipasi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                        @foreach ($tps as $t)
                            <tr class="border-b text-center">
                                <td class="py-3 px-4">01</td>
                                <td class="py-3 px-4">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                                </td>
                                <td class="py-3 px-4">
                                    <p>Samarinda Kota</p>
                                </td>
                                <td class="py-3 px-4">Palaran</td>
                                <td class="py-3 px-4">{{ $t->nama }}</td>
                                <td class="py-3 px-4">55.345</td>
                                @foreach ($paslon as $calon)
                                    <td class="py-3 px-4">{{ $calon->nama }}</td>
                                @endforeach
                                <td class="py-3 px-4">Gubernur/<br>Wakil Gubernur</td>
                                <td class="py-3 px-4">55.345</td>
                                <td class="py-3 px-4">55.345</td>
                                <td class="py-3 px-4">55.345</td>
                                <td class="py-3 px-4">55.345</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="bg-green-400 text-white py-1 px-7 rounded text-xs">90%</span>
                                </td>
                            </tr>
                        @endforeach
                        {{-- <tr class="border-b text-center">
                            <td class="py-3 px-4">02</td>
                            <td class="py-3 px-4">
                                <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                            </td>
                            <td class="py-3 px-4">
                                <p>Samarinda Kota</p>
                            </td>
                            <td class="py-3 px-4">Palaran</td>
                            <td class="py-3 px-4">2370750016-TPS 016</td>
                            <td class="py-3 px-4">
                                <input type="search" placeholder="Jumlah" name="cari" class="bg-[#ECEFF5] border border-gray-600 text-gray-600 rounded-lg ml-2 px-4 py-2 focus:outline-none">
                            </td>
                            <td class="py-3 px-4">
                                <input type="search" placeholder="Jumlah" name="cari" class="bg-[#ECEFF5] border border-gray-600 text-gray-600 rounded-lg ml-2 px-4 py-2 focus:outline-none">
                            </td>
                            <td class="py-3 px-4">
                                <input type="search" placeholder="Jumlah" name="cari" class="bg-[#ECEFF5] border border-gray-600 text-gray-600 rounded-lg ml-2 px-4 py-2 focus:outline-none">
                            </td>
                            <td class="py-3 px-4">Gubernur/<br>Wakil Gubernur</td>
                            <td class="py-3 px-4">
                                <input type="search" placeholder="Jumlah" name="cari" class="bg-[#ECEFF5] border border-gray-600 text-gray-600 rounded-lg ml-2 px-4 py-2 focus:outline-none">
                            </td>
                            <td class="py-3 px-4">
                                <input type="search" placeholder="Jumlah" name="cari" class="bg-[#ECEFF5] border border-gray-600 text-gray-600 rounded-lg ml-2 px-4 py-2 focus:outline-none">
                            </td>
                            <td class="py-3 px-4">
                                <input type="search" placeholder="Jumlah" name="cari" class="bg-[#ECEFF5] border border-gray-600 text-gray-600 rounded-lg ml-2 px-4 py-2 focus:outline-none">
                            </td>
                            <td class="py-3 px-4">
                                <input type="search" placeholder="Jumlah" name="cari" class="bg-[#ECEFF5] border border-gray-600 text-gray-600 rounded-lg ml-2 px-4 py-2 focus:outline-none">
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="bg-green-400 text-white py-1 px-7 rounded text-xs">90%</span>
                            </td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{ $tps->links('vendor.livewire.simple', data: ['scrollTo' => false]) }}
</div>