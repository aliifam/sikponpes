<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ArusKas extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'aruskaschart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Arus Akun Kas';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $year = date('Y');
        $session = Filament::getTenant()->id;
        $account = Account::whereHas('classification.parent', function ($q) use ($session) {
            $q->where('pesantren_id', $session);
        })->where('account_name', 'Kas')
            ->first();

        $cash_i = [];
        $cash_o = [];

        $monthGroup = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

        foreach ($monthGroup as $month) {
            $cash_out = 0;
            $cash_in = 0;
            if (!$account->initialBalance()->whereYear('date', $year)->whereMonth('date', $month)->first()) {
                $cash_in = 0;
            } else {
                $cash_in = $account->initialBalance()->whereYear('date', $year)->whereMonth('date', $month)->first()->amount;
            }
            if ($account->journal()->exists()) {
                $jurnals = $account->journal()->whereHas('detail', function ($q) use ($year, $month) {
                    $q->whereYear('date', $year)->whereMonth('date', $month);
                })->get();

                foreach ($jurnals as $jurnal) {
                    if ($jurnal->position == "credit") {
                        $jurnal->position = "kredit";
                    }
                    if ($jurnal->position == "debit") {
                        $cash_in += $jurnal->amount;
                    } else if ($jurnal->position == "kredit") {
                        $cash_out += $jurnal->amount;
                    }
                }
            } else {
                if ($account->initialBalance()->whereYear('date', $year)->first()) {
                    $cash_in = $cash_in;
                } else {
                    $cash_out = 0;
                    $cash_in = 0;
                }
            }


            $thisMonth[] = $month;
            $cash_i[] = $cash_in;
            $cash_o[] = -$cash_out;
        }
        return [
            'chart' => [
                'type' => 'bar',
                'height' => 490,
                'stacked' => true,
            ],
            'series' => [
                [
                    'name' => 'Kas Masuk',
                    'data' => $cash_i
                ],
                [
                    'name' => 'Kas Keluar',
                    'data' => $cash_o
                ],
            ],
            'xaxis' => [
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Dec'],
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'colors' => ['#4ADE80', '#f43f5e'],
        ];
    }
}
