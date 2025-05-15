<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ImportUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/sample_users.txt');
        
        if (!file_exists($filePath)) {
            $this->command->error('File sample_users.txt không tồn tại!');
            return;
        }

        $this->command->info('Bắt đầu import users...');
        
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $successCount = 0;
        $errorCount = 0;

        foreach ($lines as $lineNumber => $line) {
            try {
                // Phân tách dữ liệu theo dấu |
                $data = explode('|', $line);
                
                if (count($data) < 8) {
                    throw new \Exception("Thiếu dữ liệu ở dòng " . ($lineNumber + 1));
                }

                // Xử lý dữ liệu
                $user = [
                    'full_name' => trim($data[0]),
                    'username' => trim($data[1]),
                    'email' => trim($data[2]),
                    'password' => Hash::make(trim($data[3])),
                    'role' => trim($data[4]),
                    'phone' => trim($data[5]),
                    'status' => trim($data[6]),
                    'code' => trim($data[7]),
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                // Thêm vào database
                DB::table('users')->insert($user);
                $successCount++;
                
                $this->command->info('Đã import thành công: ' . $user['full_name']);
                
            } catch (\Exception $e) {
                $this->command->error("Lỗi ở dòng " . ($lineNumber + 1) . ": " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->command->info('Hoàn thành import users:');
        $this->command->info('- Thành công: ' . $successCount);
        $this->command->info('- Lỗi: ' . $errorCount);
    }
}