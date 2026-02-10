<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createResourcePermissions('pos', 'web');
        $this->createResourcePermissions('foods', 'web');
        $this->createResourcePermissions('food-categories', 'web');
        $this->createResourcePermissions('orders', 'web');
        $this->createResourcePermissions('pos-order', 'web');
    }

    public function createResourcePermissions(string $resource, string $guard): void
    {
        Permission::findOrCreate($guard.'.'.$resource.'.viewAny', $guard);
        Permission::findOrCreate($guard.'.'.$resource.'.view', $guard);
        Permission::findOrCreate($guard.'.'.$resource.'.create', $guard);
        Permission::findOrCreate($guard.'.'.$resource.'.update', $guard);
        Permission::findOrCreate($guard.'.'.$resource.'.delete', $guard);
        Permission::findOrCreate($guard.'.'.$resource.'.restore', $guard);
        Permission::findOrCreate($guard.'.'.$resource.'.forceDelete', $guard);
    }
}
