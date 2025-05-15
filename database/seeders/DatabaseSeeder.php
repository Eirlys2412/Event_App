<?php

namespace Database\Seeders;

use App\Modules\Event\Models\EventType;
use App\Modules\Teaching_1\Models\Teacher;

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
                "role"=>"sinhvien",
                "phone"=>"111111117",
                'status'=>'active',
                'code' => '21103121',
            ],
            [
                'full_name'=>"Nguyễn Thị Hoài Thương",
                "username"=>"Nguyễn Thị Hoài Thương",
                "email"=>"thuongnt@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111118",
                'status'=>'active',
                'code' => '21103122',
            ],
            [
                'full_name'=>"Nguyễn Thị Hoài Thu",
                "username"=>"Nguyễn Thị Hoài Thu",
                "email"=>"thunt@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111119",
                'status'=>'active',
                'code' => '21103123',
            ],

            [
                'full_name'=>"Nguyễn Trần Đăng Quang",
                "username"=>"Nguyễn Trần Đăng Quang",
                "email"=>"quangnt@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111120",
                'status'=>'active',
                'code' => '21103124',
            ],
            [
                'full_name'=>"Trần Thị yến",
                "username"=>"Trần Thị yến",
                "email"=>"yentt@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111121",
                'status'=>'active',
                'code' => '21103125',
            ],
            [
                'full_name'=>"Nguyễn Đa Trường",
                "username"=>"Nguyễn Đa Trường",
                "email"=>"truongnd@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111122",
                'status'=>'active',
                'code' => '21103126',
            ],
            [
                'full_name'=>"Nguyễn Đức Huy",
                "username"=>"Nguyễn Đức Huy",
                "email"=>"huynd@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111123",
                'status'=>'active',
                'code' => '21103127',
            ],
            [
                'full_name'=>"Lê Quốc Tuấn",
                "username"=>"Lê Quốc Tuấn",
                "email"=>"tuanlq@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111124",
                'status'=>'active',
                'code' => '21103128',
            ],
            [
                'full_name'=>"Nguyễn Văn Huy",
                "username"=>"Nguyễn Văn Huy",
                "email"=>"huynd@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111125",
                'status'=>'active',
                'code' => '21103129',
            ],
            [
                'full_name'=>"Bùi Thị Huyền",
                "username"=>"Bùi Thị Huyền",
                "email"=>"huyenbt@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111126",
                'status'=>'active',
                'code' => '21103130',
            ],
            [
                'full_name'=>"Trương Thanh Tùng",
                "username"=>"Trương Thanh Tùng",
                "email"=>"tungtt@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111127",
                'status'=>'active',
                'code' => '21103131',
            ],
            [
                'full_name'=>"Nguyễn Thị Yến Vân",
                "username"=>"Nguyễn Thị Yến Vân",
                "email"=>"vannt@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111128",
                'status'=>'active',
                'code' => '21103132',
            ],
            [
                'full_name'=>"Nguyễn Thị Yến Vy",
                "username"=>"Nguyễn Thị Yến Vy",
                "email"=>"vynt@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111129",
                'status'=>'active',
                'code' => '21103133',
            ],
            [
                'full_name'=>"Nguyễn Thị Yến Vi",
                "username"=>"Nguyễn Thị Yến Vi",
                "email"=>"vint@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111130",
                'status'=>'active',
                'code' => '21103134',
            ],
            [
                'full_name'=>"Phạm Xuân Mạnh",
                "username"=>"Phạm Xuân Mạnh",
                "email"=>"manhpx@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111131",
                'status'=>'active',
                'code' => '21103135',
            ],
            [
                'full_name'=>"Lê Thanh Phong",
                "username"=>"Lê Thanh Phong",
                "email"=>"phonglt@gmail.com",
                "password"=>Hash::make('12345678'),
                "role"=>"Event Member",
                "phone"=>"111111132",
                'status'=>'active',
                'code' => '21103136',
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
                'alias'=>'eventmanager',
                'title'=>"Event Manager",
                'status'=>'active',
            ],
            [   
                'alias'=>'eventmember',
                'title'=>"Event Member",
                'status'=>'active',
            ],
            [   
                'alias'=>'leaderteam',
                'title'=>"leader team",
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
            TeacherSeeder::class,
            ClassSeeder::class,  
            ImportUserGroupSeeder::class,
            ImportUsersSeeder::class,
            ImportEventsSeeder::class,
            ImportEventRegistationsSeeder::class,
        ]);
    }
}
