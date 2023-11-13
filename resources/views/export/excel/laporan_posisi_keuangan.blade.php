<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" href="/images/favicon.svg" type="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        Laporan Posisi Keuangan
    </title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <style>
        * {
            font-size: 100%;
            font-family: sans-serif;
        }

        .surplus {
            background: #58ff7a;
        }

        .defisit {
            background: #ff5858;
        }

        .text-center {
            text-align: center
        }

        .text-right {
            text-align: right
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
            font-size: 13px;
        }

        th {
            background: #58ff7a;
        }

        .company {
            font-weight: 400;
            text-transform: uppercase;
            margin: 0px;
            font-size: 18px;
        }
    </style>
</head>

<body>
    @php
        // $dt = $_GET['year'];
        // $month = $_GET['month'];

        setlocale(LC_ALL, 'id_ID');

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
    <div>
        <div>
            <h3 class="text-center company">{{ $pesantren->name }}</h3>
            <h3 class="text-center company">Laporan Posisi Keuangan</h3>
            <p class="text-center" style="margin:5px; margin-bottom:10px"><strong>Periode</strong>
                {{ $monthName }} {{ $year }} </p>
        </div>
        <br>
        <br>
        <div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" width="100%" style="width:100%">
                <tbody>
                    <tr class="bg-green-600 text-white font-bold">
                        <th style="width:60%" class="p-3" colspan="2">
                            Aktiva
                        </th>

                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 font-bold">
                        <td style="width:60%" class="p-3" colspan="2">Aset</td>
                    </tr>
                    @php
                        $sum = 0;
                    @endphp
                    @for ($i = 0; $i < sizeof($assetData); $i++)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2" colspan="2">
                                <strong>{{ $assetData[$i]['classification_code'] }} -
                                    {{ $assetData[$i]['classification'] }}</strong>
                            </td>

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
                        <th style="width:60%" class="p-3" colspan="2">Pasiva</th>
                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 font-bold">
                        <td style="width:60%" class="p-3" colspan="2">Liabilitas</td>
                    </tr>
                    @for ($i = 0; $i < sizeof($liabilityData); $i++)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2" colspan="2">
                                <strong>{{ $liabilityData[$i]['classification_code'] }} -
                                    {{ $liabilityData[$i]['classification'] }}</strong>
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
                                            @if ($liabilityData[$i]['ending balance'][$j] < 0)
                                                -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $liabilityData[$i]['ending balance'][$j])), 3))) }}
                                            @else
                                                Rp{{ strrev(implode('.', str_split(strrev(strval($liabilityData[$i]['ending balance'][$j])), 3))) }}
                                            @endif
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
                                @if (array_sum($liabilityData[$i]['ending balance']) < 0)
                                    -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * array_sum($liabilityData[$i]['ending balance']))), 3))) }}
                                @else
                                    Rp{{ strrev(implode('.', str_split(strrev(strval(array_sum($liabilityData[$i]['ending balance']))), 3))) }}
                                @endif
                            </td>
                        </tr>
                    @endfor
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 font-bold">
                        <td style="width:60%" class="p-3" colspan="2">Ekuitas</td>
                    </tr>
                    @for ($i = 0; $i < sizeof($equityData); $i++)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2" colspan="2">
                                <strong>{{ $equityData[$i]['classification_code'] }} -
                                    {{ $equityData[$i]['classification'] }}</strong>
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
                                            @if ($equityData[$i]['ending balance'][$j] < 0)
                                                -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $equityData[$i]['ending balance'][$j])), 3))) }}
                                            @else
                                                Rp{{ strrev(implode('.', str_split(strrev(strval($equityData[$i]['ending balance'][$j])), 3))) }}
                                            @endif
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
                                @if (array_sum($equityData[$i]['ending balance']) < 0)
                                    -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * array_sum($equityData[$i]['ending balance']))), 3))) }}
                                @else
                                    Rp{{ strrev(implode('.', str_split(strrev(strval(array_sum($equityData[$i]['ending balance']))), 3))) }}
                                @endif
                            </td>
                        </tr>
                    @endfor
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                            <strong>Surplus/Desifisit</strong>
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%">
                            @if ($surpdef < 0)
                                - Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $surpdef)), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($surpdef)), 3))) }}
                            @endif
                        </td>
                    </tr>
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
                            <td class="bg-red-600 text-center text-white text-base py-2 defisit" colspan="2">
                                Aktiva dan Pasiva Belum Balance
                            </td>
                        @else
                            <td class="bg-green-600 text-center text-white text-base py-2 surplus" colspan="2">
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
        <!-- end content-->
    </div>
</body>

</html>
