@extends('admin.layout.app')

@push('head-scrpts')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endpush

@push('styles')
    <style>
        #tpsBtn,
        #suaraBtn,
        #pilgubBtn {
            margin-right: 1px;
        }

        .btn-group button {
            margin: 0;
            padding: 10px 20px;
            border: none;
        }

        .btn-group button:not(:last-child) {
            border-right: 1px solid #d1d5db;
        }

        @media (max-width: 640px) {
            .container {
                padding: 1rem;
            }

            .flex-wrap-mobile {
                flex-wrap: wrap;
            }

            .w-full-mobile {
                width: 100%;
            }

            .mt-4-mobile {
                margin-top: 1rem;
            }

            .overflow-x-auto {
                overflow-x: auto;
            }

            .btn-group button {
                padding: 8px 12px;
                font-size: 0.875rem;
            }
        }

    </style>
@endpush

@section('content')
    <main class="container flex-grow px-4 mx-auto mt-6">
        <div class="container mx-auto p-4 sm:p-6 bg-white rounded-lg shadow-md">
            <!-- Section Header -->
        <livewire:admin.vote-summary-provinsi />
        <livewire:admin.vote-summary-kabupaten />

            

        </div>
    </main>
@endsection

@push('scripts')
    <script>
        // Dropdown functionality
        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownMenu = document.getElementById('dropdownMenu');

        dropdownButton.addEventListener('click', function () {
            dropdownMenu.classList.toggle('hidden');
        });

        // Close the dropdown when clicking outside of it
        document.addEventListener('click', function (event) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });

        // Optional: Update button text when an option is selected
        const dropdownItems = dropdownMenu.querySelectorAll('li');
        dropdownItems.forEach(item => {
            item.addEventListener('click', function () {
                dropdownButton.querySelector('span').textContent = this.textContent;
                dropdownMenu.classList.add('hidden');
            });
        });

        // Filter dropdown functionality
        const filterButton = document.getElementById('filterButton');
        const filterMenu = document.getElementById('filterMenu');

        filterButton.addEventListener('click', function () {
            filterMenu.classList.toggle('hidden');
        });

        // Close the filter dropdown when clicking outside of it
        document.addEventListener('click', function (event) {
            if (!filterButton.contains(event.target) && !filterMenu.contains(event.target)) {
                filterMenu.classList.add('hidden');
            }
        });

        // Update filter button text when an option is selected
        const filterItems = filterMenu.querySelectorAll('li');
        filterItems.forEach(item => {
            item.addEventListener('click', function () {
                const colorName = this.textContent.trim();
                filterButton.querySelector('span').textContent = colorName;
                filterButton.querySelector('span').className = this.querySelector('span').className;
                filterMenu.classList.add('hidden');
                // You can add filtering logic here based on the selected color
            });
        });
    </script>
@endpush
