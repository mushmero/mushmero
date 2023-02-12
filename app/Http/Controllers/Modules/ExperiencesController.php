<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Experiences;
use App\Models\User;
use wadeshuler\FlashMessages\Facades\FlashMessages;

class ExperiencesController extends Controller
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
        $array = Experiences::all();

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
                User::find($arr->user_id)->name,
                '<nobr><a href="'.url('experiences/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('experiences/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('experiences/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null],
        ];

        return view('modules.experiences.list', [
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

        return view('modules.experiences.create', compact('configTE', 'startDate', 'endDate'));
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
        ]);

        if($store){
            FlashMessages::addFlashMessage(
                'success',
                'Experience created successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to create Experience',
            );
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

        $user = User::find($data->user_id)->firstOrFail();

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

        return view('modules.experiences.show', compact('data','user','configTE'));
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

        return view('modules.experiences.edit', compact('data','startDate','endDate','configTE'));
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

        $update = Experiences::find($id)->update($data);

        if($update){
            FlashMessages::addFlashMessage(
                'success',
                'Experience updated successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to update Experience',
            );
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
        FlashMessages::addFlashMessage(
            'success',
            'Experience deleted successfully',
        );
        return redirect()->route('experiences');
    }
}
