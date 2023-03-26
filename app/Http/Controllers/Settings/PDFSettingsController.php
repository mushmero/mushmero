<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SettingsPDF;
use App\Models\User;

class PDFSettingsController extends Controller
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
        $array = SettingsPDF::all();

        $heads = [
            'Name',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                $arr->user->name,
                '<nobr><a href="'.url('settings_pdf/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('settings_pdf/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('settings_pdf/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null],
        ];

        return view('settings.pdf.list', [
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
        return view('settings.pdf.create');
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

        $store = SettingsPDF::create([
            'name' => $data['name'],
            'margin_top' => $data['margin_top'],
            'margin_bottom' => $data['margin_bottom'],
            'margin_left' => $data['margin_left'],
            'margin_right' => $data['margin_right'],
            'header' => $data['header'],
            'footer' => $data['footer'],
            'user_id' => auth()->user()->id,
        ]);

        if($store){
            flash(
                'PDF settings created successfully',
            )->success();
        }else{
            flash(
                'Unable to create PDF settings',
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
        $data = SettingsPDF::where('id', $id)->firstOrFail();

        $user = $data->user;

        return view('settings.pdf.show', compact('data','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = SettingsPDF::where('id', $id)->firstOrFail();

        return view('settings.pdf.edit', compact('data'));
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
        $data['margin_top'] = $request->input('margin_top');
        $data['margin_bottom'] = $request->input('margin_bottom');
        $data['margin_left'] = $request->input('margin_left');
        $data['margin_right'] = $request->input('margin_right');
        $data['header'] = $request->input('header');
        $data['footer'] = $request->input('footer');
        $data['user_id'] = auth()->user()->id;

        $update = SettingsPDF::find($id)->update($data);

        if($update){
            flash(
                'PDF settings updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update PDF settings',
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
        SettingsPDF::find($id)->delete();
        flash(
            'PDF settings deleted successfully',
        )->success();
        return redirect()->route('settings_pdf');
    }
}
