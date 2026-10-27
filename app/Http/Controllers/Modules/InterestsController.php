<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Traits\PersonaTrait;
use Illuminate\Http\Request;
use App\Models\Interests;
use App\Models\User;

class InterestsController extends Controller
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
                $array = Interests::all();
        //     }else{
        //         $array = Interests::where(['user_id' => auth()->user()->id])->get();
        //     }
        // }

        $heads = [
            'Name',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                $arr->user->name,
                '<nobr><a href="'.url('interests/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('interests/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('interests/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null],
        ];

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.interests.list', [
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
            return view('modules.interests.create');
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

        $activePersona = PersonaTrait::getActivePersonaByAuthUser();
        if (!$activePersona) {
            flash('Please create or activate your Personal profile first.')->warning();
            return redirect()->route('personal');
        }

        $data = $request->all();

        $store = Interests::create([
            'name' => $data['name'],
            'user_id' => auth()->user()->id,
            'personal_id' => $activePersona->id,
        ]);

        if($store){
            flash(
                'Interest created successfully',
            )->success();
        }else{
            flash(
                'Interest to create Skill',
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
        $data = Interests::where('id', $id)->firstOrFail();

        $user = $data->user;
        
        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.interests.show', compact('data','user'));
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
        $data = Interests::where('id', $id)->firstOrFail();
        
        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.interests.edit', compact('data'));
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
        $interest = Interests::findOrFail($id);
        if (!auth()->user()->hasRole('Superadmin') && $interest->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'name' => 'required',
        ]);

        $activePersona = PersonaTrait::getActivePersonaByAuthUser();

        $data['name'] = $request->input('name');
        if ($activePersona) {
            $data['personal_id'] = $activePersona->id;
        }

        $update = $interest->update($data);

        if($update){
            flash('Interest updated successfully')->success();
        }else{
            flash('Unable to update Interest')->error();
        }

        return redirect()->route('interests');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interest = Interests::findOrFail($id);
        if (!auth()->user()->hasRole('Superadmin') && $interest->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $interest->delete();
        flash('Interest deleted successfully')->success();
        return redirect()->route('interests');
    }
}
