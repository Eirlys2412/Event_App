<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Teaching_1\Models\Nganh;
use Illuminate\Support\Str;

class NganhSeeder extends Seeder
{
    public function run()
    {
        // Mảng dữ liệu mẫu
        $nganhs = [
            [
                'title' => 'Công nghệ thông tin',
                'slug' => Str::slug('Công nghệ thông tin'),
                'donvi_id' => 1, // Giả sử đã có đơn vị với ID 1
                'code' => 'CNTT',
                'content' => 'Chương trình đào tạo về công nghệ thông tin.',
                'status' => 'active',
            ],
            [
                'title' => 'Kỹ thuật điện',
                'slug' => Str::slug('Kỹ thuật điện'),
                'donvi_id' => 1, // Giả sử đã có đơn vị với ID 1
                'code' => 'KTĐ',
                'content' => 'Chương trình đào tạo về kỹ thuật điện.',
                'status' => 'active',
            ],
            [
                'title' => 'Quản trị kinh doanh',
                'slug' => Str::slug('Quản trị kinh doanh'),
                'donvi_id' => 2, // Giả sử đã có đơn vị với ID 2
                'code' => 'QTKD',
                'content' => 'Chương trình đào tạo về quản trị kinh doanh.',
                'status' => 'inactive',
            ],
            [
                'title' => 'Ngôn ngữ Anh',
                'slug' => Str::slug('Ngôn ngữ Anh'),
                'donvi_id' => 3, // Giả sử đã có đơn vị với ID 3
                'code' => 'NNAN',
                'content' => 'Chương trình đào tạo về ngôn ngữ Anh.',
                'status' => 'active',
            ],
        ];

        // Tạo bản ghi trong cơ sở dữ liệu
        foreach ($nganhs as $nganh) {
            Nganh::create($nganh);
        }
    }
}