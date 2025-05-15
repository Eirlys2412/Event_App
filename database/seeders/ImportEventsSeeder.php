<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/sample_events.txt');
        
        if (!file_exists($filePath)) {
            $this->command->error('File sample_events.txt không tồn tại!');
            return;
        }

        $this->command->info('Bắt đầu import events...');
        
        // Đọc file theo từng dòng
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $successCount = 0;
        $errorCount = 0;

        foreach ($lines as $index => $line) {
            try {
                // Parse JSON data
                $eventData = json_decode($line, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("Lỗi parse JSON ở dòng " . ($index + 1));
                }

                // Kiểm tra dữ liệu bắt buộc
                $requiredFields = ['title', 'slug', 'summary', 'description', 'timestart', 'timeend', 'diadiem', 'event_type_id'];
                foreach ($requiredFields as $field) {
                    if (!isset($eventData[$field])) {
                        throw new \Exception("Thiếu trường bắt buộc: {$field}");
                    }
                }

                // Chuẩn bị dữ liệu để insert
                $data = [
                    'title' => $eventData['title'],
                    'slug' => $eventData['slug'],
                    'summary' => $eventData['summary'],
                    'description' => $eventData['description'],
                    'timestart' => $eventData['timestart'],
                    'timeend' => $eventData['timeend'],
                    'diadiem' => $eventData['diadiem'],
                    'event_type_id' => $eventData['event_type_id'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                // Insert event
                $eventId = DB::table('event')->insertGetId($data);

                // Xử lý tags nếu có
                if (isset($eventData['tags']) && is_array($eventData['tags'])) {
                    foreach ($eventData['tags'] as $tag) {
                        DB::table('event_tags')->insert([
                            'event_id' => $eventId,
                            'tag' => $tag,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }

                // Xử lý resources nếu có
                if (isset($eventData['resources']) && is_array($eventData['resources'])) {
                    foreach ($eventData['resources'] as $resource) {
                        if (isset($resource['type']) && isset($resource['items'])) {
                            foreach ($resource['items'] as $item) {
                                DB::table('event_resources')->insert([
                                    'event_id' => $eventId,
                                    'type' => $resource['type'],
                                    'item' => $item,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ]);
                            }
                        }
                    }
                }

                $successCount++;
                $this->command->info('Đã import thành công: ' . $data['title']);
                
            } catch (\Exception $e) {
                $this->command->error("Lỗi ở dòng " . ($index + 1) . ": " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->command->info('Hoàn thành import events:');
        $this->command->info('- Thành công: ' . $successCount);
        $this->command->info('- Lỗi: ' . $errorCount);
    }
} 