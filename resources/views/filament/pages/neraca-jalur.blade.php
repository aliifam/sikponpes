<x-filament-panels::page>
    @php
        if (isset($_GET['year'])) {
            $dt = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $dt = date('Y');
            $month = date('m');
        }

        setlocale(LC_ALL, 'id_ID');

        $jumlah_debit = 0;
        $jumlah_kredit = 0;

        //switch month to indonesian name
        switch ($month) {
            case '01':
                $monthName = 'Januari';
                break;
            case '02':
                $monthName = 'Februari';
                break;
            case '03':
                $monthName = 'Maret';
                break;
            case '04':
                $monthName = 'April';
                break;
            case '05':
                $monthName = 'Mei';
                break;
            case '06':
                $monthName = 'Juni';
                break;
            case '07':
                $monthName = 'Juli';
                break;
            case '08':
                $monthName = 'Agustus';
                break;
            case '09':
                $monthName = 'September';
                break;
            case '10':
                $monthName = 'Oktober';
                break;
            case '11':
                $monthName = 'November';
                break;
            case '12':
                $monthName = 'Desember';
                break;
        }
    @endphp
    <h2 class="text-lg font-semibold mt-0">Periode {{ $monthName }} {{ $dt }}</h2>
    {{-- start dropdown and submit --}}

    <div class="flex items-center">
        <div class="mr-2">
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
                Bulan :</label>
            <select id="months"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 pr-6 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
                <option value="01" {{ $month == '01' ? 'selected' : '' }}>Januari</option>
                <option value="02" {{ $month == '02' ? 'selected' : '' }}>Februari</option>
                <option value="03" {{ $month == '03' ? 'selected' : '' }}>Maret</option>
                <option value="04" {{ $month == '04' ? 'selected' : '' }}>April</option>
                <option value="05" {{ $month == '05' ? 'selected' : '' }}>Mei</option>
                <option value="06" {{ $month == '06' ? 'selected' : '' }}>Juni</option>
                <option value="07" {{ $month == '07' ? 'selected' : '' }}>Juli</option>
                <option value="08" {{ $month == '08' ? 'selected' : '' }}>Agustus</option>
                <option value="09" {{ $month == '09' ? 'selected' : '' }}>September</option>
                <option value="10" {{ $month == '10' ? 'selected' : '' }}>Oktober</option>
                <option value="11" {{ $month == '11' ? 'selected' : '' }}>November</option>
                <option value="12" {{ $month == '12' ? 'selected' : '' }}>Desember</option>
            </select>
        </div>
        <button type="button" id="search"
            class="self-end focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 ml-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            Submit
        </button>
        <div class="flex-grow"></div>
        <button type="button" id="export"
            class="self-end focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 ml-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            Export PDF
        </button>
    </div>
    {{-- end dropdown and submit --}}

    {{-- start table --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-base text-white bg-green-600">
                <tr>
                    <th class="px-6 py-3 text-center">Nama Akun</th>
                    <th class="px-6 py-3 text-center">Posisi Normal</th>
                    <th class="px-6 py-3 text-center">Debit</th>
                    <th class="px-6 py-3 text-center">Kredit</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < sizeof($balance); $i++)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-2">
                            <strong>{{ $balance[$i]['parent_code'] }} - {{ $balance[$i]['parent_name'] }}</strong>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @if (isset($balance[$i]['classification']))
                        @for ($j = 0; $j < sizeof($balance[$i]['classification']); $j++)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td style="padding-left: 1.5rem!important;" class="px-6 py-2">
                                    {{ $balance[$i]['classification'][$j]['classification_name'] }}
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @if (isset($balance[$i]['classification'][$j]['account']))
                                @for ($k = 0; $k < sizeof($balance[$i]['classification'][$j]['account']); $k++)
                                    @if ($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] != '0')
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td style="padding-left: 3rem!important;" class="px-6 py-2">
                                                {{ $balance[$i]['classification'][$j]['account'][$k]['account_code'] }}
                                                -
                                                {{ $balance[$i]['classification'][$j]['account'][$k]['account_name'] }}
                                            </td>
                                            <td class="text-center">
                                                {{ $balance[$i]['classification'][$j]['account'][$k]['position'] }}
                                            </td>
                                            <td class="text-center">
                                                @if ($balance[$i]['classification'][$j]['account'][$k]['position'] == 'debit')
                                                    @if ($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] < 0)
                                                        -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])), 3))) }}
                                                    @else
                                                        Rp{{ strrev(implode('.', str_split(strrev(strval($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])), 3))) }}
                                                    @endif
                                                    @php
                                                        $jumlah_debit += $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'];
                                                    @endphp
                                                @endif

                                            </td>
                                            <td class="text-center">
                                                @if ($balance[$i]['classification'][$j]['account'][$k]['position'] == 'kredit')
                                                    @if ($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] < 0)
                                                        -
                                                        Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])), 3))) }}
                                                    @else
                                                        Rp{{ strrev(implode('.', str_split(strrev(strval($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])), 3))) }}
                                                    @endif
                                                    @php
                                                        $jumlah_kredit += $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'];
                                                    @endphp
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endfor
                            @endif
                        @endfor
                    @endif
                @endfor
                <tr class="text-sm text-white uppercase bg-green-600">
                    <td class="px-6 py-3"><strong>Total</strong></td>
                    <td><strong></strong></td>
                    <td class="text-center">
                        <strong>
                            @if ($jumlah_debit < 0)
                                -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $jumlah_debit)), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($jumlah_debit)), 3))) }}
                            @endif
                        </strong>
                    </td>
                    <td class="text-center">
                        <strong>
                            @if ($jumlah_kredit < 0)
                                -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $jumlah_kredit)), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($jumlah_kredit)), 3))) }}
                            @endif
                        </strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    {{-- end table --}}

    <script>
        //search function
        document.getElementById('search').addEventListener('click', function() {
            var year = document.querySelector('#years').value;
            var month = document.querySelector('#months').value;
            window.location.href = 'neraca-jalur?year=' + year + '&month=' + month;
        });

        //export pdf function in new tab
        document.getElementById('export').addEventListener('click', function() {
            var document = @json($endpoint);
            var url = "{{ route('export.neraca_lajur') }}?document=" + document;
            window.open(url);
        });
    </script>
</x-filament-panels::page>
