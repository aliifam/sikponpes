<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" href="/images/favicon.svg" type="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        Neraca Lajur {{ $pesantrendata->name }}
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
        $dt = $_GET['year'];
        $month = $_GET['month'];

        setlocale(LC_ALL, 'id_ID');

        $jumlah_debit = 0;
        $jumlah_kredit = 0;

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
            <h3 class="text-center company">Neraca Lajur</h3>
            {{-- @php
                $dateObj = DateTime::createFromFormat('!m', $month);
                $monthName = $dateObj->format('F'); // March
            @endphp --}}
            <p class="text-center" style="margin:5px; margin-bottom:10px"><strong>Periode</strong>
                {{ $monthName }} {{ $dt }} </p>
        </div>
        <div>
            <table cellspacing="0" width="100%" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width:40%">Nama Akun</th>
                        <th class="text-center" style="width:10%">Posisi Normal</th>
                        <th class="text-center" style="width:20%">Debit</th>
                        <th class="text-center" style="width:20%">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < sizeof($balance); $i++)
                        <tr>
                            <td>
                                <strong>{{ $balance[$i]['parent_code'] }} - {{ $balance[$i]['parent_name'] }}</strong>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @if (isset($balance[$i]['classification']))
                            @for ($j = 0; $j < sizeof($balance[$i]['classification']); $j++)
                                <tr>
                                    <td style="padding-left: 1.5rem!important;">
                                        {{ $balance[$i]['classification'][$j]['classification_name'] }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @if (isset($balance[$i]['classification'][$j]['account']))
                                    @for ($k = 0; $k < sizeof($balance[$i]['classification'][$j]['account']); $k++)
                                        @if ($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] != '0')
                                            <tr class="accordian-body collapse show accordion-toggle pl-3 dataAkun">
                                                <td style="padding-left: 3rem!important;">
                                                    {{ $balance[$i]['classification'][$j]['account'][$k]['account_code'] }}
                                                    -
                                                    {{ $balance[$i]['classification'][$j]['account'][$k]['account_name'] }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $balance[$i]['classification'][$j]['account'][$k]['position'] }}
                                                </td>
                                                <td class="text-right">
                                                    @if ($balance[$i]['classification'][$j]['account'][$k]['position'] == 'debit')
                                                        @if ($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] < 0)
                                                            -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])), 3))) }}
                                                        @else
                                                            Rp{{ strrev(implode('.', str_split(strrev(strval($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])), 3))) }}
                                                        @endif
                                                        @php
                                                            $jumlah_debit += $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'];
                                                        @endphp
                                                    @else
                                                        Rp0
                                                    @endif
                                                </td>
                                                <td class="text-right">
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
                                                    @else
                                                        Rp0
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endfor
                                @endif
                            @endfor
                        @endif
                    @endfor
                    <tr>
                        <td><strong>Total</strong></td>
                        <td><strong></strong></td>
                        <td class="text-right">
                            <strong>Rp{{ strrev(implode('.', str_split(strrev(strval($jumlah_debit)), 3))) }} </strong>
                        </td>
                        <td class="text-right">
                            <strong>Rp{{ strrev(implode('.', str_split(strrev(strval($jumlah_kredit)), 3))) }}
                            </strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- end content-->
    </div>
</body>

</html>
