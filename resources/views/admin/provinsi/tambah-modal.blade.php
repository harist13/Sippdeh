<!-- Add Provinsi Modal -->
<div id="addProvinsiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
  <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3 text-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Provinsi</h3>
          <div class="mt-2 px-7 py-3">
              <input type="text" id="addProvinsiName"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Nama Provinsi">
          </div>
          <div class="items-center px-4 py-3">
              <button id="cancelAddProvinsi"
                  class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">Batalkan</button>
              <button id="confirmAddProvinsi"
                  class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Tambah</button>
          </div>
      </div>
  </div>
</div>