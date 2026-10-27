<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SettingsProject;

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
        $array = SettingsProject::all();

        $heads = [
            'Status',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->status,
                '<nobr><a href="'.url('settings_project/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('settings_project/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('settings_project/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null],
        ];

        return view('settings.project.list', [
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
        return view('settings.project.create');
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
            'status' => 'required|unique:settings_projects',
        ]);

        $data = $request->all();

        $store = SettingsProject::create([
            'status' => $data['status'],
        ]);

        if($store){
            flash(
                'Project status created successfully',
            )->success();
        }else{
            flash(
                'Unable to create project status',
            )->error();
        }

        return redirect()->route('settings_project');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = SettingsProject::where('id', $id)->firstOrFail();

        return view('settings.project.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = SettingsProject::where('id', $id)->firstOrFail();

        return view('settings.project.edit', compact('data'));
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
            'status' => 'required|unique:settings_projects,status,' . $id,
        ]);
        
        $data['status'] = $request->input('status');

        $update = SettingsProject::findOrFail($id)->update($data);

        if($update){
            flash(
                'Project status updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update project status',
            )->error();
        }

         return redirect()->route('settings_project');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SettingsProject::findOrFail($id)->delete();
        flash(
            'Project status deleted successfully',
        )->success();
        return redirect()->route('settings_project');
    }
}
