<x-filament-panels::page>
    <label for="countries" class="block text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
    <select id="countries"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
        <option selected>Choose a country</option>
        <option value="US">United States</option>
        <option value="CA">Canada</option>
        <option value="FR">France</option>
        <option value="DE">Germany</option>
    </select>

    <label for="countries" class="block text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
    <select id="countries"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
        <option selected>Choose a country</option>
        <option value="US">United States</option>
        <option value="CA">Canada</option>
        <option value="FR">France</option>
        <option value="DE">Germany</option>
    </select>

    <label for="countries" class="block text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
    <select id="countries"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
        <option selected>Choose a country</option>
        <option value="US">United States</option>
        <option value="CA">Canada</option>
        <option value="FR">France</option>
        <option value="DE">Germany</option>
    </select>


    <button type="button"
        class="focus:outline-none text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
        Tambah Jurnal Umum
    </button>


    <form>
        <label for="default-search"
            class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input type="search" id="default-search"
                class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500"
                placeholder="Cari Jurnal Umum..." required>
            <button type="submit"
                class="text-white absolute right-2.5 bottom-2.5 bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Search</button>
        </div>
    </form>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-sm text-white uppercase bg-green-600">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center" rowspan="2">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-center" rowspan="2">No Kwitansi</th>
                    <th scope="col" class="px-6 py-3 text-center" rowspan="2">Keterangan</th>
                    <th scope="col" class="px-6 py-3 text-center" colspan="2">Nama Akun</th>
                    <th scope="col" class="px-6 py-3 text-center" colspan="2">Jumlah</th>
                    <th scope="col" class="px-6 py-3 text-center" rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">Debit</th>
                    <th scope="col" class="px-6 py-3 text-center">Kredit</th>
                    <th scope="col" class="px-6 py-3 text-center">Debit</th>
                    <th scope="col" class="px-6 py-3 text-center">Kredit</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="text-center px-6 py-3">01/01/2021</td>
                    <td class="text-center px-6 py-3">0001</td>
                    <td class="text-center px-6 py-3">Pembelian Barang</td>
                    <td class="text-center px-6 py-3">Kas</td>
                    <td class="text-center px-6 py-3"></td>
                    <td class="text-center px-6 py-3">Rp. 1.000.000</td>
                    <td class="text-center px-6 py-3"></td>
                    <td class="text-center px-6 py-3">
                        <button class="btn btn-sm btn-primary">Edit</button>
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </td>
                </tr>
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="text-center px-6 py-3">01/01/2021</td>
                    <td class="text-center px-6 py-3">0001</td>
                    <td class="text-center px-6 py-3">Pembelian Barang</td>
                    <td class="text-center px-6 py-3"></td>
                    <td class="text-center px-6 py-3">Utang</td>
                    <td class="text-center px-6 py-3"></td>
                    <td class="text-center px-6 py-3">Rp. 1.000.000</td>
                    <td class="text-center px-6 py-3">
                        <button class="btn btn-sm btn-primary">Edit</button>
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </td>
                </tr>


            </tbody>
        </table>

        <script>
            //on select year change
            document.addEventListener('livewire:load', function() {
                Livewire.on('selectYearChanged', function(e) {
                    console.log(e);
                })
            })
        </script>

</x-filament-panels::page>
