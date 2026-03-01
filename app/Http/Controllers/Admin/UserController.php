<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\LoginActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */

    public function __construct()
    {
        $this->middleware('can:user-management-view', ['only' => ['index', 'show']]);
        $this->middleware('can:user-management-create', ['only' => ['create', 'store']]);
        $this->middleware('can:user-management-update', ['only' => ['edit', 'update']]);
        $this->middleware('can:user-management-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $authUser = User::find(Auth::id());
        $users = User::where(function ($query) use ($authUser) {
            if (!$authUser->hasRole('super-admin')) {
                $query->where('created_by', $authUser->id)->orWhere('updated_by', $authUser->id);
            }
        })->whereNot('id', $authUser->id)->with(['roles'])->paginate(12);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $userRoles = auth()->user()->roles; // Collection of Role models
        $userLevel = $userRoles->min('level'); // Pick the smallest level (1 = highest)
        $roles = Role::where('level', '>', $userLevel)->orderBy('level')->get(); // Only lower roles
        return view('admin.users.create', compact('roles'));
    }


    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            // 'password' => ['required', Password::defaults()],
            'phone' => 'nullable|string|max:20|unique:users,phone',
            // 'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('password123'), // Default password; consider sending reset link
            'phone' => $validated['phone'],
            'created_by' => auth()->user()->id,
        ]);

        $roleNames = 'Customer'; // Role::whereIn('id', $validated['roles'])->pluck('name')->toArray();
        $user->syncRoles($roleNames);

        //        $token = Str::random(60);
        //        LoginToken::create([
        //            'user_id' => $user->id,
        //            'token' => $token,
        //            'expires_at' => now()->addMinutes(30),
        //        ]);
        //        Mail::to($user->email)->queue(new UserStatusMail($user));
        // Mail::to(config('app.email'))->queue(new UserStatusMail($user));

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function loginWithToken(Request $request, $token)
    {
        //        $loginToken = LoginToken::where('token', $token)
        //            ->where('expires_at', '>', now())
        //            ->first();
        //
        //        if (!$loginToken) {
        //            return redirect('/')->with('error', 'Invalid or expired login token.');
        //        }

        // $user = $loginToken->user;
        //        $user = User::find($loginToken->user_id);


        // Auth::logout();
        //        Auth::login($user);
        // $loginToken->delete(); // Delete the token after successful login
        //        return redirect()->route('profile.edit', $user->id)->with('success', 'Logged in successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['roles']);
        $permissions = $user->getAllPermissions();
        $loginSessions = LoginActivity::where('user_id', $user->id)
            ->orderByDesc('logged_in_at')
            ->get()
            ->map(function ($session) use ($user) {
                $start = $session->logged_in_at;
                $end = $session->logged_out_at ?? now();

                $activities = ActivityLog::where('user_id', $user->id)
                    ->whereBetween('created_at', [$start, $end])
                    ->orderBy('created_at')
                    ->get();

                $session->activities = $activities;
                return $session;
            });
        // $performanceReviews = $user->performanceReviews()->with('getCreated')->orderByDesc('review_date')->get();

        return view('admin.users.show', compact('user', 'permissions', 'loginSessions'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', Password::defaults()],
            ]);

            $user->password = Hash::make($request->password);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->updated_by = auth()->user()->id;
        $user->is_active = $request->status;
        $user->save();

        // Sync roles using Spatie's method
        $roleNames = Role::whereIn('id', $validated['roles'])->pluck('name')->toArray();
        $user->syncRoles($roleNames);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
        ]);

        $user = User::find($request->id);
        $user->is_active  = !$user->is_active;
        $user->updated_by = auth()->user()->id;

        if ($user->save()) {
            return redirect()->route('users.show', ['user' => $request->id])
                ->with('success', 'User status changed successfully.');
        } else {
            return redirect()->route('users.show', ['user' => $request->id])
                ->with('failure', 'Something went wrong.');
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Spatie automatically handles role detachment
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function all()
    {
        if (request()->has('dataTable')) {
            $start = 0;
            $length = 10;

            if (request()->has('start')) {
                if (request('start') >= 0) {
                    $start = intval(request('start'));
                }
            }

            if (request()->has('length')) {
                if (request('length') >= 5 && request('length') <= 100) {
                    $length = intval(request('length'));
                }
            }
            $authUser = auth()->user();

            $staff_lists = User::whereDoesntHave('roles.permissions', function ($query) {
                $query->where('name', 'role-management-staff');
            })
                ->where(function ($query) use ($authUser) {
                    $query->where('created_by', $authUser->id)
                        ->orWhere('updated_by', $authUser->id);
                    if (!is_null($authUser->ministry_id)) {
                        $query->orWhere('ministry_id', $authUser->ministry_id);
                    }
                })->whereNot('id', $authUser->id)->with(['roles', 'ministry', 'description']);

            $counts = $staff_lists->count();
            $filtered_count = $staff_lists->count();
            $staff_lists->offset($start)->limit($length);

            return response()->json([
                "data" => $staff_lists->get()->map(function ($u, $i) use ($start) {
                    return [
                        "sn" => $start + $i + 1,
                        "id" => $u->id,
                        "name" => $u->name,
                        "email" => $u->email,
                        "profile" => $u->description->profile ?? false,
                        "roles" => $u->roles->map(function ($u) {
                            return $u->name;
                        }),
                    ];
                }),
                "recordsFiltered" => $filtered_count,
                "recordsTotal" => $counts
            ]);
        }
        return view('admin.users.all.index');
    }

    /**
     * Show the form for managing user permissions.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function showPermissions(User $user)
    {
        // Get all permissions grouped by module
        $permissionGroups = Permission::all()->groupBy('group');

        // Get the user's current permissions (both direct and from roles)
        $userPermissions = $user->getAllPermissions()->pluck('id')->toArray();

        if (request()->has('getPermissionDetail')) {
            $permission = Permission::where('group', request()->groupname)->get();
            return response()->json([
                'success' => true,
                'permission' => $permission,
            ]);
        }

        return view('users.permissions', compact('user', 'permissionGroups', 'userPermissions'));
    }

    /**
     * Update the user's permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updatePermissions(Request $request, User $user)
    {
        // Get the submitted permissions (empty array if none selected)
        $newPermissions = $request->input('permissions', []);

        // Check if this is a partial update (from modal for specific group)
        $permissionGroup = $request->input('permission_group', null);

        if ($permissionGroup) {
            // Partial update: Only update permissions for the specific group
            // Get all permissions in this group
            $groupPermissionIds = Permission::where('group', $permissionGroup)->pluck('id')->toArray();

            // Get current user direct permissions for this group
            $currentGroupPermissions = $user->permissions()
                ->whereIn('id', $groupPermissionIds)
                ->pluck('id')
                ->toArray();

            // Find permissions to attach (new ones in this group that aren't already assigned)
            $permissionsToAttach = array_diff($newPermissions, $currentGroupPermissions);

            // Find permissions to detach (existing ones in this group that weren't submitted)
            $permissionsToDetach = array_diff($currentGroupPermissions, $newPermissions);
        } else {
            // Full update: Update all direct permissions (from main form)
            // Get existing direct permissions
            $existingPermissions = $user->permissions->pluck('id')->toArray();

            // Find permissions to attach (new ones that aren't already assigned)
            $permissionsToAttach = array_diff($newPermissions, $existingPermissions);

            // Find permissions to detach (existing ones that weren't submitted)
            $permissionsToDetach = array_diff($existingPermissions, $newPermissions);
        }

        // Attach new permissions
        if (!empty($permissionsToAttach)) {
            foreach ($permissionsToAttach as $permissionId) {
                $permission = Permission::find($permissionId);
                if ($permission) {
                    $user->givePermissionTo($permission);
                }
            }
        }

        // Detach unchecked permissions
        if (!empty($permissionsToDetach)) {
            foreach ($permissionsToDetach as $permissionId) {
                $permission = Permission::find($permissionId);
                if ($permission) {
                    $user->revokePermissionTo($permission);
                }
            }
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        if ($request->has('ajax') && $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'User permissions updated successfully!']);
        } else {
            return redirect()->route('users.show', $user)
                ->with('success', 'User permissions updated successfully!');
        }
    }
}
