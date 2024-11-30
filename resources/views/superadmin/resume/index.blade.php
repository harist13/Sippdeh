@extends('superadmin.layout.app')

@section('content')
<main class="container mx-auto px-4 py-8">
    @if($showPilgub)
        <div class="mb-8">
            @livewire('superadmin.resume.pilgub.per-wilayah.resume-suara-pilgub-per-wilayah')
            @livewire('superadmin.resume.pilgub.per-tps.resume-suara-pilgub-per-tps')
            @livewire('superadmin.paslon-pilgub', ['kabupatenId' => request()->get('kabupatenId')])
        </div>
    @endif

    @if(request()->get('showPilwali'))
        @livewire('superadmin.resume.pilwali.per-wilayah.resume-suara-pilwali-per-wilayah', ['kabupatenId' => request()->get('kabupatenId')])
        @livewire('superadmin.resume.pilwali.per-tps.resume-suara-pilwali-per-tps', ['kabupatenId' => request()->get('kabupatenId')])
        @livewire('superadmin.paslon-pilwali', ['kabupatenId' => request()->get('kabupatenId')])
    @endif

    @if(request()->get('showPilbup'))
        @livewire('superadmin.resume.pilbup.per-wilayah.resume-suara-pilbup-per-wilayah', ['kabupatenId' => request()->get('kabupatenId')])
        @livewire('superadmin.resume.pilbup.per-tps.resume-suara-pilbup-per-tps', ['kabupatenId' => request()->get('kabupatenId')])
        @livewire('superadmin.paslon-pilbup', ['kabupatenId' => request()->get('kabupatenId')])
    @endif
</main>
@endsection