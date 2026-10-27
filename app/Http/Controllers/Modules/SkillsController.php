<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Traits\PersonaTrait;
use App\Models\SettingsSocial;
use Illuminate\Http\Request;
use App\Models\Skills;
use App\Models\User;

class SkillsController extends Controller
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
        // $authUserRoles = auth()->user()->roles->pluck('name');
        // foreach($authUserRoles as $authUserRole){
        //     if($authUserRole == 'Superadmin'){
                $array = Skills::all();
        //     }else{
        //         $array = Skills::where(['user_id' => auth()->user()->id])->get();
        //     }
        // }

        $heads = [
            'Name',
            'Percentage',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                '<div class="progress"><div role="progressbar" class="progress-bar progress-bar-striped progress-bar-animated" aria-valuenow="'.$arr->percentage.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$arr->percentage.'%; background-color: rgb(52, 125, 241);">'.$arr->percentage.'</div></div>',
                $arr->user->name,
                '<nobr><a href="'.url('skills/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('skills/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('skills/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.skills.list', [
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
            return view('modules.skills.create', compact('icons'));
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
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $activePersona = PersonaTrait::getActivePersonaByAuthUser();
        if (!$activePersona) {
            flash('Please create or activate your Personal profile first.')->warning();
            return redirect()->route('personal');
        }

        $data = $request->all();

        $store = Skills::create([
            'name' => $data['name'],
            'percentage' => $data['percentage'],
            'user_id' => auth()->user()->id,
            'icon_id' => $data['icon'] ?? null,
            'personal_id' => $activePersona->id,
        ]);

        if($store){
            flash('Skill created successfully')->success();
            return redirect()->route('skills');
        }else{
            flash('Unable to create Skill')->error();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Skills::where('id', $id)->firstOrFail();

        $user = $data->user;
        
        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.skills.show', compact('data','user'));
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
        $data = Skills::where('id', $id)->firstOrFail();

        $icons = SettingsSocial::all();
        
        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.skills.edit', compact('data','icons'));
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
        $skill = Skills::findOrFail($id);
        if ($skill->user_id && $skill->user_id != auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'name' => 'required',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $activePersona = PersonaTrait::getActivePersonaByAuthUser();

        $data['name'] = $request->input('name');
        $data['percentage'] = $request->input('percentage');
        $data['icon_id'] = $request->input('icon') ?? $skill->icon_id;
        if ($activePersona) {
            $data['personal_id'] = $activePersona->id;
        }

        $update = $skill->update($data);

        if($update){
            flash('Skill updated successfully')->success();
        }else{
            flash('Unable to update Skill')->error();
        }

        return redirect()->route('skills');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $skill = Skills::findOrFail($id);
        if ($skill->user_id && $skill->user_id != auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $skill->delete();
        flash('Skill deleted successfully')->success();
        return redirect()->route('skills');
    }
}
