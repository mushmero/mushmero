<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Traits\PersonaTrait;
use Illuminate\Http\Request;
use App\Models\Educations;
use App\Models\SettingsEducations;
use App\Models\User;

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
        $authUserRoles = auth()->user()->roles->pluck('name');
        foreach($authUserRoles as $authUserRole){
            if($authUserRole == 'Superadmin'){
                $array = Educations::all();
            }else{
                $array = Educations::where(['user_id' => auth()->user()->id])->get();
            }
        }

        $heads = [
            'Name',
            'Institution',
            'Year',
            'Level',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                $arr->institution,
                $arr->start .' - '. $arr->end,
                $arr->level_id ? $arr->alllevel->name .' (' .$arr->alllevel->level.')' : '',
                User::find($arr->user_id)->name,
                '<nobr><a href="'.url('educations/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('educations/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('educations/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null, null],
        ];

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.educations.list', [
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
        $educations = SettingsEducations::all();
        $configEducations = [
            "placeholder" => "Select level",
            "allowClear" => true,
        ];

        $startDate = [
            'format' => 'YYYY',
            'useCurrent' => false,
        ];

        $endDate = [
            'format' => 'YYYY',
            'useCurrent' => false,
        ];

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.educations.create', compact('educations','configEducations', 'startDate', 'endDate'));
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

        $store = Educations::create([
            'name' => $data['name'],
            'institution' => $data['institution'],
            'start' => $data['startDate'],
            'end' => $data['endDate'],
            'level_id' => $data['level'],
            'user_id' => auth()->user()->id,
            'personal_id' => PersonaTrait::getActivePersonaByAuthUser()->id,
        ]);

        if($store){
            flash(
                'Education created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Education',
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
        $data = Educations::where('id', $id)->firstOrFail();

        $user = User::find($data->user_id)->firstOrFail();

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.educations.show', compact('data','user'));
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
        $data = Educations::where('id', $id)->firstOrFail();

        $educations = SettingsEducations::all();
        $configEducations = [
            "placeholder" => "Select level",
            "allowClear" => true,
        ];

        $startDate = [
            'format' => 'YYYY',
            'useCurrent' => false,
        ];

        $endDate = [
            'format' => 'YYYY',
            'useCurrent' => false,
        ];

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.educations.edit', compact('data','educations','configEducations', 'startDate', 'endDate'));
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
        $data['institution'] = $request->input('institution');
        $data['start'] = $request->input('startDate');
        $data['end'] = $request->input('endDate');
        $data['level_id'] = $request->input('level');
        $data['user_id'] = auth()->user()->id;
        $data['personal_id'] = PersonaTrait::getActivePersonaByAuthUser()->id;

        $update = Educations::find($id)->update($data);

        if($update){
            flash(
                'Education updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update Education',
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
        Educations::find($id)->delete();
        flash(
            'Education deleted successfully',
        )->success();
        return redirect()->route('educations');
    }
}
