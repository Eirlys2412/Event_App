<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChuyennganhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('chuyennganhs')->insert([
            [
                'title' => 'Công nghệ thông tin',
                'slug' => Str::slug('Công nghệ thông tin'),
                'status' => 1,
            ],
            [
                'title' => 'Kỹ thuật phần mềm',
                'slug' => Str::slug('Kỹ thuật phần mềm'),
                'status' => 1,
            ],
            [
                'title' => 'Hệ thống thông tin',
                'slug' => Str::slug('Hệ thống thông tin'),
                'status' => 1,
            ],
            [
                'title' => 'Mạng máy tính và truyền thông',
                'slug' => Str::slug('Mạng máy tính và truyền thông'),
                'status' => 1,
            ],
            [
                'title' => 'Trí tuệ nhân tạo',
                'slug' => Str::slug('Trí tuệ nhân tạo'),
                'status' => 0,
            ],
        ]);
    }
}
