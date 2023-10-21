<?php

namespace App\Filament\Resources\NeracaAwalResource\Pages;

use App\Filament\Resources\NeracaAwalResource;
use App\Models\AccountParent;
use App\Models\InitialBalance;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class NeracaAwal extends Page
{
    protected static string $resource = NeracaAwalResource::class;

    protected static string $view = 'filament.resources.neraca-awal-resource.pages.neraca-awal';

    public function create()
    {
        //route to the create pageof the resource to http://127.0.0.1:8000/dashboard/1/neraca-awals/create hardcoded

        $currentPesantren = Filament::getTenant()->id;

        $this->redirect('/dashboard/' . $currentPesantren . '/neraca-awals/create');
    }

    public array $years = [];
    public string $year = '';
    public $initialBalances = [];
    public $parent = [];
    public $totalDebit = 0;
    public $totalKredit = 0;
    public $balanceStatus = '';

    public function mount()
    {
        $currentPesantren = Filament::getTenant()->id;

        $years = InitialBalance::where('pesantren_id', $currentPesantren)
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->get()
            ->pluck('year');

        //convert to array
        $years = $years->toArray();
        //sort the array from the newest year to the oldest year
        rsort($years);

        $this->years = $years;
        //set the selected year to the newest year

        //if years is empty, set the year to null
        if (empty($years)) {
            //get now year
            $this->year = date('Y');
        } else {
            $this->year = $years[0];
        }

        //if years is null, set the year to null

        $initialBalancesbyYear = InitialBalance::where('pesantren_id', Filament::getTenant()->id)
            ->whereYear('date', $this->year)
            ->get();
        //get the parent account, account > classification > parent
        $initialBalancesbyYear->map(function ($item) {
            $item->account->load('classification.parent');
        });
        //convert to so can be used in the view by ->
        //sort by account_code
        $initialBalancesbyYear = $initialBalancesbyYear->sortBy('account.account_code');

        $this->totalDebit = 0;
        $this->totalKredit = 0;

        //get the total debit and kredit this year
        foreach ($initialBalancesbyYear as $initialBalance) {
            if ($initialBalance->account->position == 'debit') {
                $this->totalDebit = $this->totalDebit + $initialBalance->amount;
            }
            if ($initialBalance->account->position == 'kredit') {
                $this->totalKredit = $this->totalKredit + $initialBalance->amount;
            }
        }

        //get the balance status
        if ($this->totalDebit != $this->totalKredit) {
            $this->balanceStatus = 'Unbalance';
        } else {
            $this->balanceStatus = 'Balance';
        }

        $this->initialBalances = $initialBalancesbyYear;
    }

    //submit the year
    public function submit()
    {
        $this->year = $this->year;
        $initialBalancesbyYear = InitialBalance::where('pesantren_id', Filament::getTenant()->id)
            ->whereYear('date', $this->year)
            ->get();
        //get the parent account, account > classification > parent
        $initialBalancesbyYear->map(function ($item) {
            $item->account->load('classification.parent');
        });
        //convert to so can be used in the view by ->
        //sort by account_code
        $initialBalancesbyYear = $initialBalancesbyYear->sortBy('account.account_code');

        $this->totalDebit = 0;
        $this->totalKredit = 0;

        //get the total debit and kredit this year
        foreach ($initialBalancesbyYear as $initialBalance) {
            if ($initialBalance->account->position == 'debit') {
                $this->totalDebit = $this->totalDebit + $initialBalance->amount;
            }
            if ($initialBalance->account->position == 'kredit') {
                $this->totalKredit = $this->totalKredit + $initialBalance->amount;
            }
        }

        //get the balance status
        if ($this->totalDebit != $this->totalKredit) {
            $this->balanceStatus = 'Unbalance';
        } else {
            $this->balanceStatus = 'Balance';
        }

        $this->initialBalances = $initialBalancesbyYear;
    }

    public function delete($id)
    {
        $initialBalance = InitialBalance::find($id);
        $initialBalance->delete();

        Notification::make()
            ->title('Berhasil Menghapus Neraca Awal')
            ->body('Neraca Awal berhasil dihapus')
            ->success()
            ->send();

        $this->redirect('/dashboard/' . Filament::getTenant()->id . '/neraca-awals');
    }
}
