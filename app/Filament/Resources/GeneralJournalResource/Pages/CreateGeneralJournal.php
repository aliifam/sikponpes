<?php

namespace App\Filament\Resources\GeneralJournalResource\Pages;

use App\Filament\Resources\GeneralJournalResource;
use App\Models\JournalDetail;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
//str uuid
use Illuminate\Support\Str;

class CreateGeneralJournal extends CreateRecord
{
    protected static string $resource = GeneralJournalResource::class;

    protected static bool $canCreateAnother = false;

    //change title
    public static ?string $title = 'Tambah Jurnal Umum';

    //validation before submit
    public function beforeCreate()
    {
        //get filament repeater value
        $transactions = $this->data['journals'];

        //check if the debit and credit is balance
        $debit = 0;
        $credit = 0;
        $journal_detail_id = uniqid();

        foreach ($transactions as $transaction) {
            if ($transaction['position'] == 'debit') {
                $debit += $transaction['amount'];
            } else if ($transaction['position'] == 'credit') {
                $credit += $transaction['amount'];
            }
        }

        // dd($debit, $credit);

        if ($debit != $credit) {
            Notification::make()
                ->title('Gagal Menambahkan Jurnal')
                ->body('Debit dan Kredit tidak seimbang')
                ->danger()
                ->send();
            //halting
            $this->halt();
        }

        //check if initial balance for account in this year is enough to debit or credit
        foreach ($transactions as $transaction) {
            $account = \App\Models\Account::find($transaction['account_id']);
            $initial_balance = $account->initialBalance()->where('year', date('Y'))->first();
            if ($transaction['position'] == 'debit') {
                if ($initial_balance->amount < $transaction['amount']) {
                    Notification::make()
                        ->title('Gagal Menambahkan Jurnal')
                        ->body('Saldo awal tidak mencukupi untuk melakukan debit')
                        ->danger()
                        ->send();
                    //halting
                    $this->halt();
                }
            } else if ($transaction['position'] == 'credit') {
                if ($initial_balance->amount < $transaction['amount']) {
                    Notification::make()
                        ->title('Gagal Menambahkan Jurnal')
                        ->body('Saldo awal tidak mencukupi untuk melakukan kredit')
                        ->danger()
                        ->send();
                    //halting
                    $this->halt();
                }
            }
        }

        //transaksi valid generate journal detail id string pk

        $journal_detail = new JournalDetail();
        $journal_detail->id = $journal_detail_id;
        $journal_detail->receipt = $this->data['receipt'];
        $journal_detail->date = $this->data['date'];
        $journal_detail->description = $this->data['description'];
        $journal_detail->pesantren_id = $this->data['pesantren_id'];

        //modify the transactions add primary key string id to array of transactions
        foreach ($transactions as &$transaction) {
            //generate uuid
            $transaction['id'] = uniqid();
        }

        unset($transaction);

        $journal_detail->journals = $transactions;
        $journal_detail->save();

        //foreach into general journal save transaction to db
        foreach ($transactions as $transaction) {
            $general_journal = new \App\Models\GeneralJournal();
            $general_journal->id = $transaction['id'];
            $general_journal->journal_detail_id = $journal_detail_id;
            $general_journal->account_id = $transaction['account_id'];
            $general_journal->position = $transaction['position'];
            $general_journal->amount = $transaction['amount'];
            $general_journal->pesantren_id = $this->data['pesantren_id'];
            $general_journal->save();
        }

        // redirect to index page
        // $this->redirect('/dashboard/' . Filament::getTenant()->id . '/general-journals');
        $this->redirect(GeneralJournalResource::getUrl('index'));
        //notifikasi
        Notification::make()
            ->title('Berhasil Menambahkan Jurnal')
            ->body('Jurnal umum berhasil ditambahkan')
            ->success()
            ->send();
        //halting
        $this->halt();
    }
}
