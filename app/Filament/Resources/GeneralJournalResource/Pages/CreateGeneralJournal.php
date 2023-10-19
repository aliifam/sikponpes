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
        $transactions = $this->data['journals'];

        //check if the debit and credit is balance
        $debit = 0;
        $credit = 0;

        foreach ($transactions as $transaction) {
            if ($this->data['position'] == 'debit') {
                $debit += $this->data['amount'];
            } else if ($this->data['position'] == 'kredit') {
                $credit += $this->data['amount'];
            }
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

        //foreach into general journal table
        foreach ($transactions as $transaction) {
            $journal = new \App\Models\GeneralJournal();
            $journal->journal_detail_id = $this->record->id;
            $journal->account_id = $transaction['account_id'];
            $journal->pesantren_id = $this->data['pesantren_id'];
            //check position debit or credit
            if ($transaction['debit'] != 0) {
                $journal->debit = $transaction['debit'];
                $journal->credit = 0;
            } else if ($transaction['kredit'] != 0) {
                $journal->debit = 0;
                $journal->credit = $transaction['kredit'];
            }
            $journal->save();
        }
    }
}
