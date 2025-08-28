<?php

namespace Database\Seeders;

use App\Enums\ServiceStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('services')->exists()){
            return;
        }

        $now = now();

        $services = [
            [
                'name' => 'Service 1',
                'description' => 'Description for Service 1',
                'price' => 100.00,
                'status' => ServiceStatus::ACTIVE->value,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Service 2',
                'description' => 'Description for Service 2',
                'price' => 200.00,
                'status' => ServiceStatus::INACTIVE->value,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('services')->insert($services);
    }
}
