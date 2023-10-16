<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class BukuBesar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static string $view = 'filament.pages.buku-besar';

    //send custom data to view

    public $data;
    public $count = 0;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }

    public function mount()
    {
        $this->data = "makan";
    }
}
