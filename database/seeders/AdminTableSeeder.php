<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->createAdmin();
    }

    public function createAdmin()
    {
        $admin = Admin::firstOrCreate([
            'username' => 'administrator',
            'email'    => 'admin@example.com'
        ], [
            'name' => 'Admin',
            'password' => '123456',
            'super_user' => true
        ]);

        $admin->syncRoles(['Super Admin']);
    }
}