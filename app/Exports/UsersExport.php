<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::all()->map(function ($user) {

            //  Get roles 
            $roles = DB::table('role_user')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('role_user.user_id', $user->id)
                ->pluck('roles.name') //  use name instead of display_name
                ->map(function ($role) {
                    return ucfirst($role); //  Admin instead of admin
                })
                ->join(', ');

            return [
                'Status' => $user->active ? 'Active' : 'Inactive',
                'First Name' => $user->first_name,
                'Last Name' => $user->last_name,
                'Email' => $user->email,
                'Role' => $roles ?: 'N/A',
                'Created At' => $user->created_at->format('Y-m-d H:i:s'),
                'Last Log' => $user->updated_at 
                    ? $user->updated_at->format('Y-m-d H:i:s') 
                    : 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Status',
            'First Name',
            'Last Name',
            'Email',
            'Role',
            'Created At',
            'Last Log'
        ];
    }
}