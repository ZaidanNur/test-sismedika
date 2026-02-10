<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createKasirRole();
        $this->createPelayanRole();
    }

    public function createKasirRole(): void
    {
        $role = Role::findOrCreate('Kasir', 'web');
        $role->givePermissionTo(
            Permission::where('guard_name', 'web')
                ->where(function ($q) {
                    $q->where('name', 'LIKE', '%orders%')
                      ->orWhere('name', 'LIKE', '%pos%');
                })->get()
        );
    }

    public function createPelayanRole(): void
    {
        $role = Role::findOrCreate('Pelayan', 'web');
        $role->givePermissionTo(Permission::where('guard_name','web')->get());
    }
}
