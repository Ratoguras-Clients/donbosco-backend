<?php

namespace App\Helper;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class SidebarHelper
{
    protected $sidebarConfig;
    protected $currentRoute;

    public function __construct($sidebarConfig)
    {
        $this->sidebarConfig = $sidebarConfig;
        $this->currentRoute = Route::currentRouteName();
    }

    public function isActiveRoute($routePattern, $activeRoutes = [])
    {
        // Check specific active routes first
        if (!empty($activeRoutes) && in_array($this->currentRoute, $activeRoutes)) {
            return true;
        }

        // Check route pattern
        if (is_array($routePattern)) {
            return in_array($this->currentRoute, $routePattern);
        }

        if (str_contains($routePattern, '.')) {
            return str_contains($this->currentRoute, $routePattern);
        }

        return $this->currentRoute === $routePattern;
    }

    public function hasPermission($permission)
    {
        return $permission ? Auth::check() && Auth::user()->hasPermissionTo($permission) : true;
    }


    public function generateRoute($routeName, $params = [])
    {
        try {
            if (empty($params)) {
                return route($routeName);
            }
            return route($routeName, $params);
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function shouldShowUserInfo($section)
    {
        return isset($section['show_user_info']) && $section['show_user_info'] === true;
    }

    public function getItemColor($item, $defaultColor)
    {
        return $item['color'] ?? $defaultColor;
    }

    public function getItemMethod($item)
    {
        return $item['method'] ?? 'GET';
    }

    public function getCurrentRoleId()
    {
        try {
            $route = request()->route('role');
            return $route instanceof Role ? $route->id : $route;
        } catch (\Exception $e) {
            return null;
        }
    }
}
