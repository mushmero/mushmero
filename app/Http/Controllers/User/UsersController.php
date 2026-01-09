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

        foreach ($users as $user) {
            $data[] = array(
                $user->name,
                $user->email,
                Carbon::parse($user->created_at)->format('d F Y'),
                '<nobr><a href="' . url('settings_users/' . $user->id . '/restore') . '" class="' . (!$user->trashed() ? 'disabled' : '') . '">' . $this->btnRestore . '</a><a class="' . ($user->trashed() ? 'disabled' : '') . '" href="' . url('settings_users/' . $user->id . '/edit') . '">' . $this->btnEdit . '</a><a class="" href="' . url('settings_users/' . $user->id . '/delete') . '">' . $this->btnDelete . '</a><a class="" href="' . url('settings_users/' . $user->id . '/show') . '">' . $this->btnDetails . '</a></nobr>',
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
            // 'roles' => 'required',
        ]);

        $data = $request->all();

        $store = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // if (is_array($data['roles'])) {
        //     foreach ($data['roles'] as $role) {
        //         $store->assignRole($role);
        //     }
        // }

        if ($store) {
            flash(
                'User created successfully',
            )->success();
        } else {
            flash(
                'Unable to create user',
            )->error();
        }

        return back();
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'same:password_confirmation',
            // 'roles' => 'required',
        ]);

        $data['name'] = $request->name;

        if (!empty($request->email)) {
            $user = User::find($id)->first();
            if ($user->email != $request->email) {
                $data['email'] = $request->email;
            }
        }

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user = User::find($id)->update($data);

        // DB::table('model_has_roles')->where('model_id', $id)->delete();

        // $data['roles'] = $request->roles;

        // if (is_array($data['roles'])) {
        //     foreach ($data['roles'] as $role) {
        //         User::find($id)->assignRole($role);
        //     }
        // }

        if ($user) {
            flash(
                'User updated successfully',
            )->success();
        } else {
            flash(
                'Unable to update user',
            )->error();
        }

        return back();
    }

    public function destroy($id)
    {
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
