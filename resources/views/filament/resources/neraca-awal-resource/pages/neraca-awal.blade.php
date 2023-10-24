<x-filament-panels::page>
    <h2 class="text-lg font-semibold mt-0">Periode {{ $year }}</h2>
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <label for="years" class="mr-4 text-sm font-medium text-gray-900 dark:text-white">
                Tahun:</label>
            <select wire:model="year"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 pr-6 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
                @foreach ($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
            <button type="button" wire:click="submit"
                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 ml-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Submit
            </button>
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
                <th scope="col" class="px-6 py-3" rowspan="2">Akun</th>
                <th scope="col" class="px-6 py-3" rowspan="2">Debit</th>
                <th scope="col" class="px-6 py-3" rowspan="2">Kredit</th>
                <th scope="col" class="px-6 py-3" rowspan="2">Aksi</th>
            </thead>
            <tbody>
                {{-- <tr>
                    <td class="px-6 py-3">1 - Asset</td>
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3">
                        <button class="btn btn-sm btn-primary"></button>
                        <button class="btn btn-sm btn-danger"></button>
                    </td>
                </tr> --}}
                @foreach ($initialBalances as $initialBalance)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="pl-8 px-6 py-3">{{ $initialBalance->account->account_code }} -
                            {{ $initialBalance->account->account_name }}</td>
                        @if ($initialBalance->account->position == 'debit')
                            <td class="px-6 py-3">
                                Rp{{ strrev(implode('.', str_split(strrev(strval($initialBalance->amount)), 3))) }}</td>
                            <td class="px-6 py-3"></td>
                        @else
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3">
                                Rp{{ strrev(implode('.', str_split(strrev(strval($initialBalance->amount)), 3))) }}</td>
                            </td>
                        @endif
                        <td class="px-6 py-3">
                            <a href="neraca-awals/{{ $initialBalance->id }}/edit">Edit</a>
                            <a class="cursor-pointer" wire:click="delete({{ $initialBalance->id }})">Delete</a>
                        </td>
                    </tr>
                @endforeach

                @if ($balanceStatus == 'Unbalance')
                    <tr class="bg-red-500 text-white border-b">
                        <td class="px-6 py-3">Total</td>
                        <td class="px-6 py-3">Rp{{ strrev(implode('.', str_split(strrev(strval($totalDebit)), 3))) }}
                        </td>
                        <td class="px-6 py-3">Rp{{ strrev(implode('.', str_split(strrev(strval($totalKredit)), 3))) }}
                        </td>
                        <td class="px-6 py-3">
                            Tidak Balance
                        </td>
                    </tr>
                @else
                    <tr class="bg-green-500 text-white border-b">
                        <td class="px-6 py-3">Total</td>
                        <td class="px-6 py-3">Rp{{ strrev(implode('.', str_split(strrev(strval($totalDebit)), 3))) }}
                        </td>
                        <td class="px-6 py-3">Rp{{ strrev(implode('.', str_split(strrev(strval($totalKredit)), 3))) }}
                        </td>
                        <td class="px-6 py-3">
                            Sudah Balance
                        </td>
                    </tr>
                @endif


            </tbody>
        </table>
</x-filament-panels::page>
