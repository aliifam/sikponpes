<x-filament-panels::page>
    @php
        if (isset($_GET['year'], $_GET['akun'])) {
            $year = $_GET['year'];
            $akun = $_GET['akun'];
        } else {
            $year = date('Y');
            $akun = $account;
        }

        $saldo_awal = $log['saldo_awal'];
        $saldo_akhir = $log['saldo_awal'];
        $debit = 0;
        $kredit = 0;
    @endphp
    <h2 class="text-lg font-semibold mt-0">Periode {{ $year }}</h2>
    {{-- start dropdown and submit --}}
    <div class="flex items-center">
        <div class="mr-4">
            <label for="years" class="text-sm font-medium text-gray-900 dark:text-white">
                Tahun:</label>
            <select wire:model="year"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 pr-6 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
                @foreach ($years as $y)
                    <option value="{{ $y->year }}" {{ $year == $y->year ? 'selected' : '' }}>{{ $y->year }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="ml-4 mr-4">
            <label for="accounts" class="text-sm font-medium text-gray-900 dark:text-white">
                Akun:</label>
            <select wire:model="account_id"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 pr-6 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
                @foreach ($accounts as $a)
                    <option wire:key="{{ $a->id }}" value="{{ $a->id }}">{{ $a->account_code }} -
                        {{ $a->account_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="button"
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
                <p>: {{ $log['nama_akun'] }}</p>
                <p>: {{ $log['kode_akun'] }}</p>
                <p>: {{ $log['position'] }}</p>
            </div>
        </div>
        <div class="flex ml-16">
            <div>
                <p>Saldo Awal</p>
                <p>Saldo Akhir</p>
            </div>
            <div class="ml-8">
                <p>: Rp{{ strrev(implode('.', str_split(strrev(strval($saldo_awal)), 3))) }}</p>
                <p>: <span id="saldo_akhir"></span></p>
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
                <tr class="font-semibold dark:bg-slate-700 dark:text-white">
                    <td class="px-6 py-3">{{ strftime('%d %B %G', strtotime($log['date'])) }}</td>
                    <td class="px-6 py-3">Saldo Awal</td>
                    <td class="px-6 py-3 text-center"></td>
                    <td class="px-6 py-3 text-center"></td>
                    <td class="px-6 py-3">
                        Rp{{ strrev(implode('.', str_split(strrev(strval($log['saldo_awal'])), 3))) }}
                    </td>
                </tr>

                @foreach ($data as $d)
                    <tr class="dark:bg-slate-700 dark:text-white">
                        <td class="px-6 py-3">{{ strftime('%d %B %G', strtotime($d->detail->date)) }}</td>
                        <td class="px-6 py-3">{{ $d->detail->description }}</td>
                        <td class="px-6 py-3 text-center">
                            @if ($d->position == 'debit')
                                @if ($d->amount < 0)
                                    -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $d->amount)), 3))) }}
                                @else
                                    Rp{{ strrev(implode('.', str_split(strrev(strval($d->amount)), 3))) }}
                                @endif
                                @php
                                    $debit += $d->amount;
                                @endphp
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if ($d->position == 'credit')
                                @if ($d->amount < 0)
                                    -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $d->amount)), 3))) }}
                                @else
                                    Rp{{ strrev(implode('.', str_split(strrev(strval($d->amount)), 3))) }}
                                @endif
                                @php
                                    $kredit += $d->amount;
                                @endphp
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            @if ($log['position'] == 'debit')
                                @if ($d->position == 'credit')
                                    @php
                                        $saldo_akhir -= $d->amount;
                                    @endphp
                                @elseif ($d->position == 'debit')
                                    @php
                                        $saldo_akhir += $d->amount;
                                    @endphp
                                @endif
                            @elseif ($log['position'] == 'kredit')
                                @if ($d->position == 'credit')
                                    @php
                                        $saldo_akhir += $d->amount;
                                    @endphp
                                @elseif ($d->position == 'debit')
                                    @php
                                        $saldo_akhir -= $d->amount;
                                    @endphp
                                @endif
                            @endif
                            @if ($saldo_akhir < 0)
                                -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $saldo_akhir)), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($saldo_akhir)), 3))) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- end table --}}
</x-filament-panels::page>

@push('js')
@endpush
