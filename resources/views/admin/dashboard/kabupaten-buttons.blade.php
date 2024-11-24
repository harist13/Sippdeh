<div class="grid grid-cols-5 gap-4 mb-8">
    @foreach($kabupatens as $kabupaten)
        <button
            wire:click="navigateToResume('{{ $kabupaten->slug }}')"
            class="bg-[#3560A0] text-white py-2 px-4 rounded text-center hover:bg-[#2a4d8a] transition-colors duration-200">
            {{ str_replace(['Kabupaten ', 'Kota '], '', $kabupaten->nama) }}
        </button>
    @endforeach
</div>