<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Teaching_2\Models\HinhThucThi;

class HinhThucThiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Dữ liệu modules cần thêm
        $hinhthucthi = [
            [
                'title' => 'Trắc nghiệm',
                'status' => '1',
            ],
            [
                'title' => 'Tự luận',
                'status' => '1',
            ],
        ];

        // Chèn dữ liệu vào bảng 'modules'
        foreach ($hinhthucthi as $hinhthucthi) {
            HinhThucThi::create($hinhthucthi);
        }
    }
}
