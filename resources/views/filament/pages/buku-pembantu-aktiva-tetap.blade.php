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

        $total;

        $totalAmountTanah = 0;
        $totalDepresiasiBulananTanah = 0;
        $totalDepresiasiTanah = 0;
        $totalNilaiBukuTanah = 0;

        $totalAmountKendaraan = 0;
        $totalDepresiasiBulananKendaraan = 0;
        $totalDepresiasiKendaraan = 0;
        $totalNilaiBukuKendaraan = 0;

        $totalAmountPeralatan = 0;
        $totalDepresiasiBulananPeralatan = 0;
        $totalDepresiasiPeralatan = 0;
        $totalNilaiBukuPeralatan = 0;

        $totalAmountGedung = 0;
        $totalDepresiasiBulananGedung = 0;
        $totalDepresiasiGedung = 0;
        $totalNilaiBukuGedung = 0;
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
    </div>
    {{-- end dropdown and submit --}}

    {{-- start table --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            {{-- <thead>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </thead> --}}
            <tbody>
                <tr class="bg-green-600 text-white font-bold">
                    <td style="" class="p-3 text-center">
                        Tanah
                    </td>
                    <td style="" class="p-2 text-center">
                        Tanggal Beli
                    </td>
                    <td style="" class="p-2 text-center">
                        Harga Perolehan
                    </td>
                    <td style="" class="p-2 text-center">
                        Penyusutan Bulanan
                    </td>
                    <td style="" class="p-2 text-center">
                        Akumulasi Penyusutan
                    </td>
                    <td style="" class="p-2 text-center">
                        Nilai Buku
                    </td>
                </tr>
                {{-- saldo awal tanah --}}
                @if ($initialTanah != null)
                    @php
                        $totalAmountTanah += $initialTanah->amount;
                        $totalDepresiasiBulananTanah += $initialTanah->depresiasi_bulanan;
                        $totalDepresiasiTanah += $initialTanah->depresiasi;
                        $totalNilaiBukuTanah += $initialTanah->amount - $initialTanah->depresiasi;
                    @endphp
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style=";padding-left: 1.5rem!important;" class="text-center">
                            Saldo Awal
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            {{ strftime('%G', strtotime($initialTanah->date)) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialTanah->amount)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialTanah->depresiasi_bulanan)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialTanah->depresiasi)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialTanah->amount - $initialTanah->depresiasi)), 3))) }}
                        </td>
                    </tr>
                @else
                    {{-- single row displayed empty --}}
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-3 text-center" colspan="6">Saldo awal Tanah kosong</td>
                    </tr>
                @endif
                @foreach ($listTanah as $tanah)
                    @php
                        $totalAmountTanah += $tanah->amount;
                        $totalDepresiasiBulananTanah += $tanah->depresiasi_bulanan;
                        $totalDepresiasiTanah += $tanah->depresiasi;
                        $totalNilaiBukuTanah += $tanah->amount - $tanah->depresiasi;
                    @endphp
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style=";padding-left: 1.5rem!important;" class="text-center">
                            {{ $tanah->detail->description }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            {{ strftime('%d %B %G', strtotime($tanah->detail->date)) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($tanah->amount)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($tanah->depresiasi_bulanan)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($tanah->depresiasi)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($tanah->amount - $tanah->depresiasi)), 3))) }}
                        </td>
                    </tr>
                @endforeach
                @if (empty($listTanah))
                    {{-- single row displayed empty --}}
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-3 text-center" colspan="6">Data Kosong</td>
                    </tr>
                @endif
                {{-- counting total of amount, depresiasi bulanan, depresiasi, nilai buku of tanah --}}
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td style=";padding-left: 1.5rem!important;" class="text-center">
                        Total
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalAmountTanah)), 3))) }}
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalDepresiasiBulananTanah)), 3))) }}
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalDepresiasiTanah)), 3))) }}
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalNilaiBukuTanah)), 3))) }}
                    </td>
                </tr>


                <tr class="bg-green-600 text-white font-bold">
                    <td style="" class="p-3 text-center">
                        Kendaraan
                    </td>
                    <td style="" class="p-2 text-center">
                        Tanggal Beli
                    </td>
                    <td style="" class="p-2 text-center">
                        Harga Perolehan
                    </td>
                    <td style="" class="p-2 text-center">
                        Penyusutan Bulanan
                    </td>
                    <td style="" class="p-2 text-center">
                        Akumulasi Penyusutan
                    </td>
                    <td style="" class="p-2 text-center">
                        Nilai Buku
                    </td>
                </tr>
                {{-- saldo awal kendaraan --}}
                @if ($initialKendaraan != null)
                    @php
                        $totalAmountKendaraan += $initialKendaraan->amount;
                        $totalDepresiasiBulananKendaraan += $initialKendaraan->depresiasi_bulanan;
                        $totalDepresiasiKendaraan += $initialKendaraan->depresiasi;
                        $totalNilaiBukuKendaraan += $initialKendaraan->amount - $initialKendaraan->depresiasi;
                    @endphp
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style=";padding-left: 1.5rem!important;" class="text-center">
                            Saldo Awal
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            {{ strftime('%G', strtotime($initialKendaraan->date)) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialKendaraan->amount)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialKendaraan->depresiasi_bulanan)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialKendaraan->depresiasi)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialKendaraan->amount - $initialKendaraan->depresiasi)), 3))) }}
                        </td>
                    </tr>
                @else
                    {{-- single row displayed empty --}}
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-3 text-center" colspan="6">Saldo awal kendaraan kosong</td>
                    </tr>
                @endif
                @foreach ($listKendaraan as $kendaraan)
                    @php
                        $totalAmountKendaraan += $kendaraan->amount;
                        $totalDepresiasiBulananKendaraan += $kendaraan->depresiasi_bulanan;
                        $totalDepresiasiKendaraan += $kendaraan->depresiasi;
                        $totalNilaiBukuKendaraan += $kendaraan->amount - $kendaraan->depresiasi;
                    @endphp
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style=";padding-left: 1.5rem!important;" class="text-center">
                            {{ $kendaraan->detail->description }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            {{ strftime('%d %B %G', strtotime($kendaraan->detail->date)) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($kendaraan->amount)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($kendaraan->depresiasi_bulanan)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($kendaraan->depresiasi)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($kendaraan->amount - $kendaraan->depresiasi)), 3))) }}
                        </td>
                    </tr>
                @endforeach
                @if (empty($listKendaraan))
                    {{-- single row displayed empty --}}
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-3 text-center" colspan="6">Data Kosong</td>
                    </tr>
                @endif
                {{-- counting total of amount, depresiasi bulanan, depresiasi, nilai buku of kendaraan --}}
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td style=";padding-left: 1.5rem!important;" class="text-center">
                        Total
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalAmountKendaraan)), 3))) }}
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalDepresiasiBulananKendaraan)), 3))) }}
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalDepresiasiKendaraan)), 3))) }}
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalNilaiBukuKendaraan)), 3))) }}
                    </td>
                </tr>

                <tr class="bg-green-600 text-white font-bold">
                    <td style="" class="p-3 text-center">
                        Peralatan dan Mesin
                    </td>
                    <td style="" class="p-2 text-center">
                        Tanggal Beli
                    </td>
                    <td style="" class="p-2 text-center">
                        Harga Perolehan
                    </td>
                    <td style="" class="p-2 text-center">
                        Penyusutan Bulanan
                    </td>
                    <td style="" class="p-2 text-center">
                        Akumulasi Penyusutan
                    </td>
                    <td style="" class="p-2 text-center">
                        Nilai Buku
                    </td>
                </tr>
                {{-- saldo awal kendaraan --}}
                @if ($initialPeralatan != null)
                    @php
                        $totalAmountPeralatan += $initialPeralatan->amount;
                        $totalDepresiasiBulananPeralatan += $initialPeralatan->depresiasi_bulanan;
                        $totalDepresiasiPeralatan += $initialPeralatan->depresiasi;
                        $totalNilaiBukuPeralatan += $initialPeralatan->amount - $initialPeralatan->depresiasi;
                    @endphp
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style=";padding-left: 1.5rem!important;" class="text-center">
                            Saldo Awal
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            {{ strftime('%G', strtotime($initialPeralatan->date)) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialPeralatan->amount)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialPeralatan->depresiasi_bulanan)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialPeralatan->depresiasi)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialPeralatan->amount - $initialPeralatan->depresiasi)), 3))) }}
                        </td>
                    </tr>
                @else
                    {{-- single row displayed empty --}}
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-3 text-center" colspan="6">Saldo awal Peralatan dan Mesin kosong</td>
                    </tr>
                @endif
                @foreach ($listPeralatan as $peralatan)
                    @php
                        $totalAmountPeralatan += $peralatan->amount;
                        $totalDepresiasiBulananPeralatan += $peralatan->depresiasi_bulanan;
                        $totalDepresiasiPeralatan += $peralatan->depresiasi;
                        $totalNilaiBukuPeralatan += $peralatan->amount - $peralatan->depresiasi;
                    @endphp
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style=";padding-left: 1.5rem!important;" class="text-center">
                            {{ $peralatan->detail->description }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            {{ strftime('%d %B %G', strtotime($peralatan->detail->date)) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($peralatan->amount)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($peralatan->depresiasi_bulanan)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($peralatan->depresiasi)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($peralatan->amount - $peralatan->depresiasi)), 3))) }}
                        </td>
                    </tr>
                @endforeach
                @if (empty($listPeralatan))
                    {{-- single row displayed empty --}}
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-3 text-center" colspan="6">Data Kosong</td>
                    </tr>
                @endif
                {{-- counting total of amount, depresiasi bulanan, depresiasi, nilai buku of peralatan --}}
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td style=";padding-left: 1.5rem!important;" class="text-center">
                        Total
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalAmountPeralatan)), 3))) }}
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalDepresiasiBulananPeralatan)), 3))) }}
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalDepresiasiPeralatan)), 3))) }}
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalNilaiBukuPeralatan)), 3))) }}
                    </td>
                </tr>


                <tr class="bg-green-600 text-white font-bold">
                    <td style="" class="p-3 text-center">
                        Gedung dan Bangunan
                    </td>
                    <td style="" class="p-2 text-center">
                        Tanggal Beli
                    </td>
                    <td style="" class="p-2 text-center">
                        Harga Perolehan
                    </td>
                    <td style="" class="p-2 text-center">
                        Penyusutan Bulanan
                    </td>
                    <td style="" class="p-2 text-center">
                        Akumulasi Penyusutan
                    </td>
                    <td style="" class="p-2 text-center">
                        Nilai Buku
                    </td>
                </tr>
                {{-- saldo awal kendaraan --}}
                @if ($initialGedung != null)
                    @php
                        $totalAmountGedung += $initialGedung->amount;
                        $totalDepresiasiBulananGedung += $initialGedung->depresiasi_bulanan;
                        $totalDepresiasiGedung += $initialGedung->depresiasi;
                        $totalNilaiBukuGedung += $initialGedung->amount - $initialGedung->depresiasi;
                    @endphp
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style=";padding-left: 1.5rem!important;" class="text-center">
                            Saldo Awal
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            {{ strftime('%G', strtotime($initialGedung->date)) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialGedung->amount)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialGedung->depresiasi_bulanan)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialGedung->depresiasi)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($initialGedung->amount - $initialGedung->depresiasi)), 3))) }}
                        </td>
                    </tr>
                @else
                    {{-- single row displayed empty --}}
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-3 text-center" colspan="6">Saldo awal Gedung dan Bangunan kosong</td>
                    </tr>
                @endif
                @foreach ($listGedung as $gedung)
                    @php
                        $totalAmountGedung += $gedung->amount;
                        $totalDepresiasiBulananGedung += $gedung->depresiasi_bulanan;
                        $totalDepresiasiGedung += $gedung->depresiasi;
                        $totalNilaiBukuGedung += $gedung->amount - $gedung->depresiasi;
                    @endphp
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td style=";padding-left: 1.5rem!important;" class="text-center">
                            {{ $gedung->detail->description }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            {{ strftime('%d %B %G', strtotime($gedung->detail->date)) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($gedung->amount)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($gedung->depresiasi_bulanan)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($gedung->depresiasi)), 3))) }}
                        </td>
                        <td class="px-3 py-2 text-center" style="">
                            Rp.{{ strrev(implode('.', str_split(strrev(strval($gedung->amount - $gedung->depresiasi)), 3))) }}
                        </td>
                    </tr>
                @endforeach
                @if (empty($listGedung))
                    {{-- single row displayed empty --}}
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-3 text-center" colspan="6">Data Kosong</td>
                    </tr>
                @endif
                {{-- counting total of amount, depresiasi bulanan, depresiasi, nilai buku of gedung --}}
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td style=";padding-left: 1.5rem!important;" class="text-center">
                        Total
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalAmountGedung)), 3))) }}
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalDepresiasiBulananGedung)), 3))) }}
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalDepresiasiGedung)), 3))) }}
                    </td>
                    <td class="px-3 py-2 text-center" style="">
                        Rp.{{ strrev(implode('.', str_split(strrev(strval($totalNilaiBukuGedung)), 3))) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    {{-- end table --}}

    {{-- start script --}}
    <script>
        // dyamic filter by month and year
        document.getElementById('search').addEventListener('click', function() {
            var year = document.getElementById('years').value;
            var month = document.getElementById('months').value;
            window.location.href = 'buku-pembantu-aktiva-tetap?year=' + year + '&month=' + month;
        });
    </script>
    {{-- end script --}}
</x-filament-panels::page>
