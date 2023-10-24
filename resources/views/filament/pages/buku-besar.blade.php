<x-filament-panels::page>
    <h2 class="text-lg font-semibold mt-0">Periode {{ $year }}</h2>
    {{-- start dropdown and submit --}}
    <div class="flex items-center">
        <div class="mr-4">
            <label for="years" class="text-sm font-medium text-gray-900 dark:text-white">
                Tahun:</label>
            <select wire:model="year"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 pr-6 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
                @foreach ($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="ml-4 mr-4">
            <label for="accounts" class="text-sm font-medium text-gray-900 dark:text-white">
                Akun:</label>
            <select wire:model="account"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 pr-6 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->account_code }} - {{ $account->account_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="button" wire:click="submit"
            class="self-end focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 ml-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            Submit
        </button>
    </div>
    {{-- end dropdown and submit --}}

    {{-- start detail --}}
    <div class="flex">
        <div class="flex">
            <div>
                <p>Nama Akun</p>
                <p>Kode Akun</p>
                <p>Posisi Normal</p>
            </div>
            <div class="ml-8">
                <p>: kas</p>
                <p>: 110</p>
                <p>: Debit</p>
            </div>
        </div>
        <div class="flex ml-16">
            <div>
                <p>Saldo Awal</p>
                <p>Saldo Akhir</p>
            </div>
            <div class="ml-8">
                <p>: kas</p>
                <p>: 110</p>
            </div>
        </div>
    </div>
    {{-- end detail --}}

    {{-- start table --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-sm text-white uppercase bg-green-600">
                <tr>
                    <th rowspan="2" class="px-6 py-3" style="width:15%">Tanggal</th>
                    <th rowspan="2" class="px-6 py-3" style="width:30%">Keterangan</th>
                    <th colspan="2" class="px-6 py-3 text-center">Posisi</th>
                    <th rowspan="2" class="px-6 py-3">Saldo</th>
                </tr>
                <tr>
                    <th class="px-6 py-3 text-center">Debit</th>
                    <th class="px-6 py-3 text-center">Kredit</th>
                </tr>
            </thead>
            <tbody>
                <tr class="font-semibold">
                    <td class="px-6 py-3">1 Januari 2021</td>
                    <td class="px-6 py-3">Saldo Awal</td>
                    <td class="px-6 py-3 text-center"></td>
                    <td class="px-6 py-3 text-center"></td>
                    <td class="px-6 py-3">Rp 0</td>
                </tr>
            </tbody>
        </table>
    </div>
    {{-- end table --}}
</x-filament-panels::page>
