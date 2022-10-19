<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use wadeshuler\FlashMessages\Facades\FlashMessages;
use App\Models\SettingsLanguage;

class LanguagesController extends Controller
{
    protected $btnEdit, $btnDelete, $btnDetails;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
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
        $proficiencies = SettingsLanguage::all();

        $heads = [
            'Proficiency',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($proficiencies as $proficiency){
            $data[] = array(
                $proficiency->proficiency,
                '<nobr><a href="'.url('settings_language/'.$proficiency->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('settings_language/'.$proficiency->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('settings_language/'.$proficiency->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, ['orderable' => false]],
        ];

        return view('settings.language.list', [
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
        return view('settings.language.create');
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
            'proficiency' => 'required|unique:settings_languages',
        ]);

        $data = $request->all();

        $store = SettingsLanguage::create([
            'proficiency' => $data['proficiency'],
        ]);

        if($store){
            FlashMessages::addFlashMessage(
                'success',
                'Proficiency created successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to create proficiency',
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
        $data = SettingsLanguage::where('id', $id)->firstOrFail();

        return view('settings.language.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = SettingsLanguage::where('id', $id)->firstOrFail();

        return view('settings.language.edit', compact('data'));
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
            'proficiency' => 'required|unique:settings_languages',
        ]);
        
        $data['proficiency'] = $request->input('proficiency');

        $update = SettingsLanguage::find($id)->update($data);

        if($update){
            FlashMessages::addFlashMessage(
                'success',
                'Proficiency updated successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to update proficiency',
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
        SettingsLanguage::find($id)->delete();
        FlashMessages::addFlashMessage(
            'success',
            'Proficiency deleted successfully',
        );
        return redirect()->route('settings_language');
    }
}
