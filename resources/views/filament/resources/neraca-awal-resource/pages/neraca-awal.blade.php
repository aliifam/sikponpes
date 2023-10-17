<x-filament-panels::page>
    <h2 class="text-lg font-semibold mt-0">Periode {{ $year }}</h2>
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <label for="countries" class="mr-4 text-sm font-medium text-gray-900 dark:text-white">
                Tahun:</label>
            <select wire:model="year"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 pr-6 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
                @foreach ($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>

        <button type="button" wire:click="create"
            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            Tambah Neraca Awal
        </button>

    </div>

    {{-- table --}}

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



</x-filament-panels::page>
