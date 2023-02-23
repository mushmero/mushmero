<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Languages;
use App\Models\SettingsLanguage;
use App\Models\User;
use wadeshuler\FlashMessages\Facades\FlashMessages;

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
        $array = Languages::all();

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

        return view('modules.languages.list', [
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
        $proficiency = SettingsLanguage::all();
        $configProficiency = [
            "placeholder" => "Select proficiency",
            "allowClear" => true,
        ];

        return view('modules.languages.create', compact('proficiency','configProficiency'));
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
        ]);

        if($store){
            FlashMessages::addFlashMessage(
                'success',
                'Language created successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to create Language',
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
        $data = Languages::where('id', $id)->firstOrFail();

        $user = User::find($data->user_id)->firstOrFail();

        return view('modules.languages.show', compact('data','user'));
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

        return view('modules.languages.edit', compact('data','proficiency','configProficiency'));
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

        $update = Languages::find($id)->update($data);

        if($update){
            FlashMessages::addFlashMessage(
                'success',
                'Language updated successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to update Language',
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
        Languages::find($id)->delete();
        FlashMessages::addFlashMessage(
            'success',
            'Language deleted successfully',
        );
        return redirect()->route('languages');
    }
}
