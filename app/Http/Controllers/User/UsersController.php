<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\UsersTrait;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use DB;

class UsersController extends Controller
{
    protected $btnEdit, $btnDelete, $btnDetails, $btnRestore;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->btnEdit = config('adminlte.btn-edit');
        $this->btnDelete = config('adminlte.btn-delete');
        $this->btnDetails = config('adminlte.btn-view');
        $this->btnRestore = config('adminlte.btn-restore');
    }

    public function index()
    {
        $data = array();
        $users = User::get();
        $heads = [
            'Name',
            'Email',
            'Member Since',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        $isDemo = config('app.is_demo') || auth()->user()?->email === 'demo@resumed.com';

        foreach ($users as $user) {
            $isProtectedDemoAccount = $isDemo && $user->email === 'demo@resumed.com';
            $deleteAction = $isProtectedDemoAccount
                ? '<a class="disabled" href="#" title="Primary demo account cannot be deleted">' . $this->btnDelete . '</a>'
                : '<a class="" href="' . url('settings_users/' . $user->id . '/delete') . '">' . $this->btnDelete . '</a>';

            $data[] = array(
                $user->name,
                $user->email,
                Carbon::parse($user->created_at)->format('d F Y'),
                '<nobr><a href="' . url('settings_users/' . $user->id . '/restore') . '" class="' . (!$user->trashed() ? 'disabled' : '') . '">' . $this->btnRestore . '</a><a class="' . ($user->trashed() ? 'disabled' : '') . '" href="' . url('settings_users/' . $user->id . '/edit') . '">' . $this->btnEdit . '</a>' . $deleteAction . '<a class="" href="' . url('settings_users/' . $user->id . '/show') . '">' . $this->btnDetails . '</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        return view('users.list', [
            'heads' => $heads,
            'config' => $config,
        ]);
    }

    public function create()
    {
        // $roles = Role::pluck('name', 'name')->all();
        $config = [
            "placeholder" => "Select required roles",
            "allowClear" => true,
        ];
        return view('users.create', compact('config'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
        ]);

        $store = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($store) {
            flash('User created successfully')->success();
        } else {
            flash('Unable to create user')->error();
        }

        return redirect()->route('users');
    }

    public function show($id)
    {
        $user = User::where('id', $id)->firstOrFail();

        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $config = [
            "placeholder" => "Select required roles",
            "allowClear" => true,
        ];
        $user = User::where('id', $id)->firstOrFail();
        // $roles = Role::pluck('name', 'name')->all();
        // $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'config'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6|same:password_confirmation',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $updated = $user->update($data);

        if ($updated) {
            flash('User updated successfully')->success();
        } else {
            flash('Unable to update user')->error();
        }

        return redirect()->route('users');
    }

    public function destroy($id)
    {
        $targetUser = User::withTrashed()->findOrFail($id);

        if ((config('app.is_demo') || auth()->user()?->email === 'demo@resumed.com') && $targetUser->email === 'demo@resumed.com') {
            flash('The primary demo account cannot be deleted.')->warning();
            return redirect()->route('users');
        }

        UsersTrait::delete($id);
        flash(
            'User deleted successfully',
        )->success();
        return redirect()->route('users');
    }

    public function restore($id)
    {
        UsersTrait::restore($id);
        flash(
            'User restored successfully',
        )->success();
        return redirect()->route('users');
    }
}
