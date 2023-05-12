<?php
namespace Database\Seeders;
use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            [
                'id' => 1,
                'name' => 'Greek',
                'code' => 'el'
            ],
            [
                'id' => 2,
                'name' => 'English',
                'code' => 'en'
            ],
            [
                'id' => 3,
                'name' => 'Español',
                'code' => 'es'
            ],
            [
                'id' => 4,
                'name' => 'Italiano',
                'code' => 'it'
            ],
            [
                'id' => 5,
                'name' => 'Norwegian',
                'code' => 'no'
            ]
        ];
        foreach ($data as $datum) {
            echo "\nProcessing language: " . $datum['name'] . "\n";
            Language::updateOrCreate(
                ['id' => $datum['id']],
                array_merge($datum, ['flag_img_path' => $datum['code'] . '.png'])
            );
        }
    }
}
