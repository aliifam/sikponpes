<?php

namespace App\Filament\Pages;

use App\Models\InitialBalance;
use App\Models\JournalDetail;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class BukuPembantuAktivaTetap extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Manajemen Buku';

    protected static string $view = 'filament.pages.buku-pembantu-aktiva-tetap';

    public $listTanah;
    public $listKendaraan;
    public $listPeralatan;
    public $listGedung;
    public $year;
    public $month;
    public $years;

    public function mount()
    {
        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
        //depresiasi dalam bulanan
        $dtanah = 0; //tidak ada depresiasi
        $dkendaraan = 60; //5 tahun
        $dperalatan = 48; //4 tahun
        $dgedung = 240; //20 tahun

        $session = Filament::getTenant()->id;
        //get id of asset tetap account
        $idtanah = \App\Models\Account::where('account_name', 'LIKE', 'Tanah')->where('pesantren_id', $session)->first()->id;
        $idkendaraan = \App\Models\Account::where('account_name', 'LIKE', 'Kendaraan')->where('pesantren_id', $session)->first()->id;
        $idperalatan = \App\Models\Account::where('account_name', 'LIKE', 'Peralatan dan Mesin')->where('pesantren_id', $session)->first()->id;
        $idgedung = \App\Models\Account::where('account_name', 'LIKE', 'Gedung dan Bangunan')->where('pesantren_id', $session)->first()->id;

        //general journal incude asset tetap id and where position is debit and where date before or equal to selected date
        $data = \App\Models\GeneralJournal::with('detail', 'account')
            ->whereHas('detail', function ($q) use ($idtanah, $idkendaraan, $idperalatan, $idgedung, $year, $month) {
                $q->where(function ($q) use ($year, $month) {
                    $q->whereYear('date', '>', $year)
                        ->orWhere(function ($q) use ($year, $month) {
                            $q->whereYear('date', '<=', $year)
                                ->whereMonth('date', '<', $month);
                        });
                })
                    ->whereIn('account_id', [$idtanah, $idkendaraan, $idperalatan, $idgedung]);
            })
            ->where('position', 'debit')
            ->get();


        // dd($data->toArray());

        $listTanah = [];
        $listKendaraan = [];
        $listPeralatan = [];
        $listGedung = [];


        foreach ($data as $d) {
            if ($d->account_id == $idtanah) {
                $listTanah[] = $d;
            } elseif ($d->account_id == $idkendaraan) {
                $listKendaraan[] = $d;
            } elseif ($d->account_id == $idperalatan) {
                $listPeralatan[] = $d;
            } elseif ($d->account_id == $idgedung) {
                $listGedung[] = $d;
            }
        }

        // build data for table with deplresiasi
        // depresiasi per bulan = amount / n bulan (n tergantung jenis asset)

        foreach ($listTanah as $t) {
            $tanggal_beli = $t->detail->date;
            $selisih_bulan = (date('Y', strtotime($year . '-' . $month . '-01')) - date('Y', strtotime($tanggal_beli))) * 12 + (date('m', strtotime($year . '-' . $month . '-01')) - date('m', strtotime($tanggal_beli)));
            $t->depresiasi_bulanan = 0;
            $t->depresiasi = $t->depresiasi_bulanan * $selisih_bulan;
        }

        foreach ($listKendaraan as $k) {
            $tanggal_beli = $k->detail->date;
            $selisih_bulan = (date('Y', strtotime($year . '-' . $month . '-01')) - date('Y', strtotime($tanggal_beli))) * 12 + (date('m', strtotime($year . '-' . $month . '-01')) - date('m', strtotime($tanggal_beli)));
            $k->depresiasi_bulanan = floor($k->amount / $dkendaraan);
            $k->depresiasi = $k->depresiasi_bulanan * $selisih_bulan;
        }

        foreach ($listPeralatan as $p) {
            $tanggal_beli = $p->detail->date;
            $selisih_bulan = (date('Y', strtotime($year . '-' . $month . '-01')) - date('Y', strtotime($tanggal_beli))) * 12 + (date('m', strtotime($year . '-' . $month . '-01')) - date('m', strtotime($tanggal_beli)));
            //pembagian bulatkan ke bawah
            $p->depresiasi_bulanan = floor($p->amount / $dperalatan);
            $p->depresiasi = $p->depresiasi_bulanan * $selisih_bulan;
        }

        foreach ($listGedung as $g) {
            $tanggal_beli = $g->detail->date;
            $selisih_bulan = (date('Y', strtotime($year . '-' . $month . '-01')) - date('Y', strtotime($tanggal_beli))) * 12 + (date('m', strtotime($year . '-' . $month . '-01')) - date('m', strtotime($tanggal_beli)));
            $g->depresiasi_bulanan = floor($g->amount / $dgedung);
            $g->depresiasi = $g->depresiasi_bulanan * $selisih_bulan;
        }

        // dd($listTanah, $listKendaraan, $listPeralatan, $listGedung);



        $years = JournalDetail::selectRaw('YEAR(date) as year')
            ->whereHas('general_journal.account.classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->union(InitialBalance::selectRaw('YEAR(date) as year')
                ->whereHas('account.classification.parent', function ($q) use ($session) {
                    $q->where('pesantren_id', $session);
                }))->orderBy('year', 'DESC')->get();

        if ($years->isEmpty()) {
            //same data structure as $years
            $years = collect([
                (object) [
                    'year' => date('Y')
                ]
            ]);
        }

        $this->years = $years;
        $this->year = $year;
        $this->month = $month;
        $this->listTanah = $listTanah;
        $this->listKendaraan = $listKendaraan;
        $this->listPeralatan = $listPeralatan;
        $this->listGedung = $listGedung;
    }
}
