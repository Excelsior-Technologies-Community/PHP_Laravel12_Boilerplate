<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class UserExportController extends Controller
{
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        $users = User::with('roles')
            ->select('id', 'name', 'email', 'created_at', 'updated_at')
            ->get();
        
        if ($format === 'csv') {
            return $this->exportToCsv($users);
        } elseif ($format === 'excel') {
            return $this->exportToExcel($users);
        } elseif ($format === 'json') {
            return $this->exportToJson($users);
        }
        
        return redirect()->back()->with('growl', [
            'type' => 'error',
            'message' => 'Invalid export format!',
            'title' => 'Error'
        ]);
    }
    
    private function exportToCsv($users)
    {
        $filename = 'users_export_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Roles', 'Created At', 'Updated At']);
            
            // Add data rows
            foreach ($users as $user) {
                $roles = $user->roles->pluck('name')->implode(', ');
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $roles,
                    $user->created_at,
                    $user->updated_at
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    private function exportToExcel($users)
    {
        // Create a simple HTML table export as Excel
        $filename = 'users_export_' . date('Y-m-d_His') . '.xls';
        
        $html = '<html><head>';
        $html .= '<meta charset="UTF-8">';
        $html .= '<title>Users Export</title>';
        $html .= '<style>
            th { background-color: #4CAF50; color: white; padding: 8px; }
            td { padding: 8px; border: 1px solid #ddd; }
            table { border-collapse: collapse; width: 100%; }
        </style>';
        $html .= '</head><body>';
        $html .= '<h2>Users List - ' . date('Y-m-d H:i:s') . '</h2>';
        $html .= '<table border="1">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>ID</th><th>Name</th><th>Email</th><th>Roles</th><th>Created At</th><th>Updated At</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->implode(', ');
            $html .= '<tr>';
            $html .= '<td>' . $user->id . '</td>';
            $html .= '<td>' . htmlspecialchars($user->name) . '</td>';
            $html .= '<td>' . htmlspecialchars($user->email) . '</td>';
            $html .= '<td>' . htmlspecialchars($roles) . '</td>';
            $html .= '<td>' . $user->created_at . '</td>';
            $html .= '<td>' . $user->updated_at . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '<p>Total Users: ' . $users->count() . '</p>';
        $html .= '</body></html>';
        
        return response($html, 200)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"")
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Expires', '0');
    }
    
    private function exportToJson($users)
    {
        $filename = 'users_export_' . date('Y-m-d_His') . '.json';
        
        $data = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ];
        });
        
        return response()->json($data, 200, [
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
    }
}