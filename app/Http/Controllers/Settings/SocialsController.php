<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SettingsSocial;

class SocialsController extends Controller
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
        $socials = SettingsSocial::all();

        $heads = [
            'Icon Name',
            'Type',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($socials as $social){
            $data[] = array(
                '<i class="fa-fw '.$social->fullname.'"></i>&nbsp;'.$social->name,
                $social->type,
                '<nobr><a href="'.url('settings_social/'.$social->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('settings_social/'.$social->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('settings_social/'.$social->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null],
        ];

        return view('settings.social.list', [
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
        return view('settings.social.create');
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
            'fullname' => 'required|unique:settings_socials',
            'name' => 'required|unique:settings_socials',
            'type' => 'required',
        ]);

        $data = $request->all();

        $store = SettingsSocial::create([
            'fullname' => $data['fullname'],
            'name' => $data['name'],
            'type' => $data['type'],
        ]);

        if($store){
            flash(
                'Icon created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Icon',
            )->error();
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
        $data = SettingsSocial::where('id', $id)->firstOrFail();

        return view('settings.social.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = SettingsSocial::where('id', $id)->firstOrFail();

        return view('settings.social.edit', compact('data'));
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
            'fullname' => 'required|unique:settings_socials',
            'name' => 'required|unique:settings_socials',
            'type' => 'required',
        ]);
        
        $data['fullname'] = $request->input('fullname');
        $data['name'] = $request->input('name');
        $data['type'] = $request->input('type');

        $update = SettingsSocial::find($id)->update($data);

        if($update){
            flash(
                'Icon updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update icon',
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
    public function destroy($id)
    {
        SettingsSocial::find($id)->delete();
        flash(
            'Icon deleted successfully',
        )->success();
        return redirect()->route('settings_social');
    }
}
