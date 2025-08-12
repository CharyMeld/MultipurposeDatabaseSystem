<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\Menu;


class MenuSeeder extends Seeder
{
    public function run()
    {
        // 1. Clear the menus table
        DB::table('menus')->truncate();

        // 2. Define parent menus
        $parentMenus = [
            'home' => '🏠 Home',
            'user_management' => '👤 User Management',
            'candidates' => '👥 Candidates',
            'admin_office' => '🏢 Admin Office',
            'office_documents' => '📄 Office Documents',
            'exam_office' => '🧑‍🏫 Exam Office',
            'agsm_resources' => '📘 AGSM Resources',
            'finance' => '💰 Finance',
            'head_of_admin' => '📋 Head of Admin',
            'officials' => '🧑 Officials',
            'settings' => '⚙️ Settings',
        ];

        $parentMenuIds = [];

        $order = 1;
        foreach ($parentMenus as $key => $title) {
            $menu = Menu::create([
                'title' => $title,
                'parent_id' => null,
                'order' => $order++,
                'status' => 'active',
            ]);
            $parentMenuIds[$key] = $menu->id;
        }

        // 3. Define child routes by matching them to a parent
        $routeMap = [
            // ⚙️ Settings
            'menus.index' => 'settings',
            'menus.create' => 'settings',
            'menus.store' => 'settings',
            'menus.edit' => 'settings',
            'menus.update' => 'settings',
            'menus.destroy' => 'settings',
            'menus.order' => 'settings',
            'menus.sync' => 'settings',

            // 👤 User Management
            'users.index' => 'user_management',
            'users.create' => 'user_management',
            'users.store' => 'user_management',
            'users.edit' => 'user_management',
            'users.update' => 'user_management',
            'users.destroy' => 'user_management',

            'roles.index' => 'user_management',
            'roles.create' => 'user_management',
            'roles.store' => 'user_management',
            'roles.edit' => 'user_management',
            'roles.update' => 'user_management',
            'roles.destroy' => 'user_management',

            'permissions.index' => 'user_management',
            'permissions.create' => 'user_management',
            'permissions.store' => 'user_management',
            'permissions.edit' => 'user_management',
            'permissions.update' => 'user_management',
            'permissions.destroy' => 'user_management',

            // 👥 Candidates
            'candidates.index' => 'candidates',
            'candidates.create' => 'candidates',
            'candidates.store' => 'candidates',
            'candidates.show' => 'candidates',
            'candidates.edit' => 'candidates',
            'candidates.update' => 'candidates',
            'candidates.destroy' => 'candidates',
            'candidates.bulk.upload' => 'candidates',
            'candidates.bulk.preview.save' => 'candidates',
            'candidates.passport.upload' => 'candidates',
            'candidates.filter' => 'candidates',

            // AJAX Candidate Profile Routes
            'candidates.profile.load.passport' => 'candidates',
            'candidates.profile.load.medical' => 'candidates',
            'candidates.profile.load.institution' => 'candidates',
            'candidates.profile.load.postgrad' => 'candidates',
            'candidates.profile.save.medical' => 'candidates',
            'candidates.profile.update.medical' => 'candidates',
            'candidates.profile.delete.medical' => 'candidates',
            'candidates.profile.save.institution' => 'candidates',
            'candidates.profile.update.institution' => 'candidates',
            'candidates.profile.delete.institution' => 'candidates',
            'candidates.profile.save.postgrad' => 'candidates',
            'candidates.profile.update.postgrad' => 'candidates',
            'candidates.profile.delete.postgrad' => 'candidates',
            'candidates.profile.save.postgrad.training' => 'candidates',


            // =============================
            // 🏢 ADMIN OFFICE (You can add matching routes here)
            // =============================

            // =============================
            // 📘 AGSM RESOURCES (You can add matching routes here)
            // ============================

            // 🏫 Faculty
            'faculty.index' => 'admin_office',

            // 🏠 Home
            'dashboard' => 'home',
            'welcome' => 'home',
        ];


        $childOrder = [];

        foreach ($routeMap as $routeName => $parentKey) {
            if (!isset($parentMenuIds[$parentKey])) {
                continue; // Skip if parent not found
            }

            $childOrder[$parentKey] = ($childOrder[$parentKey] ?? 0) + 1;

            Menu::create([
                'title' => ucwords(str_replace(['.', '_'], ' ', $routeName)),
                'route_name' => $routeName,
                'parent_id' => $parentMenuIds[$parentKey],
                'order' => $childOrder[$parentKey],
                'status' => 'active',
            ]);
        }
    }
}

