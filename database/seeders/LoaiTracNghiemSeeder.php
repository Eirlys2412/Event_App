<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Exercise\Models\TracNghiemLoai;

class LoaiTracNghiemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Dữ liệu modules cần thêm
        $loaitracnghiem = [
            [
                'title' => 'Loại quan sát',
                'status' => '1',
            ],
            [
                'title' => 'Loại vấn đáp',
                'status' => '1',
            ],
            [
                'title' => 'Loại viết',
                'status' => '1',
            ],
        ];

        // Chèn dữ liệu vào bảng 'modules'
        foreach ($loaitracnghiem as $loaitracnghiem) {
            TracNghiemLoai::create($loaitracnghiem);
        }
    }
}
