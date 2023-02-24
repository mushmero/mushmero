<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Socials;
use App\Models\SettingsSocial;
use App\Models\User;
use wadeshuler\FlashMessages\Facades\FlashMessages;

class SocialsController extends Controller
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
        $array = Socials::all();

        $heads = [
            'Name',
            'Link',
            'Icon',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                $arr->link,
                $arr->icon_id ? '<i class="fa-fw '.$arr->allicons->fullname.'"></i>&nbsp;'.$arr->allicons->name : '',
                User::find($arr->user_id)->name,
                '<nobr><a href="'.url('socials/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('socials/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('socials/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null],
        ];

        return view('modules.socials.list', [
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
        $icons = SettingsSocial::all();

        return view('modules.socials.create', compact('icons'));
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

        $store = Socials::create([
            'name' => $data['name'],
            'link' => $data['link'],
            'icon_id' => $data['icon'],
            'user_id' => auth()->user()->id,
        ]);

        if($store){
            FlashMessages::addFlashMessage(
                'success',
                'Social created successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to create Social',
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
        $data = Socials::where('id', $id)->firstOrFail();

        $user = User::find($data->user_id)->firstOrFail();

        return view('modules.socials.show', compact('data','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Socials::where('id', $id)->firstOrFail();

        $icons = SettingsSocial::all();

        return view('modules.socials.edit', compact('data','icons'));
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
        $data['link'] = $request->input('link');
        $data['icon_id'] = $request->input('icon');
        $data['user_id'] = auth()->user()->id;

        $update = Socials::find($id)->update($data);

        if($update){
            FlashMessages::addFlashMessage(
                'success',
                'Social updated successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to update Social',
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
        Socials::find($id)->delete();
        FlashMessages::addFlashMessage(
            'success',
            'Social deleted successfully',
        );
        return redirect()->route('socials');
    }
}
