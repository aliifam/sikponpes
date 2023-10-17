<?php

namespace App\Filament\Resources\NeracaAwalResource\Pages;

use App\Filament\Resources\NeracaAwalResource;
use App\Models\InitialBalance;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateNeracaAwal extends CreateRecord
{
    protected static string $resource = NeracaAwalResource::class;

    //disable create another
    protected static bool $canCreateAnother = false;

    //validate data before create by check if the account_id already exist in the same year
    protected function beforeCreate(): void
    {
        //get the current pesantren id
        $currentPesantren = $this->data['pesantren_id'];
        $currentAccount = $this->data['account_id'];
        $currentDate = $this->data['date'];
        $currentAmount = $this->data['amount'];

        //check if the account_id already exist in the same year

        $check = InitialBalance::where('pesantren_id', $currentPesantren)
            ->where('account_id', $currentAccount)
            ->whereYear('date', Carbon::parse($currentDate)->format('Y'))
            ->first();
        if ($check) {
            Notification::make()
                ->title('Gagal Menambahkan Neraca Awal')
                ->body('Akun sudah ada di Neraca Awal Tahun ini')
                ->danger()
                ->send();
            //dont create the data
            $this->halt();
        } else {
            //create the data
            $this->data['pesantren_id'] = $currentPesantren;
            $this->data['account_id'] = $currentAccount;
            $this->data['date'] = $currentDate;
            $this->data['amount'] = $currentAmount;
        }
    }
}
