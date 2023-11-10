<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Pesantren;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
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

        function insertParentData($parentData, $pesantrenId, $parentCode)
        {
            // Memasukkan data parent
            $parentId = DB::table('account_parents')->insertGetId([
                'parent_name' => $parentData['name'],
                'parent_code' => $parentCode, // Use the provided parent code
                'pesantren_id' => $pesantrenId,
            ]);

            // Memasukkan data classification
            foreach ($parentData['classification'] as $classificationCode => $classificationData) {
                insertClassificationData($classificationData, $parentId, $classificationCode, $pesantrenId);
            }
        }

        function insertClassificationData($classificationData, $parentId, $classificationCode, $pesantrenId)
        {
            // Memasukkan data classification
            $classificationId = DB::table('account_classifications')->insertGetId([
                'classification_name' => $classificationData['name'],
                'classification_code' => $classificationCode, // Use the provided classification code
                'parent_id' => $parentId,
                'pesantren_id' => $pesantrenId,
            ]);

            // Memasukkan data account
            foreach ($classificationData['account'] as $accountCode => $accountData) {
                DB::table('accounts')->insert([
                    'account_name' => $accountData['name'],
                    'account_code' => $accountCode, // Use the provided account code
                    'position' => $accountData['position'],
                    'classification_id' => $classificationId,
                    'pesantren_id' => $pesantrenId,
                ]);
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
                "name" => "Liabilitas",
                "code" => "2",
                "classification" => array(
                    "2.1" => array(
                        "name" => "Utang Jangka Pendek",
                        "code" => "2.1",
                        "account" => array(
                            "2.1.1" => array(
                                "name" => "Utang Usaha",
                                "code" => "2.1.1",
                                "position" => "kredit"
                            ),
                            "2.1.2" => array(
                                "name" => "Utang Gaji",
                                "code" => "2.1.2",
                                "position" => "kredit"
                            ),
                            // "2.1.3" => array(
                            //     "name" => "Utang Bank",
                            //     "code" => "2.1.3",
                            //     "position" => "kredit"
                            // ),
                            "2.1.3" => array(
                                "name" => "Utang Pajak",
                                "code" => "2.1.3",
                                "position" => "kredit"
                            ),
                            "2.1.4" => array(
                                "name" => "Utang Lainnya",
                                "code" => "2.1.4",
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
                                "name" => "Ekuitas",
                                "code" => "3.1.1",
                                "position" => "kredit"
                            ),
                            // "3.1.2" => array(
                            //     "name" => "Ekuitas Akhir",
                            //     "code" => "3.1.2",
                            //     "position" => "kredit"
                            // ),
                            "3.1.2" => array(
                                "name" => "Prive",
                                "code" => "3.1.2",
                                "position" => "kredit"
                            ),
                        )
                    )
                )
            ),
            "4" => array(
                "name" => "Pendapatan",
                "code" => "4",
                "classification" => array(
                    "4.1" => array(
                        "name" => "Pendapatan",
                        "code" => "4.1",
                        "account" => array(
                            "4.1.1" => array(
                                "name" => "Pendapatan Bantuan/Sumbangan/Hibah",
                                "code" => "4.1.1",
                                "position" => "kredit"
                            ),
                            "4.1.2" => array(
                                "name" => "Pendapatan Uang Pendaftaran Santri",
                                "code" => "4.1.2",
                                "position" => "kredit"
                            ),
                            "4.1.3" => array(
                                "name" => "Pendapatan Sewa Tempat",
                                "code" => "4.1.3",
                                "position" => "kredit"
                            ),
                            "4.1.4" => array(
                                "name" => "Pendapatan SPP",
                                "code" => "4.1.4",
                                "position" => "kredit"
                            ),
                            "4.1.5" => array(
                                "name" => "Pendapatan Lainnya",
                                "code" => "4.1.5",
                                "position" => "kredit"
                            ),
                        )
                    )
                )
            ),
            "5" => array(
                "name" => "Biaya",
                "code" => "5",
                "classification" => array(
                    "5.1" => array(
                        "name" => "Biaya Operasional",
                        "code" => "5.1",
                        "account" => array(
                            "5.1.1" => array(
                                "name" => "Gaji Guru dan Staff",
                                "code" => "5.1.1",
                                "position" => "debit"
                            ),
                            "5.1.2" => array(
                                "name" => "Honorarium Pengajar",
                                "code" => "5.1.2",
                                "position" => "debit"
                            ),
                            "5.1.3" => array(
                                "name" => "Pemeliharaan dan Perbaikan Gedung",
                                "code" => "5.1.3",
                                "position" => "debit"
                            ),
                            "5.1.4" => array(
                                "name" => "Listrik, Air, dan Telepon",
                                "code" => "5.1.4",
                                "position" => "debit"
                            ),
                            "5.1.5" => array(
                                "name" => "Pendidian dan Pelatihan",
                                "code" => "5.1.5",
                                "position" => "debit"
                            ),
                            "5.1.6" => array(
                                "name" => "Pengadaan Bahan Ajar dan Perlengkapan",
                                "code" => "5.1.6",
                                "position" => "debit"
                            ),
                            "5.1.7" => array(
                                "name" => "Administrasi dan Umum",
                                "code" => "5.1.7",
                                "position" => "debit"
                            ),
                            "5.1.8" => array(
                                "name" => "Asuransi",
                                "code" => "5.1.8",
                                "position" => "debit"
                            ),
                            "5.1.9" => array(
                                "name" => "Pajak dan Retribusi",
                                "code" => "5.1.9",
                                "position" => "debit"
                            ),
                        )
                    ),
                    "5.2" => array(
                        "name" => "Biaya Keuangan",
                        "code" => "5.2",
                        "account" => array(
                            "5.2.1" => array(
                                "name" => "Bunga Bank",
                                "code" => "5.2.1",
                                "position" => "debit"
                            ),
                            "5.2.2" => array(
                                "name" => "Bunga Pinjaman",
                                "code" => "5.2.2",
                                "position" => "debit"
                            ),
                        )
                    ),
                    "5.3" => array(
                        "name" => "Biaya Lainnya",
                        "code" => "5.3",
                        "account" => array(
                            "5.3.1" => array(
                                "name" => "Penyusutan Aset Tetap",
                                "code" => "5.3.1",
                                "position" => "debit"
                            ),
                            "5.3.2" => array(
                                "name" => "Kerugian Piutang",
                                "code" => "5.3.2",
                                "position" => "debit"
                            ),
                            "5.3.3" => array(
                                "name" => "Kerugian Investasi",
                                "code" => "5.3.3",
                                "position" => "debit"
                            ),
                            "5.3.4" => array(
                                "name" => "Donasi dan Bantuan",
                                "code" => "5.3.4",
                                "position" => "debit"
                            ),
                        )
                    ),
                )
            ),
        );

        try {
            DB::beginTransaction();
            $pesantren = Pesantren::create($data);
            $pesantren->user()->attach(auth()->user());
            $pesantren_id = $pesantren->id;

            // Memasukkan data parent
            foreach ($defaultPesantrenData as $parentCode => $parentData) {

                insertParentData($parentData, $pesantren_id, $parentCode);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $pesantren;
    }
}
