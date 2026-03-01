<?php

namespace App\View\Components;

use App\Helper\Helper;
use App\Helper\SidebarHelper;
use Illuminate\View\Component;
use App\Models\Role;
use App\Models\Website\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Inventory\App\Models\InventorySupplier;

class DynamicSidebar extends Component
{
    public $sidebarConfig;
    public $currentRoute;
    public $mainMenus;
    public $bottomMenus;
    public $allRoles;
    public $currentRoleId;
    public $helper;
    public $suppliers;
    public $allPages;

    public function __construct()
    {
        $sidebarConfig = Helper::getSidebarConfig();



        $this->sidebarConfig = $sidebarConfig ?? [];
        $this->currentRoute = Route::currentRouteName();

        // Initialize helper
        $this->helper = new SidebarHelper($this->sidebarConfig);

        // Pre-process data for the view
        $this->mainMenus = $this->getMenusByPosition('main');
        $this->bottomMenus = $this->getMenusByPosition('bottom');
        $this->allRoles = $this->getAllRoles();
        $this->currentRoleId = $this->helper->getCurrentRoleId();
        // $this->suppliers = $this->getSuppliers();
        // $this->allPages = $this->getAllPages();
    }

    public function render()
    {
        return view('layouts.include.navigation', [
            'sidebarConfig' => $this->sidebarConfig,
            'currentRoute' => $this->currentRoute,
            'mainMenus' => $this->mainMenus,
            'bottomMenus' => $this->bottomMenus,
            'allRoles' => $this->allRoles,
            'currentRoleId' => $this->currentRoleId,
            'helper' => $this->helper,
            'suppliers' => $this->suppliers,
        ]);
    }

    public function getAllRoles()
    {
        try {
            return Role::all();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function getMenusByPosition($position = 'main')
    {
        return collect($this->sidebarConfig['menus'])->filter(function ($menu) use ($position) {
            if ($position === 'bottom') {
                return isset($menu['position']) && $menu['position'] === 'bottom';
            }
            return !isset($menu['position']) || $menu['position'] !== 'bottom';
        })->toArray();
    }

    // public function getSuppliers()
    // {
    //     try {
    //         return InventorySupplier::where('is_active', true)->get();
    //     } catch (\Exception $e) {
    //         return collect([]);
    //     }
    // }

    // public function getAllPages()
    // {
    //     try {
    //         return Page::where('post_status', 1)->whereIn('post_parent', [0])->whereNotNull('post_parent')->get();
    //     } catch (\Exception $e) {
    //         return collect([]);
    //     }
    // }
}
