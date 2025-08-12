<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use App\Models\Menu;

class AutoGenerateMenusSeeder extends Seeder
{
    public function run(): void
    {
        $routes = collect(Route::getRoutes())
            ->filter(fn($route) => $route->getName()) // only named routes
            ->map(function ($route) {
                return [
                    'name' => $route->getName(),
                    'uri' => $route->uri(),
                    'prefix' => $route->getAction('prefix') ?? '',
                ];
            });

        $grouped = $routes->groupBy(function ($route) {
            return explode('.', $route['name'])[0]; // e.g., 'users', 'admin'
        });

        foreach ($grouped as $group => $items) {
            // Create or get parent menu
            $parent = Menu::firstOrCreate(
                ['title' => ucfirst($group)],
                [
                    'route_name' => null,
                    'path' => null,
                    'parent_id' => null,
                    'status' => 'active',
                ]
            );

            foreach ($items as $item) {
                // Generate a clean title
                $title = ucwords(str_replace(['.', '_'], [' ', ' '], $item['name']));

                // Check if this child already exists
                Menu::firstOrCreate(
                    ['route_name' => $item['name']],
                    [
                        'title' => $title,
                        'path' => '/' . ltrim($item['uri'], '/'),
                        'parent_id' => $parent->id,
                        'status' => 'active',
                    ]
                );
            }
        }

        $this->command->info('✅ Menus auto-synced from routes successfully.');
    }
}

