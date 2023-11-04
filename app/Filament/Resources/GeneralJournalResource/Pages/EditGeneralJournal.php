<?php

namespace App\Filament\Resources\GeneralJournalResource\Pages;

use App\Filament\Resources\GeneralJournalResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditGeneralJournal extends EditRecord
{
    protected static string $resource = GeneralJournalResource::class;

    public static ?string $title = 'Edit Jurnal Umum';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $detail_journal_id = $this->record->id;

        $debit = 0;
        $credit = 0;

        $transactions = $this->data['journals'];
        $tahun = $this->data['date'];

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
                ->title('Gagal Mengedit Jurnal')
                ->body('Debit dan Kredit tidak seimbang')
                ->danger()
                ->send();
            //halting
            $this->halt();
        }

        //check if initial balance for account in this year is enough to debit or credit
        foreach ($transactions as $transaction) {
            $initial_balance = \App\Models\InitialBalance::where('account_id', $transaction['account_id'])
                ->whereYear('date', $tahun)
                ->first();
            //if initial balance not exist, halting
            if (!$initial_balance) {
                Notification::make()
                    ->title('Gagal Menambahkan Jurnal')
                    ->body('Saldo awal tidak ditemukan')
                    ->danger()
                    ->send();
                //halting
                $this->halt();
            }
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

        //end of checking

        // delete all old general journals
        //delet to general journal table where journal_detail_id = $detail_journal_id
        DB::table('general_journals')->where('journal_detail_id', $detail_journal_id)->delete();

        //modify the transactions add primary key string id to array of transactions
        foreach ($transactions as &$transaction) {
            //generate uuid
            $transaction['id'] = uniqid();
        }

        unset($transaction);

        //update journal detail
        DB::table('journal_details')->where('id', $detail_journal_id)->update([
            'receipt' => $this->data['receipt'],
            'date' => $this->data['date'],
            'description' => $this->data['description'],
            'journals' => $transactions,
        ]);

        //insert new general journals
        foreach ($transactions as $transaction) {
            $general_journal = new \App\Models\GeneralJournal();
            $general_journal->id = $transaction['id'];
            $general_journal->journal_detail_id = $detail_journal_id;
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
            ->title('Berhasil Mengedit Jurnal')
            ->body('Jurnal Umum berhasil di edit')
            ->success()
            ->send();
        //halting
        $this->halt();
    }
}
