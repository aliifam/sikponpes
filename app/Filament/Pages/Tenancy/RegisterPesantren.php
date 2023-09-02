<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Pesantren;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RegisterPesantren extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Daftarkan Pesantren';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Pesantren')
                    ->autofocus()
                    ->required()
                    ->placeholder('Nama Pesantren'),
                Textarea::make('address')
                    ->label('Alamat Pesantren')
                    ->autofocus()
                    ->required()
                    ->placeholder('Alamat Pesantren'),
                TextInput::make('phone_number')
                    ->autofocus()
                    ->required()
                    ->placeholder('Nomor Telepon Pesantren'),
                Toggle::make('is_active')
                    ->label('Status Aktif'),
                Hidden::make('user_id')
                    ->default(auth()->user()->id)
            ]);
    }

    protected function handleRegistration(array $data): Pesantren
    {
        $pesantren = Pesantren::create($data);

        $pesantren->user()->attach(auth()->user());

        function insertData($data, $parent_id = null, $classification_id = null)
        {
            foreach ($data as $key => $value) {
                // Sisipkan data saat ini ke dalam tabel yang sesuai
                $table = ($parent_id === null) ? 'account_parents' : (($classification_id === null) ? 'account_classifications' : 'accounts_table'); // Ganti dengan nama tabel yang sesuai
                $element_id = DB::table($table)->insertGetId([
                    'parent_id' => $parent_id,
                    'classification_id' => $classification_id,
                    'name' => $value['name'],
                    'code' => $value['code'],
                    'position' => isset($value['position']) ? $value['position'] : null,
                ]);

                if (isset($value['classification']) && is_array($value['classification'])) {
                    // Jika ada klasifikasi, panggil rekursi dengan parent_id dan classification_id saat ini
                    insertData($value['classification'], $element_id, $element_id);
                }
            }
        }


        $defaultPesantrenData = array(
            "1" => array(
                "name" => "Aset",
                "code" => "1",
                "classification" => array(
                    "1.1" => array(
                        "name" => "Aset Lancar",
                        "code" => "1.1",
                        "account" => array(
                            "1.1.1" => array(
                                "name" => "Kas",
                                "code" => "1.1.1",
                                "position" => "debit"
                            ),
                            "1.1.2" => array(
                                "name" => "Bank",
                                "code" => "1.1.2",
                                "position" => "debit"
                            ),
                            "1.1.3" => array(
                                "name" => "Piutang Usaha",
                                "code" => "1.1.3",
                                "position" => "debit"
                            ),
                            "1.1.4" => array(
                                "name" => "Piutang Santri",
                                "code" => "1.1.4",
                                "position" => "debit"
                            ),
                            "1.1.5" => array(
                                "name" => "Penyisihan Piutang",
                                "code" => "1.1.5",
                                "position" => "kredit"
                            ),
                            "1.1.6" => array(
                                "name" => "Persediaan/Inventaris",
                                "code" => "1.1.6",
                                "position" => "debit"
                            ),
                            "1.1.7" => array(
                                "name" => "Perlengkapan",
                                "code" => "1.1.7",
                                "position" => "debit"
                            ),
                            "1.1.8" => array(
                                "name" => "Pembayaran Dimuka",
                                "code" => "1.1.8",
                                "position" => "debit"
                            ),
                            "1.1.9" => array(
                                "name" => "Aset Lancar Lain",
                                "code" => "1.1.9",
                                "position" => "debit"
                            ),
                        )
                    ),
                    "1.2" => array(
                        "name" => "Investasi",
                        "code" => "1.2.1",
                        "account" => array(
                            "1.2.1" => array(
                                "name" => "Investasi",
                                "code" => "1.2.1",
                                "position" => "debit"
                            ),
                        ),
                    ),
                    "1.3" => array(
                        "name" => "Aset Tetap",
                        "code" => "1.3",
                        "account" => array(
                            "1.3.1" => array(
                                "name" => "Tanah",
                                "code" => "1.3.1",
                                "position" => "debit"
                            ),
                            "1.3.2" => array(
                                "name" => "Kendaraan",
                                "code" => "1.3.2",
                                "position" => "debit"
                            ),
                            "1.3.3" => array(
                                "name" => "Peralatan dan Mesin",
                                "code" => "1.3.3",
                                "position" => "kredit"
                            ),
                            "1.3.4" => array(
                                "name" => "Gedung dan Bangunan",
                                "code" => "1.3.4",
                                "position" => "debit"
                            ),
                            "1.3.5" => array(
                                "name" => "Konstruksi dalam Pengerjaan",
                                "code" => "1.3.5",
                                "position" => "kredit"
                            ),
                            "1.3.6" => array(
                                "name" => "Akumulasi Penyusutan Aset",
                                "code" => "1.3.6",
                                "position" => "kredit"
                            ),
                            "1.3.7" => array(
                                "name" => "Aset Tetap Lainnya",
                                "code" => "1.3.7",
                                "position" => "kredit"
                            ),
                            "1.3.8" => array(
                                "name" => "Aset Tidak Berwujud",
                                "code" => "1.3.8",
                                "position" => "kredit"
                            ),
                            "1.3.9" => array(
                                "name" => "Aset Lain-lain",
                                "code" => "1.3.9",
                                "position" => "kredit"
                            ),
                            "1.3.10" => array(
                                "name" => "Akumulasi Penyusutan Aset Lain-lain",
                                "code" => "1.3.10",
                                "position" => "kredit"
                            ),
                        ),
                    ),
                ),
            ),
            "2" => array(
                "name " => "Liabilitas",
                "code" => "2",
                "classification" => array(
                    "2.1" => array(
                        "name" => "Utang Jangka Pendek",
                        "code" => "2.1",
                        "account" => array(
                            "2.1.1" => array(
                                "name" => "Utang Dagang",
                                "code" => "2.1.1",
                                "position" => "kredit"
                            ),
                            "2.1.2" => array(
                                "name" => "Utang Gaji",
                                "code" => "2.1.2",
                                "position" => "kredit"
                            ),
                            "2.1.3" => array(
                                "name" => "Utang Bank",
                                "code" => "2.1.3",
                                "position" => "kredit"
                            ),
                            "2.1.4" => array(
                                "name" => "Utang Pajak",
                                "code" => "2.1.4",
                                "position" => "kredit"
                            ),
                            "2.1.5" => array(
                                "name" => "Utang Lainnya",
                                "code" => "2.1.5",
                                "position" => "kredit"
                            ),
                        ),
                    ),
                    "2.2" => array(
                        "name" => "Utang Jangka Panjang",
                        "code" => "2.2",
                        "account" => array(
                            "2.2.1" => array(
                                "name" => "Utang Bank",
                                "code" => "2.2.1",
                                "position" => "kredit"
                            ),
                            "2.2.2" => array(
                                "name" => "Utang kepada Pihak Ketiga Jangka Panjang",
                                "code" => "2.2.2",
                                "position" => "kredit"
                            ),
                            "2.2.3" => array(
                                "name" => "Utang Jangka Panjang Lainnya",
                                "code" => "2.2.3",
                                "position" => "kredit"
                            ),
                        )
                    )
                )
            ),
            "3" => array(
                "name" => "Ekuitas",
                "code" => "3",
                "classification" => array(
                    "3.1" => array(
                        "name" => "Ekuitas",
                        "code" => "3.1",
                        "account" => array(
                            "3.1.1" => array(
                                "name" => "Ekuitas Awal",
                                "code" => "3.1.1",
                                "position" => "kredit"
                            ),
                            "3.1.2" => array(
                                "name" => "Ekuitas Akhir",
                                "code" => "3.1.2",
                                "position" => "kredit"
                            ),
                            "3.1.3" => array(
                                "name" => "Prive",
                                "code" => "3.1.3",
                                "position" => "kredit"
                            ),
                        )
                    )
                )
            ),
            "4" => array(),
            "5" => array(),
        );

        $parent_array = array(
            '1' => 'Asset',
            '2' => 'Liabilitas',
            '3' => 'Ekuitas',
            '4' => 'Pendapatan',
            '5' => 'Biaya',
        );

        $class_array = array(
            array(
                '1.1' => 'Aset Lancar',
                '1.2' => 'Investasi',
                '1.3' => 'Aset Tetap'
            ),
            array(
                '2.1' => 'Utang Jangka Pendek',
                '2.2' => 'Utang Jangka Panjang'
            ),
            array(
                '3.1' => 'Ekuitas'
            ),
            array(
                '4.1' => 'Pendapatan'
            ),
            array(
                '5.1' => 'Biaya'
            ),
        );

        try {
            DB::beginTransaction();
            insertData($defaultPesantrenData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $pesantren;
    }
}
