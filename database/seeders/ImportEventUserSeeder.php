<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportEventUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/event_registations_seed_data.txt');
        
        if (!file_exists($filePath)) {
            $this->command->error('File event_registations_seed_data.txt không tồn tại!');
            return;
        }

        $this->command->info('Bắt đầu import event_registations...');

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Bỏ qua dòng đầu tiên (INSERT INTO ...)
        array_shift($lines);

        $successCount = 0;
        $errorCount = 0;

        foreach ($lines as $line) {
            $line = trim($line, ", \r\n);");
            if (empty($line)) continue;

            // Loại bỏ dấu ngoặc đầu/cuối nếu có
            $line = trim($line, '()');

            // Tách các giá trị
            $parts = str_getcsv($line, ',');

            // Nếu số lượng trường không đúng, bỏ qua
            if (count($parts) < 7) {
                $errorCount++;
                continue;
            }

            try {
                // Map dữ liệu
                $event_id = trim($parts[1]);
                $user_id = trim($parts[0]);
                $status = trim($parts[2]);
                $reason = trim($parts[3]);
                $created_at = trim($parts[5], " '");
                $updated_at = trim($parts[6], " '");

                // Kiểm tra event_id có tồn tại không
                $eventExists = DB::table('event')->where('id', $event_id)->exists();
                if (!$eventExists) {
                    $this->command->warn("Bỏ qua bản ghi: event_id {$event_id} không tồn tại");
                    $errorCount++;
                    continue;
                }

                // Thêm vào database
                DB::table('event_registations')->insert([
                    'event_id' => $event_id,
                    'user_id' => $user_id,
                    'status' => 'pending',
                    'reason' => null,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $this->command->error("Lỗi khi import: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->command->info('Hoàn thành import event_registations:');
        $this->command->info('- Thành công: ' . $successCount);
        $this->command->info('- Lỗi: ' . $errorCount);
    }
} 