<?php

namespace App\Filament\Resources\GeneralJournalResource\Pages;

use App\Filament\Resources\GeneralJournalResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateGeneralJournal extends CreateRecord
{
    protected static string $resource = GeneralJournalResource::class;

    protected static bool $canCreateAnother = false;

    //change title
    public static ?string $title = 'Tambah Jurnal';

    //validation before submit
    public function beforeCreate()
    {
        //get filament repeater value
        $transactions = $this->data['transactions'];

        //check if the debit and credit is balance
        $debit = 0;
        $credit = 0;

        foreach ($transactions as $transaction) {
            $debit += $transaction['debit'];
            $credit += $transaction['credit'];
        }

        if ($debit != $credit) {
            Notification::make()
                ->title('Gagal Menambahkan Jurnal')
                ->body('Debit dan Kredit tidak seimbang')
                ->danger()
                ->send();
            //halting
            $this->halt();
        }



        dd($transactions);
        //halting
        $this->halt();
        $this->validate([
            'record.kwitansi' => 'required',
            'record.date' => 'required',
            'record.description' => 'required',
            'record.transactions' => 'required',
        ]);
    }
}
