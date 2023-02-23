<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Educations;
use App\Models\SettingsEducations;
use App\Models\User;
use wadeshuler\FlashMessages\Facades\FlashMessages;

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
        $array = Educations::all();

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

        return view('modules.educations.list', [
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

        return view('modules.educations.create', compact('educations','configEducations', 'startDate', 'endDate'));
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
        ]);

        if($store){
            FlashMessages::addFlashMessage(
                'success',
                'Education created successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to create Education',
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
        $data = Educations::where('id', $id)->firstOrFail();

        $user = User::find($data->user_id)->firstOrFail();

        return view('modules.educations.show', compact('data','user'));
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

        return view('modules.educations.edit', compact('data','educations','configEducations', 'startDate', 'endDate'));
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

        $update = Educations::find($id)->update($data);

        if($update){
            FlashMessages::addFlashMessage(
                'success',
                'Education updated successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to update Education',
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
        Educations::find($id)->delete();
        FlashMessages::addFlashMessage(
            'success',
            'Education deleted successfully',
        );
        return redirect()->route('educations');
    }
}
