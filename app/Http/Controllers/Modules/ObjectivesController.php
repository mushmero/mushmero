<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Traits\PersonaTrait;
use Illuminate\Http\Request;
use App\Models\Objectives;
use App\Models\User;

class ObjectivesController extends Controller
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
                $array = Objectives::all();
        //     }else{
        //         $array = Objectives::where(['user_id' => auth()->user()->id])->get();
        //     }
        // }

        $heads = [
            'Content',
            'Is Active',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->content,
                $arr->is_active == 1 ? 'Yes' : 'No',
                $arr->user->name,
                '<nobr><a href="'.url('objectives/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('objectives/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('objectives/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.objectives.list', [
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
        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.objectives.create');
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
            'content' => 'required',
            'is_active' => 'required',
        ]);

        $activePersona = PersonaTrait::getActivePersonaByAuthUser();
        if (!$activePersona) {
            flash('Please create or activate your Personal profile first.')->warning();
            return redirect()->route('personal');
        }

        $data = $request->all();

        $store = Objectives::create([
            'content' => $data['content'],
            'is_active' => $data['is_active'] == true ? 1 : 0,
            'user_id' => auth()->user()->id,
            'personal_id' => $activePersona->id,
        ]);

        if($store){
            flash('Objective created successfully')->success();
            return redirect()->route('objectives');
        }else{
            flash('Unable to create Objective')->error();
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
        $data = Objectives::where('id', $id)->firstOrFail();

        $user = $data->user; 
        
        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.objectives.show', compact('data','user'));   
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
        $data = Objectives::where('id', $id)->firstOrFail();
        
        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.objectives.edit', compact('data'));  
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
        $objective = Objectives::findOrFail($id);
        if ($objective->user_id && $objective->user_id != auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'content' => 'required',
            'is_active' => 'required',
        ]);

        $activePersona = PersonaTrait::getActivePersonaByAuthUser();

        $data['content'] = $request->input('content');
        $data['is_active'] = $request->input('is_active') == true ? 1 : 0;
        if ($activePersona) {
            $data['personal_id'] = $activePersona->id;
        }

        $update = $objective->update($data);

        if($update){
            flash('Objective updated successfully')->success();
        }else{
            flash('Unable to update Objective')->error();
        }

        return redirect()->route('objectives');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $objective = Objectives::findOrFail($id);
        if ($objective->user_id && $objective->user_id != auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $objective->delete();
        flash('Objective deleted successfully')->success();
        return redirect()->route('objectives');
    }
}
