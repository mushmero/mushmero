<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Traits\PersonaTrait;
use Illuminate\Http\Request;
use App\Models\Socials;
use App\Models\SettingsSocial;
use App\Models\User;

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
        $authUserRoles = auth()->user()->roles->pluck('name');
        foreach($authUserRoles as $authUserRole){
            if($authUserRole == 'Superadmin'){
                $array = Socials::all();
            }else{
                $array = Socials::where(['user_id' => auth()->user()->id])->get();
            }
        }

        $heads = [
            'Name',
            'Link',
            'Icon',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                $arr->link,
                $arr->icon_id ? '<i class="fa-fw '.$arr->allicons->fullname.'"></i>&nbsp;'.$arr->allicons->name : '',
                User::find($arr->user_id)->name,
                '<nobr><a href="'.url('socials/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('socials/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('socials/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null],
        ];

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.socials.list', [
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
        $icons = SettingsSocial::all();

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.socials.create', compact('icons'));
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

        $store = Socials::create([
            'name' => $data['name'],
            'link' => $data['link'],
            'icon_id' => $data['icon'],
            'user_id' => auth()->user()->id,
            'personal_id' => PersonaTrait::getActivePersonaByAuthUser()->id,
        ]);

        if($store){
            flash(
                'Social created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Social',
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
        $data = Socials::where('id', $id)->firstOrFail();

        $user = User::find($data->user_id)->firstOrFail();

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.socials.show', compact('data','user'));
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
        $data = Socials::where('id', $id)->firstOrFail();

        $icons = SettingsSocial::all();

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.socials.edit', compact('data','icons'));
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
        $data['link'] = $request->input('link');
        $data['icon_id'] = $request->input('icon');
        $data['user_id'] = auth()->user()->id;
        $data['personal_id'] = PersonaTrait::getActivePersonaByAuthUser()->id;

        $update = Socials::find($id)->update($data);

        if($update){
            flash(
                'Social updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update Social',
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
        Socials::find($id)->delete();
        flash(
            'Social deleted successfully',
        )->success();
        return redirect()->route('socials');
    }
}
