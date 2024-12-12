<?php

namespace Database\Seeders;

use App\Modules\Event\Models\EventType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

 
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('setting_details')->insert([
            [   
                'company_name'=>"Tên công ty",
                'web_title'=>"Tên công ty",
                'phone'=>'0500363732',
                'address'=>'Ywang Buôn Ma Thuột, Đăk Lăk',
            ],
        ]);
        DB::table('users')->insert([
            [
                'full_name'=>"admin",
                "username"=>"admin",
                "email"=>"admin@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"admin",
                "phone"=>"111111111",
                'status'=>'active',
                'code' => '21103118',
            ], 
            [
                'full_name'=>"manager",
                "username"=>"manager",
                "email"=>"manager@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"manager",
                "phone"=>"111111119",
                'status'=>'active',
                'code' => '21103119',
            ],
            
            [
                'full_name'=>"giangvien",
                "username"=>"giangvien",
                "email"=>"giangvien@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"giangvien",
                "phone"=>"111111118",
                'status'=>'active',
                'code' => '21103120',
            ],
            [
                'full_name'=>"sinhvien",
                "username"=>"sinhvien",
                "email"=>"sinhvien@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"giangvien",
                "phone"=>"111111117",
                'status'=>'active',
                'code' => '21103121',
            ],

        ]);
        DB::table('roles')->insert([
            [   
                'alias'=>'admin',
                'title'=>"Quản trị viên",
                'status'=>'active',
            ],
            [   
                'alias'=>'manager',
                'title'=>"Quản lý",
                'status'=>'active',
            ],
           
            [   
                'alias'=>'giangvien',
                'title'=>"Giảng viên",
                'status'=>'active',
            ],
            [   
                'alias'=>'sinhvien',
                'title'=>"Sinhvien",
                'status'=>'active',
            ],
            [   
                'alias'=>'soft',
                'title'=>"soft",
                'status'=>'active',
            ],
        ]);

        $this->call([
            HinhThucThiSeeder::class,
            ResourceSeeder::class,
            DonviSeeder::class,
            ChuyennganhSeeder::class,
            NganhSeeder::class,
            EventTypeSeeder::class,
            LoaiTracNghiemSeeder::class,
        ]);
    }
}
