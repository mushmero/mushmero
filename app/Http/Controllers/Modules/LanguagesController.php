<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Traits\PersonaTrait;
use Illuminate\Http\Request;
use App\Models\Languages;
use App\Models\SettingsLanguage;
use App\Models\User;

class LanguagesController extends Controller
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
                $array = Languages::all();
            }else{
                $array = Languages::where(['user_id' => auth()->user()->id])->get();
            }
        }

        $heads = [
            'Name',
            'Proficiency',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                $arr->proficiency_id ? $arr->allproficiency->proficiency : '',
                User::find($arr->user_id)->name,
                '<nobr><a href="'.url('languages/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('languages/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('languages/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.languages.list', [
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
        $proficiency = SettingsLanguage::all();
        $configProficiency = [
            "placeholder" => "Select proficiency",
            "allowClear" => true,
        ];
        
        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.languages.create', compact('proficiency','configProficiency'));
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

        $store = Languages::create([
            'name' => $data['name'],
            'proficiency_id' => $data['proficiency'],
            'user_id' => auth()->user()->id,
            'personal_id' => PersonaTrait::getActivePersonaByAuthUser()->id,
        ]);

        if($store){
            flash(
                'Language created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Language',
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
        $data = Languages::where('id', $id)->firstOrFail();

        $user = User::find($data->user_id)->firstOrFail();
        
        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.languages.show', compact('data','user'));
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
        $data = Languages::where('id', $id)->firstOrFail();  
              
        $proficiency = SettingsLanguage::all();
        $configProficiency = [
            "placeholder" => "Select proficiency",
            "allowClear" => true,
        ];
        
        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.languages.edit', compact('data','proficiency','configProficiency'));
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
        $data['proficiency_id'] = $request->input('proficiency');
        $data['user_id'] = auth()->user()->id;
        $data['personal_id'] = PersonaTrait::getActivePersonaByAuthUser()->id;

        $update = Languages::find($id)->update($data);

        if($update){
            flash(
                'Language updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update Language',
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
        Languages::find($id)->delete();
        flash(
            'Language deleted successfully',
        )->success();
        return redirect()->route('languages');
    }
}
