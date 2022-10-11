<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Auth;    

class RolesController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:all-list|all-create|all-edit|all-delete', ['only' => ['index','store']]);
         $this->middleware('permission:all-create', ['only' => ['create','store']]);
         $this->middleware('permission:all-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:all-delete', ['only' => ['destroy']]);
         $this->btnEdit = config('adminlte.btn-edit');
         $this->btnDelete = config('adminlte.btn-delete');
         $this->btnDetails = config('adminlte.btn-view');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $user = Auth::user();
        $roles = Role::all();
        $heads = [
            'Name',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($roles as $role){
            $data[] = array(
                $role->name,
                '<nobr><a href="'.url('roles/'.$role->id.'/edit').'">'.$this->btnEdit.'</a><a class="'.(in_array('Superadmin', $user->getRoleNames()->toArray()) ? 'disabled' : '').'" href="'.url('roles/'.$role->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('roles/'.$role->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }
        
        $config = [
            'data' => $data,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, ['orderable' => false]],
        ];

        return view('roles.list', [
            'heads' => $heads,
            'config' => $config,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();
        $config = [
            "placeholder" => "Select required permissions",
            "allowClear" => true,
        ];
        return view('roles.create',compact('permissions','config'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        
        $role->syncPermissions($request->input('permission'));

        if($role){
            FlashMessages::addFlashMessage(
                'success',
                'Role created successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to create role',
            );
        }

         return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
        ->where("role_has_permissions.role_id",$id)->get()->pluck('name')->all();

        return view('roles.show',compact('role','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all(); 
        $config = [
            "placeholder" => "Select required permissions",
            "allowClear" => true,
        ];

        return view('roles.edit',compact('role','permissions','rolePermissions', 'config'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required',
        ]);
    
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));   

        if($role){
            FlashMessages::addFlashMessage(
                'success',
                'Role updated successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to update role',
            );
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        FlashMessages::addFlashMessage(
            'success',
            'Role deleted successfully',
        );
        return redirect()->route('roles');
    }
}
