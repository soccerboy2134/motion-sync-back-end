<?php

namespace Database\Seeders;

use App\Models\WorkOut;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkOutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkOut::factory(200)->create();
    }
}
