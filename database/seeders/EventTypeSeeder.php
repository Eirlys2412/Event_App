<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Events\Models\EventType;  // Đảm bảo đã import model EventType

class EventTypeSeeder extends Seeder
{
    public function run()
    {
        // Thêm dữ liệu mẫu vào bảng event_types
        EventType::create([
            'title' => 'Hội thảo',
            'slug' => 'hoi-thao',
            'status' => 'active',
            'location_type' => 'indoor',
            'location_address' => 'Phòng A1',
        ]);

        EventType::create([
            'title' => 'Cuộc thi',
            'slug' => 'cuoc-thi',
            'status' => 'active',
            'location_type' => 'outdoor',
            'location_address' => 'Sân khấu chính',
        ]);

        EventType::create([
            'title' => 'Họp mặt',
            'slug' => 'hop-mat',
            'status' => 'inactive',
            'location_type' => 'indoor',
            'location_address' => 'Phòng 305',
        ]);

        // Thêm các loại sự kiện khác tùy theo yêu cầu
    }
}
