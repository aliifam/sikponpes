<?php

namespace App\Filament\Resources\NeracaAwalResource\Pages;

use App\Filament\Resources\NeracaAwalResource;
use App\Models\InitialBalance;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
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
        $this->year = $years[0];
    }
}
