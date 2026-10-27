<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SettingsEducations;

class EducationsController extends Controller
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
        $edulevels = SettingsEducations::all();

        $heads = [
            'Name',
            'Level',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($edulevels as $edulevel){
            $data[] = array(
                $edulevel->name,
                $edulevel->level,
                '<nobr><a href="'.url('settings_education/'.$edulevel->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('settings_education/'.$edulevel->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('settings_education/'.$edulevel->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null],
        ];

        return view('settings.education.list', [
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
        return view('settings.education.create');
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
            'name' => 'required|unique:settings_educations',
            'level' => 'required',
        ]);

        $data = $request->all();

        $store = SettingsEducations::create([
            'name' => $data['name'],
            'level' => $data['level'],
        ]);

        if($store){
            flash(
                'Edulevel created successfully',
            )->success();
        }else{
            flash(
                'Unable to create edulevel',
            )->error();
        }

        return redirect()->route('settings_education');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = SettingsEducations::where('id', $id)->firstOrFail();

        return view('settings.education.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = SettingsEducations::where('id', $id)->firstOrFail();

        return view('settings.education.edit', compact('data'));
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
            'name' => 'required|unique:settings_educations,name,' . $id,
            'level' => 'required',
        ]);
        
        $data['name'] = $request->input('name');
        $data['level'] = $request->input('level');

        $update = SettingsEducations::findOrFail($id)->update($data);

        if($update){
            flash(
                'Edulevel updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update edulevel',
            )->error();
        }

         return redirect()->route('settings_education');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SettingsEducations::findOrFail($id)->delete();
        flash(
            'Edulevel deleted successfully',
        )->success();
        return redirect()->route('settings_education');
    }
}
