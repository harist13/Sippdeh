 <!-- Footer -->
<footer class="py-6 text-white bg-[#3560A0]">
    <div class="container px-4 mx-auto">
        <div class="flex flex-col items-start space-y-6 md:flex-row md:items-center md:justify-between md:space-y-0">
            <div class="flex flex-col items-start md:flex-row md:items-center">
                <img src="{{ asset('assets/logo.png')}}" alt="Logo" class="w-12 h-12 mb-4 md:mb-0 md:w-15 md:h-16 md:mr-4">
                <div>
                    <h3 class="text-lg font-bold">BADAN KESATUAN BANGSA DAN POLITIK</h3>
                    <p class="text-sm">Jalan Jenderal Sudirman</p>
                    <p class="text-sm">Nomor 1, Samarinda</p>
                    <p class="text-sm">Kalimantan Timur 75117</p>
                </div>
            </div>
            <div class="w-full md:w-auto">
                <h3 class="mb-2 text-lg font-bold">KONTAK</h3>
                <p class="text-sm"><i class="mr-2 fas fa-phone"></i>+62541-733333</p>
                <p class="text-sm"><i class="mr-2 fas fa-envelope"></i>kesbangpolkaltim@gmail.com</p>
            </div>
        </div>
        <div class="mt-6 text-center">
            <p>Kesbangpolkaltim.info</p>
            <p class="mt-2 text-xs text-gray-200">Copyright © 2023 detikcom, All rights reserved</p>
            <div class="flex justify-center mt-2 space-x-4">
                <i class="text-xl fab fa-instagram"></i>
                <i class="text-xl fab fa-twitter"></i>
                <i class="text-xl fab fa-facebook"></i>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    
    

  <script>

    // Get the modal
var modal = document.getElementById("profileModal");

// Get the button that opens the modal
var btn = document.querySelector("a[href='#']:has(i.fas.fa-user-circle)");

// Get the <span> element that closes the modal
var span = document.getElementById("closeModal");

// Get the save button
var saveBtn = document.getElementById("saveProfile");

// When the user clicks the button, open the modal 
btn.onclick = function(event) {
    event.preventDefault();
    modal.classList.remove("hidden");
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.classList.add("hidden");
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.classList.add("hidden");
    }
}

// Handle save profile
saveBtn.onclick = function() {
    var form = document.getElementById("profileForm");
    var formData = new FormData(form);

    fetch('/updateoperator', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Success!',
                text: 'Profile updated successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    modal.classList.add("hidden");
                }
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Error updating profile: ' + data.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while updating the profile',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

        
    // Toggle profile dropdown
    const profileDropdown = document.getElementById('profileDropdown');
    const profileMenu = document.getElementById('profileMenu');

    profileDropdown.addEventListener('click', () => {
        profileMenu.classList.toggle('hidden');
    });

    // Close the dropdown when clicking outside
    document.addEventListener('click', (event) => {
        if (!profileDropdown.contains(event.target) && !profileMenu.contains(event.target)) {
            profileMenu.classList.add('hidden');
        }
    });

    // Sidebar open/close
    const sidebar = document.getElementById('sidebar');
    const openSidebarBtn = document.getElementById('openSidebar');
    const closeSidebarBtn = document.getElementById('closeSidebar');

    openSidebarBtn.addEventListener('click', () => {
        sidebar.classList.remove('hidden');
    });

    closeSidebarBtn.addEventListener('click', () => {
        sidebar.classList.add('hidden');
    });


    // Close sidebar when clicking outside
    document.addEventListener('click', (event) => {
        if (!sidebar.contains(event.target) && !openSidebarBtn.contains(event.target)) {
            sidebar.classList.add('hidden');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    
    sidebarItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            sidebarItems.forEach(i => i.classList.remove('bg-blue-200', 'text-blue-700'));
            
            // Add active class to clicked item
            this.classList.add('bg-blue-200', 'text-blue-700');
            
            // Store active item in localStorage
            localStorage.setItem('activeMenuItem', this.getAttribute('href'));
        });
    });
    
    // Check for active item on page load
    const activeMenuItem = localStorage.getItem('activeMenuItem');
    if (activeMenuItem) {
        const activeItem = document.querySelector(`.sidebar-item[href="${activeMenuItem}"]`);
        if (activeItem) {
            activeItem.classList.add('bg-blue-200', 'text-blue-700');
        }
    }
});
</script>

</body>
</html>