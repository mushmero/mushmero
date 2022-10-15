<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Auth;
use Helper;
use wadeshuler\FlashMessages\Facades\FlashMessages;

class PermissionsController extends Controller
{
    protected $btnEdit, $btnDelete, $btnDetails;
    
    function __construct()
    {
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
        $permissions = Permission::all();
        $heads = [
            'Name',
            'Module',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($permissions as $permission){
            $data[] = array(
                $permission->name,
                explode('-',$permission->name)[0],
                '<nobr><a class="'.(explode('-',$permission->name)[0] == 'all' ? 'disabled' : '').'" href="'.url('settings_permissions/'.$permission->id.'/edit').'">'.$this->btnEdit.'</a><a class="'.(explode('-',$permission->name)[0] == 'all' ? 'disabled' : '').'" href="'.url('settings_permissions/'.$permission->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('settings_permissions/'.$permission->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }
        
        $config = [
            'data' => $data,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, ['orderable' => false]],
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
        $modules = Helper::getModulesFromMenu();
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

        $input = $request->all();
        if(isset($input['create'])){
            $permissions[] = $input['modules'].'-create';
        }
        if(isset($input['edit'])){
            $permissions[] = $input['modules'].'-edit';
        }
        if(isset($input['index'])){
            $permissions[] = $input['modules'].'-index';
        }
        if(isset($input['delete'])){
            $permissions[] = $input['modules'].'-delete';
        }

        foreach($permissions as $permission){
            if(!Permission::where(['name' => $permission])->exists()){
                $store = Permission::create(['name' => $permission]);

                if($store){
                    FlashMessages::addFlashMessage(
                        'success',
                        'Permission created successfully',
                    );
                }else{
                    FlashMessages::addFlashMessage(
                        'error',
                        'Unable to create permission',
                    );
                }
            }else{
                FlashMessages::addFlashMessage(
                    'error',
                    'Permission "'.$permission.'" exists',
                );
            }
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
        $modules = Helper::getModulesFromMenu();
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
        
        $input = $request->all();
        if(isset($input['create'])){
            $permissions['name'] = $input['modules'].'-create';
        }
        if(isset($input['edit'])){
            $permissions['name'] = $input['modules'].'-edit';
        }
        if(isset($input['index'])){
            $permissions['name'] = $input['modules'].'-index';
        }
        if(isset($input['delete'])){
            $permissions['name'] = $input['modules'].'-delete';
        }

        $update = Permission::where('id', $id)->update($permissions);
        if($update){
            FlashMessages::addFlashMessage(
                'success',
                'Permission updated successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to update permission',
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
        //
    }
}
