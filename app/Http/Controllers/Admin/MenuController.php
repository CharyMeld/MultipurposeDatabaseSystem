<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Route;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('children')->whereNull('parent_id')->orderBy('order')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $menus = Menu::all();
        return view('admin.menus.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'route_name' => 'nullable|string',
            'parent_id' => 'nullable|integer|exists:menus,id',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();

        // Auto-generate URL from route_name if url not provided
        if (empty($data['url']) && !empty($data['route_name'])) {
            if (Route::has($data['route_name'])) {
                $data['url'] = route($data['route_name']);
            } else {
                return back()->with('error', 'The provided route name does not exist.');
            }
        }

        Menu::create([
            'title'      => $data['title'],
            'route_name' => $data['route_name'],
            'url'        => $data['url'],
            'parent_id'  => $data['parent_id'],
            'order'      => $data['order'] ?? 0,
        ]);

        return redirect()->back()->with('success', 'Menu created successfully.');
    }

    public function show(Menu $menu)
    {
        return view('admin.menus.show', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        $menus = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->get();
        return view('admin.menus.edit', compact('menu', 'menus'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'route_name' => 'nullable|string',
            'parent_id' => 'nullable|integer|exists:menus,id',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();

        // Auto-generate URL from route_name if url not provided
        if (empty($data['url']) && !empty($data['route_name'])) {
            if (Route::has($data['route_name'])) {
                $data['url'] = route($data['route_name']);
            } else {
                return back()->with('error', 'The provided route name does not exist.');
            }
        }

        $menu->update([
            'title'      => $data['title'],
            'route_name' => $data['route_name'],
            'url'        => $data['url'],
            'parent_id'  => $data['parent_id'],
            'order'      => $data['order'] ?? 0,
        ]);

        return redirect()->back()->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }

     public function syncRoutes()
    {
        $routeMap = [ /* Paste the map above here */ ];

        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            $name = $route->getName();

            if (!$name || !isset($routeMap[$name])) {
                continue;
            }

            $parentSlug = $routeMap[$name];
            $parentMenu = Menu::where('slug', $parentSlug)->orWhereRaw('LOWER(REPLACE(title, " ", "_")) = ?', [$parentSlug])->first();

            if (!$parentMenu) {
                continue;
            }

            // Check if menu exists already
            $existing = Menu::where('route_name', $name)->first();
            if ($existing) {
                $existing->update([
                    'parent_id' => $parentMenu->id,
                    'status' => 'active',
                ]);
            } else {
                Menu::create([
                    'title' => ucfirst(str_replace('.', ' ', $name)),
                    'route_name' => $name,
                    'parent_id' => $parentMenu->id,
                    'status' => 'active',
                    'order' => 0,
                ]);
            }
        }

        return back()->with('success', 'Menu routes synced successfully!');
    }

}

