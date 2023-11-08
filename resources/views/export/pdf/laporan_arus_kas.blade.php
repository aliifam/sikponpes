<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" href="/images/favicon.svg" type="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        Laporan Arus Kas {{ $pesantrendata->name }}
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
            <h3 class="text-center company">{{ $pesantrendata->name }}</h3>
            <h3 class="text-center company">Laporan Arus Kas</h3>
            <p class="text-center" style="margin:5px; margin-bottom:10px"><strong>Periode</strong>
                {{ $monthName }} {{ $year }} </p>
        </div>
        <br>
        <br>
        <div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" width="100%" style="width:100%">
                <tbody>
                    {{-- arus kas operasi render --}}
                    <tr class="bg-green-600 text-white font-bold">
                        <th style="width:60%" class="p-3" colspan="2">
                            Arus Kas Operasi
                        </th>
                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;" class="px-3 py-2 font-semibold" colspan="2">
                            Arus Kas Masuk
                        </td>
                    </tr>
                    @if (isset($arusKasOperasi['masuk']))
                        @foreach ($arusKasOperasi['masuk'] as $item)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                                    {{ $item['description'] }}
                                </td>
                                <td class="text-right px-3 py-2" style="width:10%">
                                    @php
                                        $amount = 0;
                                        foreach ($item['general_journal'] as $journal) {
                                            if ($journal['account_id'] === $kasId) {
                                                $amount += $journal['amount'];
                                            }
                                        }
                                    @endphp
                                    {{ $amount < 0 ? '- Rp' . strrev(implode('.', str_split(strrev(strval($amount)), 3))) : 'Rp' . strrev(implode('.', str_split(strrev(strval($amount)), 3))) }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;" class="px-3 py-2 font-semibold" colspan="2">
                            Arus Kas Keluar
                        </td>
                        </td>
                    </tr>
                    @if (isset($arusKasOperasi['keluar']))
                        @foreach ($arusKasOperasi['keluar'] as $item)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                                    {{ $item['description'] }}
                                </td>
                                <td class="text-right px-3 py-2" style="width:10%">
                                    @php
                                        $amount = 0;
                                        foreach ($item['general_journal'] as $journal) {
                                            if ($journal['account_id'] === $kasId) {
                                                $amount += $journal['amount'];
                                            }
                                        }
                                    @endphp
                                    {{ $amount < 0 ? '- Rp' . strrev(implode('.', str_split(strrev(strval($amount)), 3))) : 'Rp' . strrev(implode('.', str_split(strrev(strval($amount)), 3))) }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;" class="px-3 py-2">
                            Jumlah kas neto diterima dari aktivitas operasi
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%">
                            @if ($arusKasOperasi['amount'] < 0)
                                - Rp{{ strrev(implode('.', str_split(strrev(strval($arusKasOperasi['amount'])), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($arusKasOperasi['amount'])), 3))) }}
                            @endif
                        </td>
                    </tr>
                    {{-- end arus kas operasi render --}}

                    {{-- arus kas investasi render --}}
                    <tr class="bg-green-600 text-white font-bold">
                        <th style="width:60%" class="p-3" colspan="2">
                            Arus Kas Investasi
                        </th>
                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;" class="px-3 py-2 font-semibold" colspan="2">
                            Arus Kas Masuk
                        </td>
                    </tr>
                    @if (isset($arusKasInvestasi['masuk']))
                        @foreach ($arusKasInvestasi['masuk'] as $item)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                                    {{ $item['description'] }}
                                </td>
                                <td class="text-right px-3 py-2" style="width:10%">
                                    @php
                                        $amount = 0;
                                        foreach ($item['general_journal'] as $journal) {
                                            if ($journal['account_id'] === $kasId) {
                                                $amount += $journal['amount'];
                                            }
                                        }
                                    @endphp
                                    {{ $amount < 0 ? '- Rp' . strrev(implode('.', str_split(strrev(strval($amount)), 3))) : 'Rp' . strrev(implode('.', str_split(strrev(strval($amount)), 3))) }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;" class="px-3 py-2 font-semibold" colspan="2">
                            Arus Kas Keluar
                        </td>
                    </tr>
                    @if (isset($arusKasInvestasi['keluar']))
                        @foreach ($arusKasInvestasi['keluar'] as $item)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                                    {{ $item['description'] }}
                                </td>
                                <td class="text-right px-3 py-2" style="width:10%">
                                    @php
                                        $amount = 0;
                                        foreach ($item['general_journal'] as $journal) {
                                            if ($journal['account_id'] === $kasId) {
                                                $amount += $journal['amount'];
                                            }
                                        }
                                    @endphp
                                    {{ $amount < 0 ? '- Rp' . strrev(implode('.', str_split(strrev(strval($amount)), 3))) : 'Rp' . strrev(implode('.', str_split(strrev(strval($amount)), 3))) }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;" class="px-3 py-2">
                            Jumlah kas neto diterima dari aktivitas investasi
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%">
                            @if ($arusKasOperasi['amount'] < 0)
                                -
                                Rp{{ strrev(implode('.', str_split(strrev(strval($arusKasInvestasi['amount'])), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($arusKasInvestasi['amount'])), 3))) }}
                            @endif
                        </td>
                    </tr>

                    {{-- end arus kas investasi render --}}

                    {{-- arus kas pendanaan render --}}
                    <tr class="bg-green-600 text-white font-bold">
                        <th style="width:60%" class="p-3" colspan="2">
                            Arus Kas Pendanaan
                        </th>
                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;" class="px-3 py-2 font-semibold" colspan="2">
                            Arus Kas Masuk
                        </td>
                    </tr>
                    @if (isset($arusKasPendanaan['masuk']))
                        @foreach ($arusKasPendanaan['masuk'] as $item)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                                    {{ $item['description'] }}
                                </td>
                                <td class="text-right px-3 py-2" style="width:10%">
                                    @php
                                        $amount = 0;
                                        foreach ($item['general_journal'] as $journal) {
                                            if ($journal['account_id'] === $kasId) {
                                                $amount += $journal['amount'];
                                            }
                                        }
                                    @endphp
                                    {{ $amount < 0 ? '- Rp' . strrev(implode('.', str_split(strrev(strval($amount)), 3))) : 'Rp' . strrev(implode('.', str_split(strrev(strval($amount)), 3))) }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;" class="px-3 py-2 font-semibold" colspan="2">
                            Arus Kas Keluar
                        </td>
                    </tr>
                    @if (isset($arusKasPendanaan['keluar']))
                        @foreach ($arusKasPendanaan['keluar'] as $item)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td style="width:60%;padding-left: 1.5rem!important;" class="px-3 py-2">
                                    {{ $item['description'] }}
                                </td>
                                <td class="text-right px-3 py-2" style="width:10%">
                                    @php
                                        $amount = 0;
                                        foreach ($item['general_journal'] as $journal) {
                                            if ($journal['account_id'] === $kasId) {
                                                $amount += $journal['amount'];
                                            }
                                        }
                                    @endphp
                                    {{ $amount < 0 ? '- Rp' . strrev(implode('.', str_split(strrev(strval($amount)), 3))) : 'Rp' . strrev(implode('.', str_split(strrev(strval($amount)), 3))) }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style="width:60%;" class="px-3 py-2">
                            Jumlah kas neto diterima dari aktivitas investasi
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%">
                            @if ($arusKasOperasi['amount'] < 0)
                                -
                                Rp{{ strrev(implode('.', str_split(strrev(strval($arusKasPendanaan['amount'])), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($arusKasPendanaan['amount'])), 3))) }}
                            @endif
                        </td>
                    </tr>
                    {{-- end arus kas pendanaan render --}}

                    {{-- render rangkuman --}}
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 font-semibold surplus">
                        <td style="width:60%;" class="px-3 py-2">
                            Kenaikan / Penurunan
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%">
                            @if ($arusKasOperasi['amount'] + $arusKasInvestasi['amount'] + $arusKasPendanaan['amount'] - $saldoAwal < 0)
                                -
                                Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * ($arusKasOperasi['amount'] + $arusKasInvestasi['amount'] + $arusKasPendanaan['amount'] - $saldoAwal))), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($arusKasOperasi['amount'] + $arusKasInvestasi['amount'] + $arusKasPendanaan['amount'] - $saldoAwal)), 3))) }}
                            @endif
                        </td>
                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 font-semibold">
                        <td style="width:60%;" class="px-3 py-2">
                            Saldo Awal
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%">
                            @if ($saldoAwal < 0)
                                - Rp{{ strrev(implode('.', str_split(strrev(strval($saldoAwal)), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($saldoAwal)), 3))) }}
                            @endif
                        </td>
                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 font-semibold">
                        <td style="width:60%;" class="px-3 py-2">
                            Saldo Akhir
                        </td>
                        <td class="text-right px-3 py-2" style="width:10%">
                            @if ($saldoAwal - $arusKasOperasi['amount'] + $arusKasInvestasi['amount'] + $arusKasPendanaan['amount'] < 0)
                                -
                                Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * ($saldoAwal - $arusKasOperasi['amount'] + $arusKasInvestasi['amount'] + $arusKasPendanaan['amount']))), 3))) }}
                            @else
                                Rp{{ strrev(implode('.', str_split(strrev(strval($saldoAwal - $arusKasOperasi['amount'] + $arusKasInvestasi['amount'] + $arusKasPendanaan['amount'])), 3))) }}
                            @endif
                        </td>
                    </tr>
                    {{-- end render rangkuman --}}
                </tbody>
            </table>
        </div>
        <!-- end content-->
    </div>
</body>

</html>
