<?php

namespace App\Filament\Pages;

use App\Models\Account;
use App\Models\GeneralJournal;
use App\Models\InitialBalance;
use App\Models\JournalDetail;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class BukuBesar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Manajemen Buku';

    protected static string $view = 'filament.resources.buku-besar-resource.pages.buku-besar';

    public $data;
    public $years;
    public $accounts;
    public $account;
    public $log;
    public $session;

    public function mount()
    {
        $session = Filament::getTenant()->id;
        $account = Account::with('classification.parent')
            ->whereHas('classification.parent.pesantren', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->first()->id;

        if (isset($_GET['year'], $_GET['akun'])) {
            $year = $_GET['year'];
            $akun = $_GET['akun'];
        } else {
            $year = date('Y');
            $akun = $account;
        }

        $checkAccount = Account::with('initialBalance', 'journal', 'classification.parent')
            ->whereHas('classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->where('id', $akun)->first();

        // dd($checkAccount->toArray());

        $log = array();
        if (!$checkAccount->initialBalance()->whereYear('date', $year)->first()) {
            $beginning_balance = 0;
        } else {
            $beginning_balance = $checkAccount->initialBalance()->whereYear('date', $year)->first()->amount;
        }

        $log['id_akun'] = $checkAccount->id;
        $log['nama_akun'] = $checkAccount->account_name;
        $log['position'] = $checkAccount->position;
        $log['saldo_awal'] = $beginning_balance;
        $log['position'] = $checkAccount->position;
        $log['kode_akun'] = $checkAccount->account_code;
        if (!$checkAccount->initialBalance()->whereYear('date', $year)->first()) {
            $log['date'] = '';
        } else {
            $log['date'] = $checkAccount->initialBalance()->first()->date;
        }

        // dd($log);

        $data = GeneralJournal::with('account.classification.parent', 'detail')
            ->whereHas('account.classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->whereHas('detail', function ($q) use ($year) {
                $q->whereYear('date', $year);
            })->where('account_id', $akun)
            ->get();

        $data = $data->sortBy('detail.date', SORT_REGULAR, true);

        // select distinct all available years in journal details table and initial balances table
        $years = JournalDetail::selectRaw('YEAR(date) as year')
            ->whereHas('general_journal.account.classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->union(InitialBalance::selectRaw('YEAR(date) as year')
                ->whereHas('account.classification.parent', function ($q) use ($session) {
                    $q->where('pesantren_id', $session);
                }))->orderBy('year', 'DESC')->get();

        // if years is empty, set default year to current year
        if ($years->isEmpty()) {
            //same data structure as $years
            $years = collect([
                (object) [
                    'year' => date('Y')
                ]
            ]);
        }

        $accounts = Account::with('classification.parent')
            ->whereHas('classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->get();

        //send data to view
        $this->data = $data;
        $this->years = $years;
        $this->accounts = $accounts;
        $this->account = $account;
        $this->log = $log;
        $this->session = $session;
    }
}
