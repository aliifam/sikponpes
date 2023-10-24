<?php

namespace App\Filament\Pages;

use App\Models\Account;
use App\Models\JournalDetail;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class BukuBesar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static string $view = 'filament.pages.buku-besar';

    public $data;
    public $years;
    public $year;
    public $accounts;
    public $account;
    public $initialBalance;
    public $finalBalance;

    public function mount()
    {
        //get all years to array
        $years = JournalDetail::selectRaw('YEAR(date) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get()
            ->toArray();
        //if years is empty, set default year to current year
        if (empty($years)) {
            $years = [date('Y')];
        } else {
            $years = array_column($years, 'year');
        }
        $this->years = $years;
        $this->year = $years[0];

        //get all accounts name, code, and id in this pesantren to array
        $accounts = Account::select('id', 'account_code', 'account_name')
            ->where('pesantren_id', Filament::getTenant()->id)
            ->get();
        $this->accounts = $accounts;
    }

    public function submit()
    {
        $this->data = "minum";
    }
}
