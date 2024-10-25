<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MediaProveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        $dates = [
            "2024-03-01",
            "2024-05-10",
            "2024-04-15",
            "2024-03-26",
            "2024-03-03"
        ];
        $tipe = [
            "Online","Printed","Both"
        ];
        for($i=0;$i<100;$i++){
            $tmp = [
                "id"=>Str::uuid(),
                "title"=>Str::random(20),
                "link"=>Str::random(210),
                "project_id"=>"2b8471e4-17f5-4a2f-9fa4-be73a47acc8b",
                "media_id"=>"b00a4700-8e0e-4eda-ad9c-ec6d1a2a1614",
                "reporter_id"=>"da7af1f4-b030-4946-8e2d-0612a473efa5",
                "date_posted"=>date("Y-m-d",strtotime($dates[mt_rand(0,299)%5])),
                "tipe"=>$tipe[mt_rand(0,230)%3]
            ];
            array_push($data,$tmp);
        }
        DB::table("media_prove")->insert($data);
    }
}
