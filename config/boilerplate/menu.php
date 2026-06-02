<?php

return [
    'sidebar' => [
        [
            'title' => 'Dashboard',
            'route' => 'boilerplate.dashboard',
            'icon' => 'fas fa-tachometer-alt',
        ],
        // Add File Manager Menu
        [
            'title' => 'File Manager',
            'route' => 'boilerplate.file-manager.index',
            'icon' => 'fas fa-folder-open',
            'permission' => 'file_manager_access',
        ],

    'dashboard' => \Sebastienheyd\Boilerplate\Controllers\DashboardController::class,
    'providers' => [], // Additional menu items providers
     ],
];
