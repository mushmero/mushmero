<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Traits\PersonaTrait;
use Illuminate\Http\Request;
use App\Models\Experiences;
use App\Models\User;

class ExperiencesController extends Controller
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
                $array = Experiences::all();
            }else{
                $array = Experiences::where(['user_id' => auth()->user()->id])->get();
            }
        }

        $heads = [
            'Position',
            'Exp Length',
            'Employer',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->position,
                $arr->start .' - '. $arr->end,
                $arr->employer,
                $arr->user->name,
                '<nobr><a href="'.url('experiences/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('experiences/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('experiences/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null],
        ];

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.experiences.list', [
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
        $configTE = [
            "height" => "300",
            "toolbar" => [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['strikethrough', 'superscript', 'subscript']],
                // ['fontsize', ['fontsize']],
                // ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                // ['height', ['height']],
                // ['table', ['table']],
                // ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        ];

        $startDate = [
            'format' => 'DD/MM/YYYY',
            'useCurrent' => false,
        ];

        $endDate = [
            'format' => 'DD/MM/YYYY',
            'useCurrent' => false,
        ];

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.experiences.create', compact('configTE', 'startDate', 'endDate'));
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
            'position' => 'required',
        ]);

        $data = $request->all();

        $store = Experiences::create([
            'position' => $data['position'],
            'start' => $data['startDate'],
            'end' => !empty($data['current']) ? $data['current'] : $data['endDate'],
            'employer' => $data['employer'],
            'user_id' => auth()->user()->id,
            'description' => $data['description'],
            'personal_id' => PersonaTrait::getActivePersonaByAuthUser()->id,
        ]);

        if($store){
            flash(
                'Experience created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Experience',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Experiences  $experiences
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Experiences::where('id', $id)->firstOrFail();

        $user = $data->user;

        $configTE = [
            "height" => "300",
            "toolbar" => [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['strikethrough', 'superscript', 'subscript']],
                // ['fontsize', ['fontsize']],
                // ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                // ['height', ['height']],
                // ['table', ['table']],
                // ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        ];

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.experiences.show', compact('data','user','configTE'));
        }else{
            return view('no-access');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Experiences  $experiences
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Experiences::where('id', $id)->firstOrFail();

        $configTE = [
            "height" => "300",
            "toolbar" => [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['strikethrough', 'superscript', 'subscript']],
                // ['fontsize', ['fontsize']],
                // ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                // ['height', ['height']],
                // ['table', ['table']],
                // ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        ];

        $startDate = [
            'format' => 'DD/MM/YYYY',
            'useCurrent' => false,
        ];

        $endDate = [
            'format' => 'DD/MM/YYYY',
            'useCurrent' => false,
        ];

        if(PersonaTrait::checkPersona()->count() > 0){
            return view('modules.experiences.edit', compact('data','startDate','endDate','configTE'));
        }else{
            return view('no-access');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Experiences  $experiences
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'position' => 'required',
        ]);

        $data['position'] = $request->input('position');
        $data['employer'] = $request->input('employer');
        $data['start'] = $request->input('startDate');
        $data['end'] = !empty($request->input('current')) ? $request->input('current') : $request->input('endDate');
        $data['description'] = $request->input('description');
        $data['user_id'] = auth()->user()->id;
        $data['personal_id'] = PersonaTrait::getActivePersonaByAuthUser()->id;

        $update = Experiences::find($id)->update($data);

        if($update){
            flash(
                'Experience updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update Experience',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Experiences  $experiences
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Experiences::find($id)->delete();
        flash(
            'Experience deleted successfully',
        )->success();
        return redirect()->route('experiences');
    }
}
