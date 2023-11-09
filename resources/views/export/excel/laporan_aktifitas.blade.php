<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" href="/images/favicon.svg" type="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        Laporan Aktivitas
    </title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <style>
        * {
            font-size: 100%;
            font-family: sans-serif;
        }

        .text-center {
            text-align: center
        }

        .text-right {
            text-align: right
        }

        .surplus {
            background: #58ff7a;
        }

        .defisit {
            background: #ff5858;
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
            <h3 class="text-center company">Laporan Aktivitas</h3>
            <p class="text-center" style="margin:5px; margin-bottom:10px"><strong>Periode </strong>
                {{ $monthName }} {{ $year }} </p>
        </div>
        <br>
        <br>
        <div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" width="100%" style="width:100%">
                <tbody>
                    <tr class="bg-green-600 text-white font-bold">
                        <th style="width:60%" class="p-3" colspan="2">
                            Pendapatan
                        </th>
                    </tr>
                    @for ($i = 0; $i < sizeof($incomeData); $i++)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                                <strong>{{ $incomeData[$i]['classification_code'] }} -
                                    {{ $incomeData[$i]['classification'] }}</strong>
                            </td>
                            <td style="width:10%"></td>
                        </tr>
                        @if (isset($incomeData[$i]['name']))
                            @for ($y = 0; $y < sizeof($incomeData[$i]['name']); $y++)
                                @if ($incomeData[$i]['ending balance'][$y] != '0')
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td style="width:60%;padding-left: 3rem!important;">
                                            {{ $incomeData[$i]['code'][$y] }}-
                                            {{ $incomeData[$i]['name'][$y] }}
                                        </td>
                                        <td class="text-right px-3 py-2" style="width:10%">
                                            @if ($incomeData[$i]['ending balance'][$y] < 0)
                                                -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $incomeData[$i]['ending balance'][$y])), 3))) }}
                                            @else
                                                Rp{{ strrev(implode('.', str_split(strrev(strval($incomeData[$i]['ending balance'][$y])), 3))) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endfor
                        @endif
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                                Total {{ $incomeData[$i]['classification'] }}
                            </td>
                            <td class="text-right px-3 py-2" style="width:10%">
                                Rp{{ strrev(implode('.', str_split(strrev(strval(array_sum($incomeData[$i]['ending balance']))), 3))) }}
                            </td>
                        </tr>
                    @endfor
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%" class="px-3 py-2">
                            <strong>Total Pendapatan</strong>
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%">
                            Rp{{ strrev(implode('.', str_split(strrev(strval($income)), 3))) }}
                        </td>
                    </tr>
                    <tr class="bg-green-600 text-white font-bold">
                        <th style="width:60%" class="p-3" colspan="2">Biaya</th>
                    </tr>
                    @for ($i = 0; $i < sizeof($expenseData); $i++)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                                <strong>{{ $expenseData[$i]['classification_code'] }} -
                                    {{ $expenseData[$i]['classification'] }}</strong>
                            </td>
                            <td style="width:10%">
                            </td>
                        </tr>
                        @if (isset($expenseData[$i]['name']))
                            @for ($j = 0; $j < sizeof($expenseData[$i]['ending balance']); $j++)
                                @if ($expenseData[$i]['ending balance'][$j] != 0)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td style="width:60%;padding-left: 3rem!important;" class="px-3 py-2">
                                            {{ $expenseData[$i]['code'][$j] }} -
                                            {{ $expenseData[$i]['name'][$j] }}
                                        </td>
                                        <td class="text-right px-3 py-2" style="width:10%">
                                            Rp{{ strrev(implode('.', str_split(strrev(strval($expenseData[$i]['ending balance'][$j])), 3))) }}
                                        </td>
                                    </tr>
                                @endif
                            @endfor
                        @endif
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                                Total {{ $expenseData[$i]['classification'] }}
                            </td>
                            <td style="width:10%" class="text-right px-3 py-2">
                                Rp{{ strrev(implode('.', str_split(strrev(strval(array_sum($expenseData[$i]['ending balance']))), 3))) }}
                            </td>
                        </tr>
                    @endfor
                    <tr
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
                        <tr class="bg-green-600 text-white font-bold surplus">
                            <td style="width:60%" class="px-3 py-4">
                                <strong>Surplus</strong>
                            </td>
                            <td class="text-right px-3 py-2" style="width:10%">
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
                        <tr class="bg-red-600 text-white font-bold defisit">
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
                    @endif
                </tbody>
            </table>
        </div>
        <!-- end content-->
    </div>
</body>

</html>
