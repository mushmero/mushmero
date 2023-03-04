<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Traits\PersonaTrait;
use App\Models\SettingsProject;
use Illuminate\Http\Request;
use App\Models\Projects;
use App\Models\User;
use wadeshuler\FlashMessages\Facades\FlashMessages;

class ProjectsController extends Controller
{
    protected $btnEdit, $btnDelete, $btnDetails;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
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
        $array = Projects::all();

        $heads = [
            'Name',
            'Status',
            'Description',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                $arr->status_id ? $arr->allstatus->status : '',
                $arr->description,
                User::find($arr->user_id)->name,
                '<nobr><a href="'.url('projects/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('projects/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('projects/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null],
        ];

        if(PersonaTrait::checkPersona()){
            return view('modules.projects.list', [
                'heads' => $heads,
                'config' => $config,
            ]);
        }else{
            return view('no-access');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $configTE = [
            "height" => "300",
            "toolbar" => [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['strikethrough', 'superscript', 'subscript']],
                // ['fontsize', ['fontsize']],
                // ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                // ['height', ['height']],
                // ['table', ['table']],
                // ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        ];
        
        $status = SettingsProject::all();
        $configStatus = [
            "placeholder" => "Select status",
            "allowClear" => true,
        ];
        
        if(PersonaTrait::checkPersona()){
            return view('modules.projects.create', compact('configTE','status','configStatus')); 
        }else{
            return view('no-access');
        }
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
            'name' => 'required',
        ]);

        $data = $request->all();

        $store = Projects::create([
            'name' => $data['name'],
            'status_id' => $data['status'],
            'user_id' => auth()->user()->id,
            'description' => $data['description'],
        ]);

        if($store){
            FlashMessages::addFlashMessage(
                'success',
                'Project created successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to create Project',
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
        $data = Projects::where('id', $id)->firstOrFail();

        $user = User::find($data->user_id)->firstOrFail();

        $configTE = [
            "height" => "300",
            "toolbar" => [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['strikethrough', 'superscript', 'subscript']],
                // ['fontsize', ['fontsize']],
                // ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                // ['height', ['height']],
                // ['table', ['table']],
                // ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        ];
        
        if(PersonaTrait::checkPersona()){
            return view('modules.projects.show', compact('data','user','configTE'));
        }else{
            return view('no-access');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Projects::where('id', $id)->firstOrFail();

        $configTE = [
            "height" => "300",
            "toolbar" => [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['strikethrough', 'superscript', 'subscript']],
                // ['fontsize', ['fontsize']],
                // ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                // ['height', ['height']],
                // ['table', ['table']],
                // ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        ];
        
        $status = SettingsProject::all();
        $configStatus = [
            "placeholder" => "Select status",
            "allowClear" => true,
        ];
        
        if(PersonaTrait::checkPersona()){
            return view('modules.projects.edit', compact('data','status','configStatus','configTE'));
        }else{
            return view('no-access');
        }
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
        ]);

        $data['name'] = $request->input('name');
        $data['status_id'] = $request->input('status');
        $data['description'] = $request->input('description');
        $data['user_id'] = auth()->user()->id;

        $update = Projects::find($id)->update($data);

        if($update){
            FlashMessages::addFlashMessage(
                'success',
                'Project updated successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to update Project',
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
        Projects::find($id)->delete();
        FlashMessages::addFlashMessage(
            'success',
            'Project deleted successfully',
        );
        return redirect()->route('projects');
    }
}
