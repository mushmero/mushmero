<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Traits\PersonaTrait;
use Illuminate\Http\Request;
use App\Models\Skills;
use App\Models\User;
use wadeshuler\FlashMessages\Facades\FlashMessages;

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
        $array = Skills::all();

        $heads = [
            'Name',
            'Percentage',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                $arr->percentage,
                User::find($arr->user_id)->name,
                '<nobr><a href="'.url('skills/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('skills/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('skills/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        if(PersonaTrait::checkPersona()){
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
        if(PersonaTrait::checkPersona()){
            return view('modules.skills.create');
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
            'percentage' => 'required|min:1|max:100',
        ]);

        $data = $request->all();

        $store = Skills::create([
            'name' => $data['name'],
            'percentage' => $data['percentage'],
            'user_id' => auth()->user()->id,
        ]);

        if($store){
            FlashMessages::addFlashMessage(
                'success',
                'Skill created successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to create Skill',
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
        $data = Skills::where('id', $id)->firstOrFail();

        $user = User::find($data->user_id)->firstOrFail();
        
        if(PersonaTrait::checkPersona()){
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
        
        if(PersonaTrait::checkPersona()){
            return view('modules.skills.edit', compact('data'));
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
            'percentage' => 'required|min:1|max:100',
        ]);

        $data['name'] = $request->input('name');
        $data['percentage'] = $request->input('percentage');
        $data['user_id'] = auth()->user()->id;

        $update = Skills::find($id)->update($data);

        if($update){
            FlashMessages::addFlashMessage(
                'success',
                'Skill updated successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to update Skill',
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
        Skills::find($id)->delete();
        FlashMessages::addFlashMessage(
            'success',
            'Skill deleted successfully',
        );
        return redirect()->route('skills');
    }
}
