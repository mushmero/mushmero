<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Traits\PersonaTrait;
use Illuminate\Http\Request;
use App\Models\Interests;
use App\Models\User;
use wadeshuler\FlashMessages\Facades\FlashMessages;

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
        $array = Interests::all();

        $heads = [
            'Name',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                User::find($arr->user_id)->name,
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

        $data = $request->all();

        $store = Interests::create([
            'name' => $data['name'],
            'user_id' => auth()->user()->id,
            'personal_id' => PersonaTrait::getActivePersonaByAuthUser()->id,
        ]);

        if($store){
            FlashMessages::addFlashMessage(
                'success',
                'Interest created successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Interest to create Skill',
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
        $data = Interests::where('id', $id)->firstOrFail();

        $user = User::find($data->user_id)->firstOrFail();
        
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
        $this->validate($request, [
            'name' => 'required',
        ]);

        $data['name'] = $request->input('name');
        $data['user_id'] = auth()->user()->id;
        $data['personal_id'] = PersonaTrait::getActivePersonaByAuthUser()->id;

        $update = Interests::find($id)->update($data);

        if($update){
            FlashMessages::addFlashMessage(
                'success',
                'Interest updated successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to update Interest',
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
        Interests::find($id)->delete();
        FlashMessages::addFlashMessage(
            'success',
            'Interest deleted successfully',
        );
        return redirect()->route('interests');
    }
}
