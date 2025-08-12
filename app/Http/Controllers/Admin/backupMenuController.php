<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class MenuController extends Controller
{
    public static function getMenuTree()
    {
        return Menu::whereNull('parent_id')
            ->where('status', 'active')
            ->orderBy('order')
            ->with('children')
            ->get();
    }
}

