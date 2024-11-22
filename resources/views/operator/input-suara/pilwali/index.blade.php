@extends('operator.layout.app')

@section('content')
    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-[20px] w-[100%] lg:w-[80%] xl:w-[50%] mb-8 shadow-lg">
            @livewire('operator.input-suara.pilwali.input-daftar-pemilih-pilwali')
        </div>
        <div class="bg-white rounded-[20px] mb-8 shadow-lg">
            @livewire('operator.input-suara.pilwali.input-suara-pilwali')
        </div>
    </main>
@endsection 