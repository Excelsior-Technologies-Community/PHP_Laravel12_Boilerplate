<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class UserExportController extends Controller
{
    /**
     * Export all users to Excel
     */
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}