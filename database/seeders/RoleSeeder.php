<?php

namespace Database\Seeders;

use App\Enums\SystemPermissions;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create permissions
        collect(SystemPermissions::values())->each(function (string $permission) {
            Permission::query()->firstOrCreate([
                'name' => $permission
            ]);
        });

        Role::query()->firstOrCreate([
            'name' => 'Super Admin',
        ]);

        $admin = Role::query()->firstOrCreate([
            'name' => 'Admin',
        ]);

        $mediator = Role::query()->firstOrCreate([
            'name' => 'Mediator',
        ]);

        $compiler = Role::query()->firstOrCreate([
            'name' => 'Compiler',
        ]);

        $dealer = Role::query()->firstOrCreate([
            'name' => 'Dealer',
        ]);

        $dealer->givePermissionTo([SystemPermissions::CreateSellOrder]);

        $compiler->givePermissionTo([SystemPermissions::AccessDashboard, SystemPermissions::ManageWithdrawals, SystemPermissions::ManageUSDTSale]);

        $mediator->givePermissionTo([SystemPermissions::AccessDashboard, SystemPermissions::ViewAllDisputes, SystemPermissions::ManageKYC, SystemPermissions::ManagePayoutProofs]);

        $admin->givePermissionTo(array_diff(SystemPermissions::values(), [SystemPermissions::CreateSellOrder->value]));

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}