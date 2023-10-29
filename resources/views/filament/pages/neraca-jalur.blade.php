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

    @endphp
    <h2 class="text-lg font-semibold mt-0">Periode 2023</h2>
    {{-- start dropdown and submit --}}

    <div class="flex items-center">
        <div class="mr-4">
            <label for="years" class="text-sm font-medium text-gray-900 dark:text-white">
                Tahun:</label>
            <select
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 pr-6 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
                @foreach ($years as $y)
                    <option value="{{ $y->year }}" {{ $year == $y->year ? 'selected' : '' }}>{{ $y->year }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="ml-4 mr-4">
            <label class="text-sm font-medium text-gray-900 dark:text-white">
                Akun:</label>
            <select
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 pr-6 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
                {{-- @foreach ($accounts as $a)
                    <option value="{{ $a->id }}" {{ $log['id_akun'] == $a->id ? 'selected' : '' }}>
                        {{ $a->account_code }} -
                        {{ $a->account_name }}
                    </option>
                @endforeach --}}
            </select>
        </div>
        <button type="button" id="search"
            class="self-end focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 ml-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            Submit
        </button>
    </div>
    {{-- end dropdown and submit --}}

    {{-- start table --}}
    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%"
        style="width:100%">
        <thead>
            <tr>
                <th style="width:40%">Nama Akun</th>
                <th style="width:10%">Posisi Normal</th>
                <th style="width:20%">Debit</th>
                <th style="width:20%">Kredit</th>
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
                                    <tr>
                                        <td style="padding-left: 3rem!important;">
                                            {{ $balance[$i]['classification'][$j]['account'][$k]['account_code'] }} -
                                            {{ $balance[$i]['classification'][$j]['account'][$k]['account_name'] }}
                                        </td>
                                        <td>
                                            {{ $balance[$i]['classification'][$j]['account'][$k]['position'] }}
                                        </td>
                                        <td>
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
                                        <td>
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
            <tr>
                <td><strong>Total</strong></td>
                <td><strong></strong></td>
                <td>
                    <strong>
                        @if ($jumlah_debit < 0)
                            -Rp{{ strrev(implode('.', str_split(strrev(strval(-1 * $jumlah_debit)), 3))) }}
                        @else
                            Rp{{ strrev(implode('.', str_split(strrev(strval($jumlah_debit)), 3))) }}
                        @endif
                    </strong>
                </td>
                <td>
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
    {{-- end table --}}
</x-filament-panels::page>
