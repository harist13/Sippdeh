<div>
    <button id="importSuaraTPSBtn" class="flex items-center bg-[#16b335] text-white text-sm font-medium px-4 py-2 rounded-lg sm:w-auto w-full">
        <i class="fas fa-file-import w-4 h-4 mr-2"></i>
        <span>Impor</span>
    </button>

    <!-- Import TPS Modal -->
    <div wire:ignore id="importSuaraTPSModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-30">
        <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
            <form wire:submit="import" enctype="multipart/form-data">
                @csrf
                <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Impor TPS</h3>

                {{-- Spreadsheet --}}
                <label for="importTPS" class="my-2 block">Spreadsheet (.csv atau .xlsx)</label>
                <input
                    type="file"
                    id="tpsSpreadsheet"
                    name="spreadsheet"
                    class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    wire:model="importSpreadsheet"
                >

                {{-- @if ($errors->has('spreadsheet'))
                    <span class="text-red-800 mb-3 block">{{ $errors->first('spreadsheet') }}</span>
                @endif --}}
                
                <p class="text-xs text-gray-500 my-2">
                    Catatan: Pastikan file yang akan diimpor memiliki format yang sama dengan berkas hasil ekspor di Resume per-TPS.
                </p>

                <hr class="h-1 my-3">

                <div class="flex items-center">
                    <button type="button" id="cancelImportSuaraTPS" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
                        Batalkan
                    </button>
                    <button type="button" 
                        wire:loading.attr="disabled" 
                        wire:target="import" 
                        wire:click="import" 
                        class="flex-1 relative bg-[#3560A0] disabled:bg-[#0070F06c] hover:bg-blue-700 text-white rounded-md px-4 py-2">
                        <div class="flex items-center justify-center">
                            <svg wire:loading wire:target="import" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Impor</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@script
    <script>
        console.log('Hello World');

        function showImportSuaraTpsModal() {
            const importSuaraTPSModal = document.getElementById('importSuaraTPSModal');
            importSuaraTPSModal.classList.remove('hidden');
        }

        function closeImportSuaraTpsModal() {
            const importSuaraTPSModal = document.getElementById('importSuaraTPSModal');
            importSuaraTPSModal.classList.add('hidden');
        }

        function initializeImportSuaraTpsModal() {
            document.getElementById('importSuaraTPSBtn').addEventListener('click', showImportSuaraTpsModal);
            document.getElementById('cancelImportSuaraTPS').addEventListener('click', closeImportSuaraTpsModal);

            document.addEventListener('keyup', function(event) {
                if (event.key === "Escape") {
                    closeImportSuaraTpsModal();
                }
            });

            document.addEventListener('click', function(event) {
                if (event.target == importSuaraTPSModal) {
                    closeImportSuaraTpsModal();
                }
            });
        }

        initializeImportSuaraTpsModal();
    </script>
@endscript