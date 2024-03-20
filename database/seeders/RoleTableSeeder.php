<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OCMS\Permission\Models\Permission;
use OCMS\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->addPermissions();
        $this->addRoles();
    }

    private function permissions()
    {
        return [
            'setting.view',
            'setting.general',
            'setting.email',
            'setting.sms',
            'setting.social-login',
            'system.view',
            'system.admins.view',
            'system.admins.create',
            'system.admins.edit',
            'system.admins.delete',
            'system.roles.view',
            'system.roles.edit',
            'system.roles.create',
            'system.roles.delete',
            'system.information.view',
            'system.activity.view',
            'system.error_log.view',
            'appearance.view',
            'pages.view',
            'pages.create',
            'pages.edit',
            'pages.delete',
            'blog.view',
            'blog.categories.view',
            'blog.categories.create',
            'blog.categories.edit',
            'blog.categories.delete',
            'blog.posts.view',
            'blog.posts.create',
            'blog.posts.edit',
            'blog.posts.delete',
            'contact.view',
            'appearance.themes.view',
            'appearance.theme_options.view',
            'appearance.menus.view',
            'appearance.partials.view',
            'appearance.partials.create',
            'appearance.partials.edit',
            'appearance.partials.delete',
            'media.view',
            'clients.view',
            'clients.edit',
            'clients.create',
            'clients.delete'
        ];
    }

    private function addPermissions()
    {
        $permissions = array_map(function($name) {
            return [
                'guard_name' => 'admins',
                'name'       => $name,
                'created_at' => Carbon::now()
            ];
        }, $this->permissions());

        foreach($permissions as $permission) {
            if(!Permission::where('name', $permission['name'])->exists()) {
                Permission::create($permission);
            }
        }
    }

    private function addRoles()
    {
        $super_admin = Role::firstOrCreate([
            'name'       => 'Super Admin',
            'guard_name' => 'admins'
        ]);

        $admin = Role::firstOrCreate([
            'name'       => 'Admin',
            'guard_name' => 'admins'
        ]);

        $admin_permissions = [
            'system.view',
            'system.admins.view',
            'system.admins.create',
            'system.admins.edit',
            'system.admins.delete',
            'system.roles.view',
            'system.roles.edit',
            'system.roles.create',
            'system.roles.delete',
            'system.information.view',
            'system.activity.view',
            'system.error_log.view',
            'clients.view',
            'clients.edit',
            'clients.create',
            'clients.delete'
        ];

        $admin->syncPermissions($admin_permissions);
    }
}
