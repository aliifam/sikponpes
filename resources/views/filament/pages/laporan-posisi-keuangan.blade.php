<x-filament-panels::page>
    @php
        if (isset($_GET['year'])) {
            $dt = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $dt = date('Y');
            $month = date('m');
        }

        // dd($assetData, $liabilityData);

        setlocale(LC_ALL, 'id_ID');

        // $jumlah_debit = 0;
        // $jumlah_kredit = 0;

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
        <button type="button" id="export-excel"
            class="self-end focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 ml-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            Export Excel
        </button>
        <button type="button" id="export"
            class="self-end focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 ml-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            Export PDF
        </button>
    </div>
    {{-- end dropdown and submit --}}

    {{-- start table --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead>
                <th></th>
                <th></th>
            </thead>
            <tbody>
                <tr class="bg-green-600 text-white font-bold">
                    <td style="width:60%" class="p-3">
                        Aktiva
                    </td>
                    <td style="width:10%" class="p-2"></td>
                </tr>
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 font-bold">
                    <td style="width:60%" class="p-3">Aset</td>
                    <td style="width:10%"></td>
                </tr>
                @php
                    $sum = 0;
                @endphp
                @for ($i = 0; $i < sizeof($assetData); $i++)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                            <strong>{{ $assetData[$i]['classification_code'] }} -
                                {{ $assetData[$i]['classification'] }}</strong>
                        </td>
                        <td style="width:10%"></td>
                    </tr>
                    @if (isset($assetData[$i]['name']))
                        @for ($y = 0; $y < sizeof($assetData[$i]['name']); $y++)
                            @if ($assetData[$i]['ending balance'][$y] != '0')
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td style="width:60%;padding-left: 3rem!important;">
                                        {{ $assetData[$i]['code'][$y] }}-
                                        {{ $assetData[$i]['name'][$y] }}
                                    </td>
                                    <td class="text-right px-3 py-2" style="width:10%">
                                        @if ($assetData[$i]['ending balance'][$y] < 0)
                                            -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $assetData[$i]['ending balance'][$y])), 3))) }}
                                        @else
                                            Rp{{ strrev(implode('.', str_split(strrev(strval($assetData[$i]['ending balance'][$y])), 3))) }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endfor
                    @endif
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                            Total {{ $assetData[$i]['classification'] }}
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%">
                            @if ($assetData[$i]['sum'] < 0)
                                -
                                Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $assetData[$i]['sum'])), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($assetData[$i]['sum'])), 3))) }}
                            @endif
                            @php
                                $sum += $assetData[$i]['sum'];
                            @endphp
                        </td>
                    </tr>
                @endfor
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td style="width:60%" class="px-3 py-2">
                        <strong>Total Aktiva</strong>
                    </td>
                    <td class="text-right px-3 py-2" style="width:10%">
                        {{-- @if ($sum < 0)
                            - Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $sum)), 3))) }}
                        @else
                            Rp{{ strrev(implode('.', str_split(strrev(strval($sum)), 3))) }}
                        @endif --}}
                        @if ($aktiva < 0)
                            - Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $aktiva)), 3))) }}
                        @else
                            Rp{{ strrev(implode('.', str_split(strrev(strval($aktiva)), 3))) }}
                        @endif
                    </td>
                </tr>
                <tr class="bg-green-600 text-white font-bold">
                    <td style="width:60%" class="p-3">Pasiva</td>
                    <td style="width:10%"></td>
                </tr>
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 font-bold">
                    <td style="width:60%" class="p-3">Liabilitas</td>
                    <td style="width:10%"></td>
                </tr>
                @for ($i = 0; $i < sizeof($liabilityData); $i++)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                            <strong>{{ $liabilityData[$i]['classification_code'] }} -
                                {{ $liabilityData[$i]['classification'] }}</strong>
                        </td>
                        <td style="width:10%">
                        </td>
                    </tr>
                    @if (isset($liabilityData[$i]['name']))
                        @for ($j = 0; $j < sizeof($liabilityData[$i]['ending balance']); $j++)
                            @if ($liabilityData[$i]['ending balance'][$j] != 0)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td style="width:60%;padding-left: 3rem!important;" class="px-3 py-2">
                                        {{ $liabilityData[$i]['code'][$j] }} -
                                        {{ $liabilityData[$i]['name'][$j] }}
                                    </td>
                                    <td class="text-right px-3 py-2" style="width:10%">
                                        Rp{{ strrev(implode('.', str_split(strrev(strval($liabilityData[$i]['ending balance'][$j])), 3))) }}
                                    </td>
                                </tr>
                            @endif
                        @endfor
                    @endif
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                            Total {{ $liabilityData[$i]['classification'] }}
                        </td>
                        <td style="width:10%" class="text-right px-3 py-2">
                            Rp{{ strrev(implode('.', str_split(strrev(strval(array_sum($liabilityData[$i]['ending balance']))), 3))) }}
                        </td>
                    </tr>
                @endfor
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 font-bold">
                    <td style="width:60%" class="p-3">Ekuitas</td>
                    <td style="width:10%"></td>
                </tr>
                @for ($i = 0; $i < sizeof($equityData); $i++)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                            <strong>{{ $equityData[$i]['classification_code'] }} -
                                {{ $equityData[$i]['classification'] }}</strong>
                        </td>
                        <td style="width:10%">
                        </td>
                    </tr>
                    @if (isset($equityData[$i]['name']))
                        @for ($j = 0; $j < sizeof($equityData[$i]['ending balance']); $j++)
                            @if ($equityData[$i]['ending balance'][$j] != 0)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td style="width:60%;padding-left: 3rem!important;" class="px-3 py-2">
                                        {{ $equityData[$i]['code'][$j] }} -
                                        {{ $equityData[$i]['name'][$j] }}
                                    </td>
                                    <td class="text-right px-3 py-2" style="width:10%">
                                        Rp{{ strrev(implode('.', str_split(strrev(strval($equityData[$i]['ending balance'][$j])), 3))) }}
                                    </td>
                                </tr>
                            @endif
                        @endfor
                    @endif
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                            Total {{ $equityData[$i]['classification'] }}
                        </td>
                        <td style="width:10%" class="text-right px-3 py-2">
                            Rp{{ strrev(implode('.', str_split(strrev(strval(array_sum($equityData[$i]['ending balance']))), 3))) }}
                        </td>
                    </tr>
                @endfor
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td style="width:60%" class="px-3 py-2">
                        <strong>Total Pasiva</strong>
                    </td>
                    <td class="text-right px-3 py-2" style="width:10%">
                        {{-- @if ($sum < 0)
                            - Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $sum)), 3))) }}
                        @else
                            Rp{{ strrev(implode('.', str_split(strrev(strval($sum)), 3))) }}
                        @endif --}}
                        @if ($pasiva < 0)
                            - Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $pasiva)), 3))) }}
                        @else
                            Rp{{ strrev(implode('.', str_split(strrev(strval($pasiva)), 3))) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    @if ($aktiva != $pasiva)
                        <td class="bg-red-600 text-center text-white text-base py-2" colspan="2">
                            Aktiva dan Pasiva Belum Balance
                        </td>
                    @else
                        <td class="bg-green-600 text-center text-white text-base py-2" colspan="2">
                            Aktiva dan Pasiva Sudah Balance
                        </td>
                    @endif
                </tr>
                {{-- <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td style="width:60%" class="px-3 py-2">
                        <strong>Total Biaya</strong>
                    </td>
                    <td class="text-right px-3 py-2" style="width:10%">
                        @if ($expense < 0)
                            <strong>-Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $expense)), 3))) }}</strong>
                        @else
                            <strong>Rp{{ strrev(implode('.', str_split(strrev(strval($expense)), 3))) }}</strong>
                        @endif
                    </td>
                </tr>
                @if ($income > $expense)
                    <tr class="bg-green-600 text-white font-bold">
                        <td style="width:60%" class="px-3 py-4">
                            <strong>Surplus</strong>
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%" class="p-2">
                            <strong>
                                @if ($income - $expense < 0)
                                    -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * ($income - $expense))), 3))) }}
                                @else
                                    Rp{{ strrev(implode('.', str_split(strrev(strval($income - $expense)), 3))) }}
                                @endif
                            </strong>
                        </td>
                    </tr>
                @elseif ($income < $expense)
                    <tr class="bg-red-600 text-white font-bold">
                        <td style="width:60%" class="px-3 py-4">
                            <strong>Defisit</strong>
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%" class="p-2">
                            <strong>
                                @if ($income - $expense < 0)
                                    -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * ($income - $expense))), 3))) }}
                                @else
                                    Rp{{ strrev(implode('.', str_split(strrev(strval($income - $expense)), 3))) }}
                                @endif
                            </strong>
                        </td>
                    </tr>
                @endif --}}
            </tbody>
        </table>
    </div>

    {{-- @if ($aktiva != $pasiva)
        <div class="flex items-center justify-center mt-4">
            <div class="flex items-center justify-center bg-red-600 text-white font-bold rounded-lg px-4 py-2">
                <p class="text-sm">Aktiva & Pasiva Belum Balance</p>
            </div>
        </div>
    @else
        <div class="flex items-center justify-center mt-4">
            <div class="flex items-center justify-center bg-green-600 text-white font-bold rounded-lg px-4 py-2">
                <p class="text-sm">Aktiva & Pasiva sudah Balance</p>
            </div>
        </div>
    @endif --}}
    {{-- end table --}}

    <script>
        // filter by year and month
        document.getElementById('search').addEventListener('click', function() {
            var year = document.getElementById('years').value;
            var month = document.getElementById('months').value;
            window.location.href = 'laporan-posisi-keuangan?year=' + year + '&month=' + month;
        });

        // export pdf
        document.getElementById('export').addEventListener('click', function() {
            var document = @json($endpoint);
            var url = "{{ route('export.laporan-posisi-keuangan') }}?document=" + document;
            window.open(url);
        });

        //export and download excel
        document.getElementById('export-excel').addEventListener('click', function() {
            var document = @json($endpoint);
            var url = "{{ route('export.laporan-posisi-keuangan-excel') }}?document=" + document;
            window.open(url);
        });
    </script>
</x-filament-panels::page>
