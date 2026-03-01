<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('can:role-management-view', ['only' => ['index', 'show']]);
        $this->middleware('can:role-management-create', ['only' => ['create', 'store']]);
        $this->middleware('can:role-management-update', ['only' => ['edit', 'update']]);
        $this->middleware('can:role-management-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        // dd(Auth::user()->getAllPermissions());
        $currentLevel = Auth::user()->roles->first()->level;
        $roles = Role::where("level", ">=", $currentLevel)->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'level' => 'required|integer|min:2',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::create([
            'name' => $request->name,
            'level' => $request->level,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Role created successfully!', 'role' => $role]);
        }

        // Check if the user wants to stay on the page
        if ($request->has('stay_on_page')) {
            return redirect()->route('roles.create')
                ->with('success', 'Role created successfully!');
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully!');
    }

    /**
     * Display the specified role.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $currentLevel = Auth::user()->roles->first()->level;

        if ($currentLevel > $role->first()->level) {
            abort(403, 'Unauthorized action.');
        }
        $role->load(['users']);
        $permissionsGroup = $role->getAllPermissions()->groupBy('group');
        return view('roles.show', compact('role', 'permissionsGroup'));
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        // Check if this is a system role
        if (in_array($role->name, ['Admin', 'Super Admin'])) {
            // You might want to redirect with a warning instead
            // For now, we'll just show the edit page with a warning
        }

        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $currentLevel = Auth::user()->roles->first()->level;

        if ($currentLevel > $role->first()->level) {
            abort(403, 'Unauthorized action.');
        }
        // Prevent updating system roles
        if (in_array($role->name, ['Admin', 'Super Admin'])) {
            return redirect()->back()
                ->with('error', 'System roles cannot be modified.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role->update([
            'name' => $request->name,
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $currentLevel = Auth::user()->roles->first()->level;

        if ($currentLevel > $role->first()->level) {
            abort(403, 'Unauthorized action.');
        }
        // Prevent deleting system roles
        if (in_array($role->name, ['Admin', 'Super Admin'])) {
            return redirect()->back()
                ->with('error', 'System roles cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully!');
    }

    /**
     * Show the form for managing role permissions.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function showPermissions(Role $role)
    {
        $currentLevel = Auth::user()->roles->first()->level;

        if ($currentLevel > $role->first()->level) {
            abort(403, 'Unauthorized action.');
        }
        // Prevent managing permissions for system roles
        if (in_array($role->name, ['Admin', 'Super Admin'])) {
            return redirect()->route('roles.show', $role)
                ->with('error', 'System roles have predefined permissions that cannot be modified.');
        }

        // Get all permissions grouped by module
        $permissionGroups = Permission::all()->groupBy('group');

        // Get the role's current permissions
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        if (request()->has('getPermissionDetail')) {
            $permission = Permission::where('group', request()->groupname)->get();
            return response()->json([
                'success' => true,
                'permission' => $permission,
            ]);
        }

        return view('roles.permissions', compact('role', 'permissionGroups', 'rolePermissions'));
    }

    /**
     * Update the role's permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $currentLevel = Auth::user()->roles->first()->level;

        if ($currentLevel > $role->first()->level) {
            abort(403, 'Unauthorized action.');
        }

        // Get the submitted permissions (empty array if none selected)
        $newPermissions = $request->input('permissions', []);

        // Check if this is a partial update (from modal for specific group)
        $permissionGroup = $request->input('permission_group', null);

        if ($permissionGroup) {
            // Partial update: Only update permissions for the specific group
            // Get all permissions in this group
            $groupPermissionIds = Permission::where('group', $permissionGroup)->pluck('id')->toArray();

            // Get current role permissions for this group
            $currentGroupPermissions = $role->permissions()
                ->whereIn('id', $groupPermissionIds)
                ->pluck('id')
                ->toArray();

            // Find permissions to attach (new ones in this group that aren't already assigned)
            $permissionsToAttach = array_diff($newPermissions, $currentGroupPermissions);

            // Find permissions to detach (existing ones in this group that weren't submitted)
            $permissionsToDetach = array_diff($currentGroupPermissions, $newPermissions);
        } else {
            // Full update: Update all permissions (from main form)
            // Get existing permissions
            $existingPermissions = $role->permissions->pluck('id')->toArray();

            // Find permissions to attach (new ones that aren't already assigned)
            $permissionsToAttach = array_diff($newPermissions, $existingPermissions);

            // Find permissions to detach (existing ones that weren't submitted)
            $permissionsToDetach = array_diff($existingPermissions, $newPermissions);
        }

        // Attach new permissions
        if (!empty($permissionsToAttach)) {
            $role->permissions()->attach($permissionsToAttach);
        }

        // Detach unchecked permissions
        if (!empty($permissionsToDetach)) {
            $role->permissions()->detach($permissionsToDetach);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        if ($request->has('ajax') && $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Role permissions updated successfully!']);
        } else {
            return redirect()->route('roles.show', $role)
                ->with('success', 'Role permissions updated successfully!');
        }
    }
    public function getRole(Request $request)
    {
        $inputlevel = $request->inputLevel;
        $role = Role::where('level', '>=', $inputlevel)->get();

        return response()->json([
            'success' => true,
            'role' => $role,
        ]);
    }
}
