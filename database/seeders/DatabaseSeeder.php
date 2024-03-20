<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->command->info("Created Roles & Permissions successful.");

        $this->call(AdminTableSeeder::class);
        $this->command->info("Created an admin account successful");

        $sample_db = database_path("sample.sql");
        DB::unprepared(file_get_contents($sample_db));
        $this->command->info("Created sample data successful.");
    }
}
