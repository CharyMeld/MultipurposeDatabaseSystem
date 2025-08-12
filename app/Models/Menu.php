<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'route_name',
        'url',
        'parent_id',
        'order'
    ];

    // Auto-generate URL from route_name on create/update
    protected static function booted()
    {
        static::creating(function ($menu) {
            $menu->url = static::generateUrl($menu->route_name);
        });

        static::updating(function ($menu) {
            $menu->url = static::generateUrl($menu->route_name);
        });
    }

    /**
     * Generate a relative URL path from a route name, only if it has no required parameters.
     */
    protected static function generateUrl($routeName)
    {
        if ($routeName && Route::has($routeName)) {
            $route = Route::getRoutes()->getByName($routeName);

            // Only generate the URL if no required parameters
            if ($route && empty($route->parameterNames())) {
                return route($routeName, [], false);
            }
        }

        return '#'; // fallback URL
    }

    /**
     * Relationship: Menu has many children
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    /**
     * Relationship: Menu belongs to a parent menu
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Accessor: Get the full navigable URL (absolute)
     */
    public function getFullUrlAttribute()
    {
        if ($this->route_name && Route::has($this->route_name)) {
            $route = Route::getRoutes()->getByName($this->route_name);

            if ($route && empty($route->parameterNames())) {
                return route($this->route_name);
            }
        }

        if (!empty($this->url)) {
            return url($this->url);
        }

        return '#';
    }

    /**
     * Accessor: Get the relative path (used in sidebars)
     */
    public function getPathAttribute()
    {
        if ($this->route_name && Route::has($this->route_name)) {
            $route = Route::getRoutes()->getByName($this->route_name);

            if ($route && empty($route->parameterNames())) {
                return route($this->route_name, [], false);
            }
        }

        return $this->url ?? '#';
    }
}

