<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" href="/images/favicon.svg" type="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        Laporan Perubahan Ekuitas
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
            <h3 class="text-center company">{{ $pesantrendata->name }}</h3>
            <h3 class="text-center company">Laporan Perubahan Ekuitas</h3>
            <p class="text-center" style="margin:5px; margin-bottom:10px"><strong>Periode</strong>
                {{ $monthName }} {{ $year }} </p>
        </div>
        <div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" width="100%" style="width:100%"
                cellspacing="0">
                <tbody>
                    <tr class="bg-green-600 text-white font-bold">
                        <th style="width:60%" class="p-3" colspan="2">
                            Penyertaan Modal
                        </th>

                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;padding-left: 1.5rem!important;">
                            Ekuitas
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%">
                            @if ($modal_awal >= 0)
                                Rp.{{ strrev(implode('.', str_split(strrev(strval($modal_awal)), 3))) }}
                            @else
                                -Rp.{{ strrev(implode('.', str_split(strrev(strval($modal_awal * -1)), 3))) }}
                            @endif
                        </td>
                    </tr>
                    <tr class="bg-green-600 text-white font-bold">
                        <th style="width:60%" class="p-3" colspan="2">
                            Penambahan Investasi periode berjalan
                        </th>
                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;padding-left: 1.5rem!important;">
                            Setoran Modal
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%">
                            @if ($setoran_modal >= 0)
                                Rp.{{ strrev(implode('.', str_split(strrev(strval($setoran_modal)), 3))) }}
                            @else
                                -Rp.{{ strrev(implode('.', str_split(strrev(strval($setoran_modal * -1)), 3))) }}
                            @endif
                        </td>
                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 font-semibold">
                        <td style="width:60%;padding-left: 1.5rem!important;">
                            Surplus/Defisit
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%">
                            @if ($surpdef >= 0)
                                Rp.{{ strrev(implode('.', str_split(strrev(strval($surpdef)), 3))) }}
                            @else
                                -Rp.{{ strrev(implode('.', str_split(strrev(strval($surpdef * -1)), 3))) }}
                            @endif
                        </td>
                    </tr>
                    <tr class="bg-green-600 text-white font-bold">
                        <th style="width:60%" class="p-3" colspan="2">
                            Bagi Hasil Penyertaan
                        </th>
                    </tr>
                    <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;padding-left: 1.5rem!important;">
                            Prive
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%">
                            @if ($prive >= 0)
                                Rp.{{ strrev(implode('.', str_split(strrev(strval($prive)), 3))) }}
                            @else
                                -Rp.{{ strrev(implode('.', str_split(strrev(strval($prive * -1)), 3))) }}
                            @endif
                        </td>
                    </tr>
                    <tr class="bg-green-600 text-white font-bold">
                        <th style="width:60%" class="p-3">
                            Ekuitas Akhir
                        </th>
                        <th style="width:10%" class="px-3 py-2 text-right">
                            @if ($modal_awal + $setoran_modal + $surpdef - $prive >= 0)
                                Rp.{{ strrev(implode('.', str_split(strrev(strval($modal_awal + $setoran_modal + $surpdef - $prive)), 3))) }}
                            @else
                                -Rp.{{ strrev(implode('.', str_split(strrev(strval(($modal_awal + $setoran_modal + $surpdef - $prive) * -1)), 3))) }}
                            @endif
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- end content-->
    </div>
</body>

</html>
