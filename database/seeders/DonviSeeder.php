<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DonviSeeder extends Seeder
{
    public function run()
    {
        // Đơn vị cha: Đại học Tây Nguyên
        $daiHocTayNguyenId = DB::table('donvi')->insertGetId([
            'title' => 'Đại học Tây Nguyên',
            'slug' => 'dai-hoc-tay-nguyen',
            'parent_id' => null,
            'children_id' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Đơn vị con: Khoa Công nghệ Thông tin và Phòng Đào tạo
        $khoaCNTTId = DB::table('donvi')->insertGetId([
            'title' => 'Khoa Công nghệ Thông tin',
            'slug' => 'khoa-cong-nghe-thong-tin',
            'parent_id' => $daiHocTayNguyenId,
            'children_id' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $phongDaoTaoId = DB::table('donvi')->insertGetId([
            'title' => 'Phòng Đào tạo',
            'slug' => 'phong-dao-tao',
            'parent_id' => $daiHocTayNguyenId,
            'children_id' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Cập nhật lại trường children_id của đơn vị cha để thêm các đơn vị con
        DB::table('donvi')->where('id', $daiHocTayNguyenId)->update([
            'children_id' => json_encode([
                ['id' => $khoaCNTTId, 'title' => 'Khoa Công nghệ Thông tin', 'child' => []],
                ['id' => $phongDaoTaoId, 'title' => 'Phòng Đào tạo', 'child' => []]
            ]),
        ]);
    }
}
