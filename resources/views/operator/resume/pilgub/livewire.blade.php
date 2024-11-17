<div>
    <div class="bg-white rounded-[20px] mb-8">
        <div class="bg-white p-4 rounded-t-[20px]">
            <div class="container mx-auto">
                <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between">
                    <h1 class="font-bold text-xl">Data Suara Pemilihan Gubernur</h1>
                    
                    {{-- Cari dan Filter --}}
                    @include('operator.resume.pilgub.export-search-filter')
                </div>
            </div>
        </div>

        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden border-b border-gray-200 shadow">
                    <div class="relative px-4">
                        <!-- Loading Overlay -->
                        <div wire:loading.delay wire:target.except="export" class="absolute inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-10"></div>
                        @include('operator.resume.pilgub.'.$scope.'-table', compact('suara', 'paslon',
                        'includedColumns'))
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4 px-6">
            {{ $suara->links('vendor.livewire.simple', data: ['scrollTo' => false]) }}
        </div>
    </div>

    <div class="w-[70%] grid grid-cols-2 gap-5 mx-auto">
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
                    {{-- <p class="text-[#6b6b6b] mb-2">
                        @if (strpos($posisi, 'gubernur') !== false)
                            PASLON PILGUB {{ $counter }}
                        @elseif (strpos($posisi, 'bupati') !== false)
                            PASLON PILBUB {{ $counter }}
                        @elseif (strpos($posisi, 'walikota') !== false)
                            PASLON PILWALI {{ $counter }}
                        @endif
                    </p> --}}
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

  <!-- Filter Pilgub Modal -->
  @include(
      'operator.resume.pilgub.filter-modal',
      compact(
          'selectedKabupaten',
          'selectedKecamatan',
          'selectedKelurahan',
          'includedColumns',
          'partisipasi'
      )
  )
</div>
