<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class FileManagerPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'file_manager_access',
            'file_manager_upload',
            'file_manager_delete',
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}