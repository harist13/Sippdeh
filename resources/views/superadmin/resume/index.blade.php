@extends('Superadmin.layout.app')

@section('content')
<main class="container mx-auto px-4 py-8">
    @if($showPilgub)
        <div class="mb-8">
            @livewire('Superadmin.resume.pilgub.per-wilayah.resume-suara-pilgub-per-wilayah')
            @livewire('Superadmin.resume.pilgub.per-tps.resume-suara-pilgub-per-tps')
            @livewire('Superadmin.paslon-pilgub')
        </div>
    @endif

    @if(request()->get('showPilwali'))
        @livewire('Superadmin.resume.pilwali.per-wilayah.resume-suara-pilwali-per-wilayah', ['kabupatenId' => request()->get('kabupatenId')])
        @livewire('Superadmin.resume.pilwali.per-tps.resume-suara-pilwali-per-tps', ['kabupatenId' => request()->get('kabupatenId')])
        @livewire('Superadmin.paslon-pilwali', ['kabupatenId' => request()->get('kabupatenId')])
    @endif

    @if(request()->get('showPilbup'))
        @livewire('Superadmin.resume.pilbup.per-wilayah.resume-suara-pilbup-per-wilayah', ['kabupatenId' => request()->get('kabupatenId')])
        @livewire('Superadmin.resume.pilbup.per-tps.resume-suara-pilbup-per-tps', ['kabupatenId' => request()->get('kabupatenId')])
        @livewire('Superadmin.paslon-pilbup', ['kabupatenId' => request()->get('kabupatenId')])
    @endif
</main>
@endsection