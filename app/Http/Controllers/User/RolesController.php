<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Auth;    
use wadeshuler\FlashMessages\Facades\FlashMessages;
use App\Helpers\Helper;

class RolesController extends Controller
{
    protected $btnEdit, $btnDelete, $btnDetails;
    
    function __construct()
    {
        $this->middleware('autologout');
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
                '<nobr><a href="'.url('settings_roles/'.$role->id.'/edit').'">'.$this->btnEdit.'</a><a class="'.(!in_array('Superadmin', $user->getRoleNames()->toArray()) && $role->name == 'Superadmin' ? 'disabled' : '').'" href="'.url('settings_roles/'.$role->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('settings_roles/'.$role->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }
        
        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null],
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
        $permissions = $this->customModules();
        $systemPermissions = $this->systemModules();
        $config = [
            "placeholder" => "Select required permissions",
            "allowClear" => true,
        ];
        return view('roles.create',compact('systemPermissions','permissions','config'));
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
            'systemPermissions' => 'required',
            'permissions' => 'required',
        ]);
        $selectedPermissions = array_merge($request->input('systemPermissions'), $request->input('permissions'));
        $permissions = $this->getSelectedPermissionsId($selectedPermissions);

        $role = Role::create(['name' => $request->input('name')]);
        
        $role->syncPermissions($permissions);

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
        $permissions = $this->customModules();
        $systemPermissions = $this->systemModules();

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        $permittedPermissions = [];
        foreach($rolePermissions as $rolePermission){
            $permittedPermissions[] = Permission::findById($rolePermission)->name;
        }

        return view('roles.show',compact('role','permissions','systemPermissions','permittedPermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $role = Role::find($id);
        $permissions = $this->customModules();
        $systemPermissions = $this->systemModules();

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        $permittedPermissions = [];
        foreach($rolePermissions as $rolePermission){
            $permittedPermissions[] = Permission::findById($rolePermission)->name;
        }

        return view('roles.edit',compact('role','permissions','systemPermissions','permittedPermissions'));
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
            'systemPermissions' => 'required',
            'permissions' => 'required',
        ]);

        $selectedPermissions = array_merge($request->input('systemPermissions'), $request->input('permissions'));
        $permissions = $this->getSelectedPermissionsId($selectedPermissions);
    
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($permissions);   

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
    private function systemModules()
    {
        $systemModules = Helper::getSystemModules();

        $system = [];

        foreach($systemModules as $systemModule){
            $system['system'][] = $systemModule;
        }

        return $system;

    }
    private function customModules()
    {
        $allModules = Helper::getModulesFromRoute();
        $systemModules = Helper::getSystemModules();

        $structuredModules = [];

        foreach(array_diff($allModules, $systemModules) as $module){
            if(strpos($module,'.')){
                $explode = explode('.', $module);
                $structuredModules[$explode[0]][] = $explode[1];
            }else{
                $structuredModules[$module][] = $module;
            }
        }

        return $structuredModules;
    }

    private function getSelectedPermissionsId($permissions)
    {
        $permissionID = [];
        foreach($permissions as $permission){
            $explodePermission = explode('.', $permission);
            if(count($explodePermission) == 2){
                if($explodePermission[0] == $explodePermission[1]){
                    $permissionID[] = Permission::findByName($explodePermission[1]);
                }else{
                    $permissionID[] = Permission::findByName($permission);
                }
            }else{
                $permissionID[] = Permission::findByName($permission);
            }
        }
        return $permissionID;
    }
}
