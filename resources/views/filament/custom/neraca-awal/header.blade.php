<h1 class="text-2xl font-bold">Nearaca Awal</h1>
<h2><span>Periode </span></h2>
<div>{{ $data }}</div>
<div>{{ $years }}</div>
<x-filament::input.wrapper>
    <x-filament::input.select wire:model="years">
        {{-- for each years order from latest to oldest years --}}
        @foreach ($years as $year)
            <option value="{{ $year }}">{{ $year }}</option>
        @endforeach
    </x-filament::input.select>
</x-filament::input.wrapper>

//when year selected data in table will change

// Path: resources/views/filament/custom/neraca-awal/table.blade.php
