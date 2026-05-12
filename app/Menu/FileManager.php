<?php

namespace App\Menu;

use Sebastienheyd\Boilerplate\Menu\Builder;
use Sebastienheyd\Boilerplate\Menu\MenuItemInterface;

class FileManager implements MenuItemInterface
{
    public function make(Builder $menu)
    {
        $menu->add('File Manager', [
            'route' => 'boilerplate.file-manager.index',
            'icon' => 'folder-open',
            'order' => 1050,
            'active' => 'boilerplate.file-manager.*',
        ]);
    }
}
