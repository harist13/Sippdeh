@include('operator.layout.header')
<main class="container mx-auto px-4 py-8">
    @livewire('input-suara-pilgub')
</main>

@include('operator.layout.footer')

@include('operator.pilgub.filter-modal')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');

        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    });
</script>

<script>
    // Tutup modal saat tombol esc di tekan
    document.addEventListener('keyup', function(event) {
        if(event.key === "Escape") {
            closeFilterPilgubModal();
        }
    });

    // Tutup modal saat overlay diklik
    document.addEventListener('click', function(event) {
        if (event.target == filterPilgubModal) {
            closeFilterPilgubModal();
        }
    });
</script>