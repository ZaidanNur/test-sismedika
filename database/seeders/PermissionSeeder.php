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
        // $this->createResourcePermissionsFor('dashboard', 'cms');
        // $this->createResourcePermissionsFor('admins', 'cms');
        // $this->createResourcePermissionsFor('roles', 'cms');
        // $this->createResourcePermissionsFor('top_up_packages', 'cms');
        // $this->createResourcePermissionsFor('top_up_transactions', 'cms');
        // $this->createResourcePermissionsFor('reseller_index', 'cms');
        // $this->createResourcePermissionsFor('package_price_lists', 'cms');
        // $this->createResourcePermissionsFor('reseller_transaction', 'cms');
        //
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
