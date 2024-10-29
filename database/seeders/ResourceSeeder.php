<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
 
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \DB::table('resource_link_types')->insert([
            [   
                'title'=>"youtube",
                'code'=>"youtube",
                 
            ],
        ]);
        \DB::table('resource_types')->insert([
            [
                'title'=>"anh",
                "code"=>"image",
              
            ], 
            [
                'title'=>"video",
                "code"=>"video",
              
            ], 
            [
                'title'=>"audio",
                "code"=>"audio",
              
            ], 
            [
                'title'=>"document",
                "code"=>"document",
              
            ], 
            
           
        ]);
        \DB::table('resources')->insert([
            [   
                'title'=>"baihat",
                'slug'=>"baihat",

				'link_code'=>"youtube",
				'type_code'=>"video",
				'url'=>"https://www.youtube.com/watch?v=05M5jKoxw78",
				'description'=>"bai hat",
                 
            ],
        ]);
         
    }
}
