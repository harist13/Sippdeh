@extends('superadmin.layout.app')

@push('styles')
    <style>
        .container {
            max-width: 1200px;
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        table tr:nth-child(even) {
            background-color: #f8f8f8;
        }
        
        /* Responsive styles */
        @media (max-width: 1024px) {
            .flex-col-mobile {
                flex-direction: column;
            }
            .w-full-mobile {
                width: 100%;
            }
            .space-y-2-mobile > * + * {
                margin-top: 0.5rem;
            }
            .mt-4-mobile {
                margin-top: 1rem;
            }
            .px-2-mobile {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            .text-sm-mobile {
                font-size: 0.875rem;
            }
            .overflow-x-auto {
                overflow-x: auto;
            }
        }
    </style>
@endpush

@section('content')
    <main class="container flex-grow px-4 mx-auto mt-6">
        @php $status = session('pesan_sukses'); @endphp
        @isset ($status)
            @include('components.alert-berhasil', ['message' => $status])
        @endisset

        @php $status = session('pesan_gagal'); @endphp
        @isset ($status)
            @include('components.alert-gagal', ['message' => $status])
        @endisset
        
        @php $catatanImpor = session('catatan_impor'); @endphp
        @isset ($catatanImpor)
            <div class="bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold mb-1 block">Catatan pengimporan:</strong>
                <ul class="list-disc ms-5">
                    @foreach ($catatanImpor as $catatan)
                        <li>{!! $catatan !!}</li>
                    @endforeach
                </ul>
            </div>
        @endisset

        @livewire('superadmin.calon')
    </main>

    @include('superadmin.calon.tambah-modal')
    @include('superadmin.calon.edit-modal')
    @include('superadmin.calon.hapus-modal')
    @include('superadmin.calon.hapus-gambar-modal')
@endsection

@push('scripts')
    <script>
        // Tutup modal saat tombol esc di tekan
        document.addEventListener('keyup', function(event) {
            if (event.key === "Escape") {
                closeAddCalonModal();
                closeEditCalonModal();
                closeDeleteCalonModal();
            }
        });

        document.addEventListener('click', function(event) {
            if (event.target == addCalonModal) {
                closeAddCalonModal();
            }

            if (event.target == editCalonModal) {
                closeEditCalonModal();
            }

            if (event.target == deleteCalonModal) {
                closeDeleteCalonModal();
            }
        });
    </script>
@endpush