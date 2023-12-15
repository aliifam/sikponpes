<x-filament-panels::page>
    @php
        if (isset($_GET['year'], $_GET['perusahaan'])) {
            $year = $_GET['year'];
            $perusahaan = $_GET['perusahaan'];
        } else {
            $year = date('Y');
            $perusahaan = $perusahaan;
        }
        $debit = 0;
        $kredit = 0;
    @endphp
    <h2 class="text-lg font-semibold mt-0">{{ $perusahaanName }} Periode {{ $year }}</h2>

    {{-- start dropdown and submit --}}
    <div class="flex items-center">
        <div class="mr-4">
            <label for="years" class="text-sm font-medium text-gray-900 dark:text-white">
                Tahun:</label>
            <select id="years"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 pr-6 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
                @foreach ($years as $y)
                    <option value="{{ $y->year }}" {{ $year == $y->year ? 'selected' : '' }}>{{ $y->year }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="ml-2 mr-4">
            <label class="text-sm font-medium text-gray-900 dark:text-white">
                Perusahaan:</label>
            <select id="perusahaan"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 pr-6 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
                @foreach ($perusahaans as $p)
                    <option value="{{ $p->id }}" {{ $p->id == $perusahaan ? 'selected' : '' }}>
                        {{ $p->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="button" id="search"
            class="self-end focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 ml-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            Submit
        </button>
    </div>
    {{-- end dropdown and submit --}}

    {{-- start table --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="main-table">
            <thead class="text-base text-white bg-green-600">
                <tr>
                    <th rowspan="2" class="px-6 py-3" style="width:20%">Tanggal</th>
                    <th rowspan="2" class="px-6 py-3" style="width:30%">Keterangan</th>
                    <th colspan="2" class="px-6 py-3 text-center">Posisi</th>
                </tr>
                <tr>
                    <th class="px-6 py-3 text-center">Debit</th>
                    <th class="px-6 py-3 text-center">Kredit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
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
                    </tr>
                @endforeach
                {{-- accumulation and total --}}
                @if ($data->count() > 0)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-3 text-left" colspan="2">Total</td>
                        <td class="px-6 py-3 text-center">
                            @if ($debit < 0)
                                -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $debit)), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($debit)), 3))) }}
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if ($kredit < 0)
                                -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $kredit)), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($kredit)), 3))) }}
                            @endif
                        </td>
                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-3 text-left" colspan="2">Saldo</td>
                        {{-- saldo utang adalah kredit - debit --}}
                        <td class="px-6 py-3 text-center" colspan="2">
                            @if ($kredit - $debit < 0)
                                -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * ($kredit - $debit))), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($kredit - $debit)), 3))) }}
                            @endif
                        </td>
                    </tr>
                @endif
                {{-- end accumulation and total --}}
                {{-- single row displayed empty --}}
                @if ($data->count() == 0)
                    {{-- single row displayed empty --}}
                    <td class="px-6 py-3 text-center" colspan="4">Data Kosong</td>
                @endif
            </tbody>
        </table>
    </div>
    {{-- end table --}}
    <script>
        document.getElementById('search').addEventListener('click', function() {
            const year = document.querySelector('#years').value;
            const perusahaan = document.querySelector('#perusahaan').value;
            window.location.href = `?year=${year}&perusahaan=${perusahaan}`;
        });
    </script>
</x-filament-panels::page>
