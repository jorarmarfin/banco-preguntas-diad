<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sudo = Role::create(['name' => 'sudo']);
        $sy = Role::create(['name' => 'System']);
        $te = Role::create(['name' => 'Teacher']);
        $de = Role::create(['name' => 'deputy']);

        Permission::create(['name' => 'menu system'])->syncRoles([$sy]);
        Permission::create(['name' => 'menu deputy'])->syncRoles([$sy,$de]);
        Permission::create(['name' => 'menu teacher'])->syncRoles([$sy,$te]);
        Permission::create(['name' => 'menu exams'])->syncRoles([$sy,$de,$te]);
        Permission::create(['name' => 'menu questions'])->syncRoles([$sy,$de,$te]);
        Permission::create(['name' => 'menu settings'])->syncRoles([$sy]);
        Permission::create(['name' => 'menu users'])->syncRoles([$sy]);
        Permission::create(['name' => 'menu roles'])->syncRoles([$sy]);
        Permission::create(['name' => 'menu permissions'])->syncRoles([$sy]);
        Permission::create(['name' => 'menu draws'])->syncRoles([$sy,$de,$te]);
        Permission::create(['name' => 'menu terms'])->syncRoles([$sy,$de]);
        Permission::create(['name' => 'menu chapters'])->syncRoles([$sy,$de]);
        Permission::create(['name' => 'menu topics'])->syncRoles([$sy,$de]);
        Permission::create(['name' => 'menu question categories'])->syncRoles([$sy,$de]);
        Permission::create(['name' => 'menu question types'])->syncRoles([$sy ,$de]);
        Permission::create(['name' => 'menu subject categories'])->syncRoles([$sy,$de]);
        Permission::create(['name' => 'menu subjects'])->syncRoles([$sy,$de]);
        Permission::create(['name' => 'menu question proposals'])->syncRoles([$sy,$de]);

        Permission::create(['name' => 'manage Subject categories'])->syncRoles([$sy]);
        Permission::create(['name' => 'manage Subjects'])->syncRoles([$sy]);
        Permission::create(['name' => 'manage Chapters'])->syncRoles([$sy,$de]);
        Permission::create(['name' => 'manage Topics'])->syncRoles([$sy,$de]);
        Permission::create(['name' => 'manage Questions'])->syncRoles([$sy,$de,$te]);
        Permission::create(['name' => 'manage Question categories'])->syncRoles([$sy,$de,$te]);
        Permission::create(['name' => 'manage Question types'])->syncRoles([$sy,$de,$te]);
        Permission::create(['name' => 'manage Exams'])->syncRoles([$sy,$de,$te]);
        Permission::create(['name' => 'manage Draws'])->syncRoles([$sy,$de,$te]);
        Permission::create(['name' => 'manage Terms'])->syncRoles([$sy,$de]);
        Permission::create(['name' => 'manage Users'])->syncRoles([$sy]);
        Permission::create(['name' => 'manage Roles'])->syncRoles([$sy]);
        Permission::create(['name' => 'manage Permissions'])->syncRoles([$sy]);
        Permission::create(['name' => 'manage Settings'])->syncRoles([$sy]);
        Permission::create(['name' => 'manage Question proposals'])->syncRoles([$sy,$de]);
    }
}
