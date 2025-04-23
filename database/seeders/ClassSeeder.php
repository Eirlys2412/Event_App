<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Teaching_1\Models\ClassModel;
use App\Modules\Teaching_1\Models\Teacher;
use App\Modules\Teaching_1\Models\Nganh;

class ClassSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu cố định cho các lớp học
        $classes = [
            ['class_name' => 'Lớp CNTT01', 'teacher_id' => 1, 'nganh_id' => 1, 'description' => 'Lớp Công nghệ thông tin 01', 'max_students' => 40],
            ['class_name' => 'Lớp CNTT02', 'teacher_id' => 2, 'nganh_id' => 1, 'description' => 'Lớp Công nghệ thông tin 02', 'max_students' => 35],
            ['class_name' => 'Lớp KTPM01', 'teacher_id' => 3, 'nganh_id' => 2, 'description' => 'Lớp Kỹ thuật phần mềm 01', 'max_students' => 30],
            ['class_name' => 'Lớp KTPM02', 'teacher_id' => 4, 'nganh_id' => 2, 'description' => 'Lớp Kỹ thuật phần mềm 02', 'max_students' => 45],
        ];

        // Tạo dữ liệu lớp học từ danh sách trên
        foreach ($classes as $class) {
            ClassModel::create($class);
        }

        $this->command->info('Seed lớp học thành công với dữ liệu cố định!');
    }
}
