<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportUserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/sample_u_group.txt');
        
        if (!file_exists($filePath)) {
            $this->command->error('File sample_u_group.txt không tồn tại!');
            return;
        }

        $this->command->info('Bắt đầu import user groups...');
        
        // Đọc toàn bộ nội dung file
        $content = file_get_contents($filePath);
        
        // Tách các nhóm theo dòng trống
        $groups = preg_split('/\n\s*\n/', $content);
        
        $successCount = 0;
        $errorCount = 0;

        foreach ($groups as $index => $group) {
            try {
                // Tách các dòng trong nhóm
                $lines = explode("\n", trim($group));
                
                $groupData = [];
                foreach ($lines as $line) {
                    // Tách key và value theo dấu :
                    list($key, $value) = explode(':', $line, 2);
                    $groupData[trim($key)] = trim($value);
                }

                // Kiểm tra dữ liệu bắt buộc
                if (!isset($groupData['title']) || !isset($groupData['status'])) {
                    throw new \Exception("Thiếu thông tin bắt buộc ở nhóm " . ($index + 1));
                }

                // Tạo slug từ title
                $slug = strtolower(str_replace(' ', '-', $groupData['title']));

                // Chuẩn bị dữ liệu để insert
                $data = [
                    'title' => $groupData['title'],
                    
                    'description' => $groupData['description'] ?? null,
                    'status' => $groupData['status'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                // Thêm vào database
                DB::table('u_groups')->insert($data);
                $successCount++;
                
                $this->command->info('Đã import thành công: ' . $data['title']);
                
            } catch (\Exception $e) {
                $this->command->error("Lỗi ở nhóm " . ($index + 1) . ": " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->command->info('Hoàn thành import user groups:');
        $this->command->info('- Thành công: ' . $successCount);
        $this->command->info('- Lỗi: ' . $errorCount);
    }
} 