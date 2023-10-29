<?php

namespace App\Filament\Pages;

use App\Models\AccountParent;
use App\Models\InitialBalance;
use App\Models\JournalDetail;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class NeracaJalur extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static string $view = 'filament.pages.neraca-jalur';

    protected static ?string $navigationGroup = 'Manajemen Neraca';
    protected static ?string $navigationLabel = 'Neraca Lajur';
    protected static ?string $pluralModelLabel = 'Neraca Lajur';

    public $count = 1;

    public function getTitle(): string|Htmlable
    {
        return 'Neraca Lajur';
    }

    public $balance;
    public $years;
    public $year;
    public $month;
    public $session;


    public function mount()
    {
        $session = Filament::getTenant()->id;

        if (isset($_GET['year'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }

        //get list of account with the balance of each account
        //balance get from initial balance and journal
        // if initial balance not exist, then balance is 0
        // if journal exist, then balance is initial balance + journal
        // if journal not exist, then balance is initial balance
        // if journal exist but the position is different, then balance is initial balance - journal
        // if journal exist and the position is same, then balance is initial balance + journal



        // hitung saldo akun
        $parents = AccountParent::with('classification.parent')->where('pesantren_id', $session)->get();
        $i = 0;
        foreach ($parents as $p) {
            $balance[$i]['parent_code'] = $p->parent_code;
            $balance[$i]['parent_name'] = $p->parent_name;
            $classification = $p->classification()->get();
            $j = 0;
            foreach ($classification as $c) {
                $balance[$i]['classification'][$j]['classification_id'] = $c->id;
                $balance[$i]['classification'][$j]['classification_name'] = $c->classification_name;
                $account = $c->account()->with('initialBalance', 'journal')->get();
                $k = 0;
                foreach ($account as $a) {
                    $balance[$i]['classification'][$j]['account'][$k]['account_id'] = $a->id;
                    $balance[$i]['classification'][$j]['account'][$k]['account_name'] = $a->account_name;
                    $balance[$i]['classification'][$j]['account'][$k]['account_code'] = $a->account_code;
                    $balance[$i]['classification'][$j]['account'][$k]['position'] = $a->position;

                    if (!$a->initialBalance()->whereYear('date', $year)->first()) {
                        $beginning_balance = 0;
                    } else {
                        $beginning_balance = $a->initialBalance()->whereYear('date', $year)->first()->amount;
                    }
                    $position = $a->position;
                    $code = $a->numberCode;

                    if ($a->journal()->exists()) {
                        $ending_balance = $beginning_balance;
                        $journals = $a->journal()->whereHas('detail', function ($q) use ($year, $month) {
                            $q->whereYear('date', $year);
                            $q->whereMonth('date', '>=', '01');
                            $q->whereMonth('date', '<=', $month);
                            // $q->whereIn(DB::RAW('month(date)'), $month);
                        })->get();
                        // dd($journals);
                        foreach ($journals as $journal) {
                            //credit same as kredit
                            if ($journal->position == "credit") {
                                $journal->position = "kredit";
                            }
                            if ($journal->position == $position) {
                                $ending_balance += $journal->amount;
                                // dd($ending_balance, $journal->detail);
                                // dd("masuk sini");
                            } else {
                                $ending_balance -= $journal->amount;
                            }
                            // dd($ending_balance);
                        }
                    } else {
                        if ($a->initialBalance()->whereYear('date', $year)->first()) {
                            $ending_balance = $beginning_balance;
                            // dd("masuk");
                        } else {
                            $ending_balance = "0";
                        }
                    }
                    $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] = $ending_balance;

                    $k++;
                }
                $j++;
            }
            $i++;
        }
        // dd($balance);

        $years = JournalDetail::selectRaw('YEAR(date) as year')
            ->whereHas('general_journal.account.classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->union(InitialBalance::selectRaw('YEAR(date) as year')
                ->whereHas('account.classification.parent', function ($q) use ($session) {
                    $q->where('pesantren_id', $session);
                }))->orderBy('year', 'DESC')->get();

        // dd($years->toArray());

        // if years is empty, set default year to current year
        if ($years->isEmpty()) {
            //same data structure as $years
            $years = collect([
                (object) [
                    'year' => date('Y')
                ]
            ]);
        }

        //send data to view
        $this->balance = $balance;
        $this->years = $years;
        $this->year = $year;
        $this->month = $month;
        $this->session = $session;
    }
}
