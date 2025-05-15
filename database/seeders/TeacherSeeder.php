<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Teaching_1\Models\Teacher;
use App\Modules\Teaching_1\Models\DonVi;
use App\Modules\Teaching_1\Models\ChuyenNganh;
use App\Models\User;

class TeacherSeeder extends Seeder {
    public function run() {
        // Danh sách giáo viên cố định
        $teachers = [
            ['mgv' => 'MGV001', 'user_id' => $users[0] ?? 1, 'ma_donvi' => 1, 'chuyen_nganh' => 1, 'hoc_ham' => 'GS', 'hoc_vi' => 'TS', 'loai_giangvien' => 'Cơ hữu'],
            ['mgv' => 'MGV002', 'user_id' => $users[1] ?? 2, 'ma_donvi' => 2, 'chuyen_nganh' => 2, 'hoc_ham' => 'PGS', 'hoc_vi' => 'ThS', 'loai_giangvien' => 'Thỉnh giảng'],
            ['mgv' => 'MGV003', 'user_id' => $users[2] ?? 3, 'ma_donvi' => 1, 'chuyen_nganh' => 3, 'hoc_ham' => 'GS', 'hoc_vi' => 'TS', 'loai_giangvien' => 'Cơ hữu'],
            ['mgv' => 'MGV004', 'user_id' => $users[3] ?? 4, 'ma_donvi' => 3, 'chuyen_nganh' => 1, 'hoc_ham' => 'TS', 'hoc_vi' => 'ThS', 'loai_giangvien' => 'Thỉnh giảng'],
        ];

        // Duyệt danh sách và tạo bản ghi
        foreach ($teachers as $teacher) {
            Teacher::create($teacher);
        }

        $this->command->info('Seed giáo viên thành công với dữ liệu cố định!');
    }
}














