<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Traits\PersonaTrait;
use Illuminate\Http\Request;
use App\Models\Objectives;
use App\Models\User;
use wadeshuler\FlashMessages\Facades\FlashMessages;

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
        $array = Objectives::all();

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
                User::find($arr->user_id)->name,
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

        $data = $request->all();

        $store = Objectives::create([
            'content' => $data['content'],
            'is_active' => $data['is_active'] == true ? 1 : 0,
            'user_id' => auth()->user()->id,
            'personal_id' => PersonaTrait::getActivePersonaByAuthUser()->id,
        ]);

        if($store){
            FlashMessages::addFlashMessage(
                'success',
                'Objective created successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to create Objective',
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
        $data = Objectives::where('id', $id)->firstOrFail();

        $user = User::find($data->user_id)->firstOrFail(); 
        
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
        $this->validate($request, [
            'name' => 'required',
            'is_active' => 'required',
        ]);

        $data['name'] = $request->input('name');
        $data['is_active'] = $request->input('is_active') == true ? 1 : 0;
        $data['user_id'] = auth()->user()->id;
        $data['personal_id'] = PersonaTrait::getActivePersonaByAuthUser()->id;

        $update = Objectives::find($id)->update($data);

        if($update){
            FlashMessages::addFlashMessage(
                'success',
                'Objective updated successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to update Objective',
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
        Objectives::find($id)->delete();
        FlashMessages::addFlashMessage(
            'success',
            'Objective deleted successfully',
        );
        return redirect()->route('objectives');
    }
}
