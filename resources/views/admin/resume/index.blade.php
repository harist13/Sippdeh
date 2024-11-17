@extends('admin.layout.app')

@section('content')
    <main class="container mx-auto px-4 py-8">

        {{-- Tampilkan Pilgub hanya jika tidak ada wilayah yang dipilih --}}
        @if(!$wilayah)
            <div class="mb-10">
                @livewire('admin.resume.pilgub.resume-suara-pilgub', ['wilayah' => $wilayah])
            </div>
        @endif

        @php
            $kotaList = ['samarinda', 'balikpapan', 'bontang'];
        @endphp
        
        @if(!$wilayah || in_array($wilayah, $kotaList))
            <div class="mb-10">
                @livewire('admin.resume.pilwali.resume-suara-pilwali', ['wilayah' => $wilayah])
            </div>
        @endif

        @php
            $kabupatenList = ['kutai-kartanegara', 'kutai-timur', 'kutai-barat', 'berau', 
                             'paser', 'penajam-paser-utara', 'mahakam-ulu'];
        @endphp
        
        @if(!$wilayah || in_array($wilayah, $kabupatenList))
            <div class="mb-10">
                @livewire('admin.resume.pilbup.resume-suara-pilbup', ['wilayah' => $wilayah])
            </div>
        @endif
    </main>
@endsection