<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Recommend\Models\Module;

class RecommendSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu modules cần thêm
        $modules = [
            [
                'name' => 'Tin học đại cương',
                'tinchi' => '2',
                'user_id' => '4',
            ],
            [
                'name' => 'Xã hội học đại cương',
                'tinchi' => '2',
                'user_id' => '4',
            ],
            [
                'name' => 'Pháp luật Việt Nam đại cương',
                'tinchi' => '1',
                'user_id' => '1',
            ],
            [
                'name' => 'Lập trình Python',
                'tinchi' => '1',
                'user_id' => '1',
            ],
        ];

        // Chèn dữ liệu vào bảng 'modules'
        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}