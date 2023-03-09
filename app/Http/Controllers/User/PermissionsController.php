<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Auth;
use App\Helpers\Helper;

class PermissionsController extends Controller
{
    protected $btnEdit, $btnDelete, $btnDetails;
    
    function __construct()
    {
        $this->middleware('autologout');
        //$this->btnEdit = config('adminlte.btn-edit');
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
        $permissions = Permission::all();
        $heads = [
            'Name',
            'Module',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($permissions as $permission){
            $data[] = array(
                $permission->name,
                explode('.',$permission->name)[0],
                '<nobr><a href="'.url('settings_permissions/'.$permission->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }
        
        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null],
        ];

        return view('permissions.list', [
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
        $modules = Helper::getModulesFromRoute();
        $config = [
            "placeholder" => "Select module",
            "allowClear" => true,
        ];
        return view('permissions.create',compact('modules','config'));
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
            'modules' => 'required',
        ]);

        if(!Permission::where(['name' => $request->input('modules')])->exists()){
            $store = Permission::create(['name' => $request->input('modules')]);

            if($store){
                flash(
                    'Permission created successfully',
                )->success();
            }else{
                flash(
                    'Unable to create permission',
                )->error();
            }
        }else{
            flash(
                'Permission "'.$request->input('modules').'" exists',
            )->warning();
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
        $permission = Permission::where('id', $id)->firstOrFail();

        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $modules = Helper::getModulesFromRoute();
        $permission = Permission::where('id', $id)->firstOrFail();
        $config = [
            "placeholder" => "Select module",
            "allowClear" => true,
        ];

        return view('permissions.edit', compact('permission','config','modules'));
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
            'modules' => 'required',
        ]);

        $update = Permission::where('id', $id)->update($request->input('modules'));
        if($update){
            flash(
                'Permission updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update permission',
            )->error();
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        flash(
            'Permission delete successfully',
        )->success();
        return back();

    }
}
